<?php
require_once 'config/database.php';

// Función para mostrar mensajes de error o éxito
function mostrarMensaje($mensaje, $tipo = 'success') {
    echo "<div class='message message-" . $tipo . " fade-in'>$mensaje</div>";
}

try {
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
        $_POST['fecha_diagnostico'],
        $_POST['nombre_cliente'],
        $_POST['industria'],
        $_POST['tamano_empresa'],
        $_POST['observaciones_generales']
    ]);

    $diagnostico_id = $conn->lastInsertId();

    // Procesar respuestas
    $stmt = $conn->prepare("
        INSERT INTO respuestas (
            diagnostico_id, pregunta_id, calificacion, observaciones
        ) VALUES (?, ?, ?, ?)
    ");

    $puntuacion_total = 0;
    $total_preguntas = 0;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'calificacion_') === 0) {
            $pregunta_id = substr($key, 12);
            $observacion_key = 'observacion_' . $pregunta_id;
            
            $stmt->execute([
                $diagnostico_id,
                $pregunta_id,
                $value,
                $_POST[$observacion_key] ?? null
            ]);

            $puntuacion_total += (int)$value;
            $total_preguntas++;
        }
    }

    // Calcular porcentaje de implementación
    $porcentaje = ($puntuacion_total / ($total_preguntas * 5)) * 100;

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
                    $_POST['responsables'][$index],
                    $_POST['fechas'][$index],
                    'Pendiente'
                ]);
            }
        }
    }

    // Confirmar transacción
    $conn->commit();

    // Redirigir a la página de éxito
    header("Location: ver_diagnostico.php?id=" . $diagnostico_id);
    exit;

} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $conn->rollBack();
    mostrarMensaje("Error al procesar el diagnóstico: " . $e->getMessage(), 'error');
}
?> 