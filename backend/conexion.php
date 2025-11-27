<?php
$host = $_ENV['DB_HOST'] ?? 'maglev.proxy.rlwy.net';
$port = $_ENV['DB_PORT'] ?? '50204';
$database = $_ENV['DB_NAME'] ?? 'alertamujer';
$user = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? 'CZhVEBZHQRoZvxHsUoPlOrWgSTXnacGc';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("Error de conexión DB: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
    exit;
}
?>