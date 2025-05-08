<?php
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Insertar diagnóstico
    $stmt = $conn->prepare("
        INSERT INTO diagnosticos (
            fecha_diagnostico, 
            nombre_cliente, 
            industria, 
            tamano_empresa, 
            puntuacion_total, 
            porcentaje_implementacion, 
            observaciones_generales
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_POST['fecha_diagnostico'],
        $_POST['nombre_cliente'],
        $_POST['industria'],
        $_POST['tamano_empresa'],
        $_POST['puntuacion_total'],
        $_POST['porcentaje_implementacion'],
        $_POST['observaciones_generales']
    ]);
    
    $diagnostico_id = $conn->lastInsertId();
    
    // Guardar respuestas
    $stmt = $conn->prepare("
        INSERT INTO respuestas (
            diagnostico_id, 
            pregunta_id, 
            calificacion, 
            observaciones
        ) VALUES (?, ?, ?, ?)
    ");
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'pregunta_') === 0) {
            $pregunta_id = substr($key, 9);
            $observacion = $_POST['observacion_' . $pregunta_id] ?? '';
            
            $stmt->execute([
                $diagnostico_id,
                $pregunta_id,
                $value,
                $observacion
            ]);
        }
    }
    
    // Guardar recomendaciones si existen
    if (isset($_POST['recomendaciones']) && is_array($_POST['recomendaciones'])) {
        $stmt = $conn->prepare("
            INSERT INTO recomendaciones (
                diagnostico_id, 
                texto_recomendacion, 
                prioridad
            ) VALUES (?, ?, ?)
        ");
        
        foreach ($_POST['recomendaciones'] as $index => $recomendacion) {
            $stmt->execute([
                $diagnostico_id,
                $recomendacion,
                $index + 1
            ]);
        }
    }
    
    // Guardar plan de acción si existe
    if (isset($_POST['plan_accion']) && is_array($_POST['plan_accion'])) {
        $stmt = $conn->prepare("
            INSERT INTO plan_accion (
                diagnostico_id, 
                accion, 
                responsable, 
                fecha_limite, 
                estado
            ) VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($_POST['plan_accion'] as $accion) {
            $stmt->execute([
                $diagnostico_id,
                $accion['accion'],
                $accion['responsable'],
                $accion['fecha_limite'],
                $accion['estado']
            ]);
        }
    }
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Diagnóstico guardado exitosamente',
        'diagnostico_id' => $diagnostico_id
    ]);
    
} catch(PDOException $e) {
    // Revertir transacción en caso de error
    $conn->rollBack();
    
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el diagnóstico: ' . $e->getMessage()
    ]);
}
?> 