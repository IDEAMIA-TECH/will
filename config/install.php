<?php
require_once 'database.php';

// Función para mostrar mensajes de error o éxito
function mostrarMensaje($mensaje, $tipo = 'success') {
    echo "<div style='padding: 10px; margin: 10px 0; border-radius: 5px; " . 
         ($tipo == 'error' ? "background-color: #ffebee; color: #c62828;" : "background-color: #e8f5e9; color: #2e7d32;") . 
         "'>$mensaje</div>";
}

try {
    // Verificar conexión
    if ($conn) {
        mostrarMensaje("Conexión a la base de datos establecida correctamente.");
    }

    // Leer el archivo SQL
    $sql = file_get_contents('database.sql');
    if (!$sql) {
        throw new Exception("No se pudo leer el archivo database.sql");
    }
    mostrarMensaje("Archivo SQL leído correctamente.");

    // Dividir el SQL en consultas individuales
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    // Ejecutar cada consulta por separado
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $conn->exec($query);
                mostrarMensaje("Consulta ejecutada: " . substr($query, 0, 50) . "...");
            } catch (PDOException $e) {
                mostrarMensaje("Error en consulta: " . substr($query, 0, 50) . "...<br>Error: " . $e->getMessage(), 'error');
            }
        }
    }

    // Verificar las tablas creadas
    $tables = ['secciones', 'preguntas', 'diagnosticos', 'respuestas', 'recomendaciones', 'plan_accion'];
    $tablesCreated = [];
    
    foreach ($tables as $table) {
        try {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() > 0) {
                $tablesCreated[] = $table;
            }
        } catch (PDOException $e) {
            mostrarMensaje("Error al verificar tabla $table: " . $e->getMessage(), 'error');
        }
    }

    if (count($tablesCreated) == count($tables)) {
        mostrarMensaje("Todas las tablas fueron creadas exitosamente.");
    } else {
        mostrarMensaje("Algunas tablas no se crearon correctamente. Tablas creadas: " . implode(', ', $tablesCreated), 'error');
    }

    // Verificar datos iniciales
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM secciones");
        $seccionesCount = $stmt->fetchColumn();
        mostrarMensaje("Secciones creadas: $seccionesCount");

        $stmt = $conn->query("SELECT COUNT(*) FROM preguntas");
        $preguntasCount = $stmt->fetchColumn();
        mostrarMensaje("Preguntas creadas: $preguntasCount");
    } catch (PDOException $e) {
        mostrarMensaje("Error al verificar datos iniciales: " . $e->getMessage(), 'error');
    }

} catch(Exception $e) {
    mostrarMensaje("Error durante la instalación: " . $e->getMessage(), 'error');
}
?> 