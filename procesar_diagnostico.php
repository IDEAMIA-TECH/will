<?php
require_once 'config/database.php';

// Función para mostrar mensajes de error o éxito
function mostrarMensaje($mensaje, $tipo = 'success') {
    echo "<div class='message message-" . $tipo . " fade-in'>$mensaje</div>";
}

// Función para logging
function logDebug($message, $data = null) {
    $log = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $log .= " - Data: " . print_r($data, true);
    }
    error_log($log . "\n", 3, "debug.log");
}

// Log de los datos POST recibidos
logDebug('POST recibido en procesar_diagnostico.php', $_POST);

// Validar campos obligatorios
$nombre_cliente = $_POST['nombre_cliente'] ?? null;
$industria = $_POST['industria'] ?? null;
$tamano_empresa = $_POST['tamano_empresa'] ?? null;
$fecha_diagnostico = $_POST['fecha_diagnostico'] ?? null;

if (!$nombre_cliente || !$industria || !$tamano_empresa || !$fecha_diagnostico) {
    mostrarMensaje("Faltan datos obligatorios del diagnóstico. Por favor, completa todos los campos requeridos.", 'error');
    logDebug('Faltan datos obligatorios', [
        'nombre_cliente' => $nombre_cliente,
        'industria' => $industria,
        'tamano_empresa' => $tamano_empresa,
        'fecha_diagnostico' => $fecha_diagnostico
    ]);
    exit;
}

