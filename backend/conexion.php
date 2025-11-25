<?php
$host = "maglev.proxy.rlwy.net";
$port = 50204;
$database = "alertamujer";
$user = "root";
$password = "CZhVEBZHQRoZvxHsUoPlOrWgSTXnacGc";

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
} catch (PDOException $e) {
    // Si la conexión falla, se muestra el error y se detiene la ejecución.
    error_log("Error fatal de conexión DB: " . $e->getMessage()); 
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
    exit;
}
?>