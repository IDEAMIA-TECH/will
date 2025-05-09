<?php
require_once 'database.php';

try {
    // Drop existing tables
    $tables = [
        'plan_accion',
        'recomendaciones',
        'respuestas',
        'preguntas',
        'secciones',
        'diagnosticos'
    ];

    foreach ($tables as $table) {
        $conn->exec("DROP TABLE IF EXISTS $table");
        echo "Table $table dropped successfully<br>";
    }

    // Include the installation script to recreate tables
    require_once 'install.php';
    
    echo "Database reinstallation completed successfully!";
} catch (PDOException $e) {
    echo "Error during reinstallation: " . $e->getMessage();
}
?> 