<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'ideamiadev_will');
define('DB_PASS', 'a8Lb39FjCLi#rdk4');
define('DB_NAME', 'ideamiadev_will');

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?> 