try {
    // Validar y formatear la fecha
    $fecha_diagnostico = !empty($_POST['fecha_diagnostico']) ? $_POST['fecha_diagnostico'] : date('Y-m-d');
    
    logDebug("Iniciando procesamiento de diagnóstico", [
        'fecha' => $fecha_diagnostico,
        'POST_data' => $_POST
    ]);
    
    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar diagnóstico
    $stmt = $conn->prepare("
        INSERT INTO diagnosticos (
            fecha_diagnostico, nombre_cliente, industria, tamano_empresa,
            observaciones_generales
        ) VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $fecha_diagnostico,
        $_POST['nombre_cliente'],
        $_POST['industria'],
        $_POST['tamano_empresa'],
        $_POST['observaciones_generales'] ?? null
    ]);

    $diagnostico_id = $conn->lastInsertId();
    logDebug("Diagnóstico creado", ['diagnostico_id' => $diagnostico_id]);

    // Procesar Momento Empresarial
    $puntuacion_total = 0;
    $total_preguntas = 0;
    $stmt_momento = $conn->prepare("
        INSERT INTO respuestas (
            diagnostico_id, pregunta_id, calificacion, observaciones
        ) VALUES (?, ?, ?, ?)
    ");
    for ($i = 1; $i <= 9; $i++) {
        $key = 'momento_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            if ($valor < 0 || $valor > 5) $valor = max(0, min(5, $valor));
            logDebug("Procesando momento empresarial", [
                'pregunta' => $key,
                'valor' => $valor,
                'pregunta_id' => 'M1_' . $i
            ]);
            $stmt_momento->execute([
                $diagnostico_id,
                'M1_' . $i,
                $valor,
                null
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }

    // Procesar switches adicionales de Momento Empresarial (solo informativos, no suman)
    $stmt_switch = $conn->prepare("
        INSERT INTO respuestas (
            diagnostico_id, pregunta_id, calificacion, observaciones
        ) VALUES (?, ?, ?, ?)
    ");
    for ($i = 1; $i <= 10; $i++) {
        $key = 'momento_switch_' . $i;
        $valor = isset($_POST[$key]) ? 1 : 0;
        if ($valor < 0 || $valor > 5) $valor = max(0, min(5, $valor));
        logDebug("Procesando switch momento empresarial", [
            'pregunta' => $key,
            'valor' => $valor,
            'pregunta_id' => 'M1S_' . $i
        ]);
        $stmt_switch->execute([
            $diagnostico_id,
            'M1S_' . $i,
            $valor,
            null
        ]);
        // NO sumar a $puntuacion_total ni a $total_preguntas
    }

    // Procesar ÁREAS DE ACCIÓN
    $stmt_area = $conn->prepare("
        INSERT INTO respuestas (
            diagnostico_id, pregunta_id, calificacion, observaciones
        ) VALUES (?, ?, ?, ?)
    ");
    for ($i = 1; $i <= 10; $i++) {
        $key = 'area_accion_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            if ($valor < 0 || $valor > 5) $valor = max(0, min(5, $valor));
            $comentario = $_POST['comentario_area_accion_' . $i] ?? null;
            logDebug("Procesando área de acción", [
                'pregunta' => $key,
                'valor' => $valor,
                'pregunta_id' => 'A2_' . $i
            ]);
            $stmt_area->execute([
                $diagnostico_id,
                'A2_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }

    // Procesar PITS Comercial - Dirección Comercial
    for ($i = 1; $i <= 3; $i++) {
        $key = 'pits_dircom_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_dircom_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_DIRCOM_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Proceso de Gestión Comercial
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_gestion_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_gestion_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_GESTION_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Sistema de Información
    $key = 'pits_info_1';
    if (isset($_POST[$key])) {
        $valor = (int)$_POST[$key];
        $comentario = $_POST['comentario_pits_info_1'] ?? null;
        $stmt = $conn->prepare("
            INSERT INTO respuestas (
                diagnostico_id, pregunta_id, calificacion, observaciones
            ) VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $diagnostico_id,
            'PITS_INFO_1',
            $valor,
            $comentario
        ]);
        $puntuacion_total += $valor;
        $total_preguntas++;
    }
    // Estrategias y Tácticas
    $key = 'pits_estrategia_1';
    if (isset($_POST[$key])) {
        $valor = (int)$_POST[$key];
        $comentario = $_POST['comentario_pits_estrategia_1'] ?? null;
        $stmt = $conn->prepare("
            INSERT INTO respuestas (
                diagnostico_id, pregunta_id, calificacion, observaciones
            ) VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $diagnostico_id,
            'PITS_ESTRATEGIA_1',
            $valor,
            $comentario
        ]);
        $puntuacion_total += $valor;
        $total_preguntas++;
    }
    // Métricas Comerciales
    for ($i = 1; $i <= 2; $i++) {
        $key = 'pits_metricas_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_metricas_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_METRICAS_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }

    // Procesar PITS CALIDAD & PRODUCTIVIDAD - Enfoque por Resultados
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_resultados_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_RES_' . $i,
                $valor,
                null
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Enfoque por Proceso
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_proceso_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_PROC_' . $i,
                $valor,
                null
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Calidad y Mejora Continua
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_calidad_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_CAL_' . $i,
                $valor,
                null
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Oportunidad de Mejora (textos)
    if (!empty($_POST['oportunidad_factores'])) {
        $stmt = $conn->prepare("
            INSERT INTO respuestas (
                diagnostico_id, pregunta_id, calificacion, observaciones
            ) VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $diagnostico_id,
            'PITS_MEJ_1',
            null,
            $_POST['oportunidad_factores']
        ]);
    }
    if (!empty($_POST['oportunidad_puntos'])) {
        $stmt = $conn->prepare("
            INSERT INTO respuestas (
                diagnostico_id, pregunta_id, calificacion, observaciones
            ) VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $diagnostico_id,
            'PITS_MEJ_2',
            null,
            $_POST['oportunidad_puntos']
        ]);
    }

    // Procesar PITS MAXIMIZACIÓN DE CAPACIDADES - Impacto del Capital Humano
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_capital_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_capital_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_CAPITAL_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Inventario de Capacidades
    for ($i = 1; $i <= 4; $i++) {
        $key = 'pits_inventario_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_inventario_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_INVENTARIO_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }
    // Maximización de Capacidades
    for ($i = 1; $i <= 3; $i++) {
        $key = 'pits_max_' . $i;
        if (isset($_POST[$key])) {
            $valor = (int)$_POST[$key];
            $comentario = $_POST['comentario_pits_max_' . $i] ?? null;
            $stmt = $conn->prepare("
                INSERT INTO respuestas (
                    diagnostico_id, pregunta_id, calificacion, observaciones
                ) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $diagnostico_id,
                'PITS_MAX_' . $i,
                $valor,
                $comentario
            ]);
            $puntuacion_total += $valor;
            $total_preguntas++;
        }
    }

    // Procesar PITS CALIDAD & PRODUCTIVIDAD - Tipo de Proceso
    if (isset($_POST['tipo_proceso'])) {
        $valor = $_POST['tipo_proceso'];
        $stmt = $conn->prepare("
            INSERT INTO respuestas (
                diagnostico_id, pregunta_id, calificacion, observaciones
            ) VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $diagnostico_id,
            'PITS_TIPO',
            $valor,
            null
        ]);
    }

    // Calcular porcentaje de implementación
    $porcentaje = $total_preguntas > 0 ? ($puntuacion_total / ($total_preguntas * 1)) * 100 : 0;
    logDebug("Cálculo de puntuación", [
        'puntuacion_total' => $puntuacion_total,
        'total_preguntas' => $total_preguntas,
        'porcentaje' => $porcentaje
    ]);

    // Actualizar puntuación total y porcentaje
    $stmt = $conn->prepare("
        UPDATE diagnosticos 
        SET puntuacion_total = ?, porcentaje_implementacion = ?
        WHERE id = ?
    ");
    $stmt->execute([$puntuacion_total, $porcentaje, $diagnostico_id]);

    // Procesar recomendaciones
    if (!empty($_POST['recomendaciones'])) {
        $stmt = $conn->prepare("
            INSERT INTO recomendaciones (
                diagnostico_id, texto_recomendacion, prioridad
            ) VALUES (?, ?, ?)
        ");

        foreach ($_POST['recomendaciones'] as $index => $recomendacion) {
            if (!empty($recomendacion)) {
                $stmt->execute([
                    $diagnostico_id,
                    $recomendacion,
                    $index + 1
                ]);
            }
        }
    }

    // Procesar plan de acción
    if (!empty($_POST['acciones'])) {
        $stmt = $conn->prepare("
            INSERT INTO plan_accion (
                diagnostico_id, accion, responsable, fecha_limite, estado
            ) VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($_POST['acciones'] as $index => $accion) {
            if (!empty($accion)) {
                $stmt->execute([
                    $diagnostico_id,
                    $accion,
                    $_POST['responsables'][$index] ?? null,
                    $_POST['fechas'][$index] ?? null,
                    'Pendiente'
                ]);
            }
        }
    }

    // Confirmar transacción
    $conn->commit();
    logDebug("Transacción completada exitosamente");

    // Responder para AJAX
    echo 'OK';
    exit;

} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $conn->rollBack();
    logDebug("Error en la transacción", [
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
    // Responder error plano para AJAX
    http_response_code(500);
    echo 'Error al procesar el diagnóstico: ' . $e->getMessage();
    exit;
}
?> 