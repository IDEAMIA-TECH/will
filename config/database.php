<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ideamiadev_will');
define('DB_PASS', 'a8Lb39FjCLi#rdk4');
define('DB_NAME', 'ideamiadev_will');

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        )
    );
} catch(PDOException $e) {
    // Mostrar mensaje de error más detallado
    $error = "Error de conexión: " . $e->getMessage() . "\n";
    $error .= "Host: " . DB_HOST . "\n";
    $error .= "Usuario: " . DB_USER . "\n";
    $error .= "Base de datos: " . DB_NAME . "\n";
    echo $error;
    exit;
}
?> 