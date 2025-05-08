<?php
require_once 'database.php';

try {
    // Leer el archivo SQL
    $sql = file_get_contents('database.sql');
    
    // Ejecutar las consultas SQL
    $conn->exec($sql);
    
    echo "Base de datos creada exitosamente.<br>";
    echo "Tablas creadas:<br>";
    echo "- secciones<br>";
    echo "- preguntas<br>";
    echo "- diagnosticos<br>";
    echo "- respuestas<br>";
    echo "- recomendaciones<br>";
    echo "- plan_accion<br>";
    echo "<br>Datos iniciales insertados correctamente.";
    
} catch(PDOException $e) {
    echo "Error durante la instalación: " . $e->getMessage();
}
?> 