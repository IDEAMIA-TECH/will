<?php
require_once 'database.php';

echo "<h2>Prueba de Conexión a la Base de Datos</h2>";

try {
    // Intentar obtener información del servidor
    $serverInfo = $conn->getAttribute(PDO::ATTR_SERVER_INFO);
    $serverVersion = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
    
    echo "<div style='background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>Conexión exitosa!</strong><br>";
    echo "Información del servidor: " . $serverInfo . "<br>";
    echo "Versión del servidor: " . $serverVersion . "<br>";
    echo "</div>";

    // Intentar listar las tablas existentes
    echo "<h3>Tablas existentes en la base de datos:</h3>";
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay tablas en la base de datos.</p>";
    }

    // Verificar permisos del usuario
    echo "<h3>Permisos del usuario:</h3>";
    $permissions = $conn->query("SHOW GRANTS FOR CURRENT_USER")->fetchAll(PDO::FETCH_COLUMN);
    echo "<pre>";
    print_r($permissions);
    echo "</pre>";

} catch(PDOException $e) {
    echo "<div style='background-color: #ffebee; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>Error de conexión:</strong><br>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Código: " . $e->getCode() . "<br>";
    echo "</div>";
    
    // Información adicional para diagnóstico
    echo "<h3>Información de conexión:</h3>";
    echo "<ul>";
    echo "<li>Host: " . DB_HOST . "</li>";
    echo "<li>Usuario: " . DB_USER . "</li>";
    echo "<li>Base de datos: " . DB_NAME . "</li>";
    echo "</ul>";
}
?> 