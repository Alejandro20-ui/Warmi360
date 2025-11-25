<?php
require_once __DIR__ . '/../backend/conexion.php';

if ($_POST) {
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (!$nombres || !$apellidos || !$contrasena) {
        die("Todos los campos son obligatorios.");
    }

    // Hashear la contraseña (¡esto es lo importante!)
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar en la BD
    $stmt = $conn->prepare("INSERT INTO superadmin (nombres, apellidos, contraseña) VALUES (?, ?, ?)");
    if ($stmt->execute([$nombres, $apellidos, $contrasena_hash])) {
        echo "<h2 style='color:green;text-align:center;'>✅ Superadmin creado con contraseña hasheada.</h2>";
        echo "<p style='text-align:center;'><a href='login.php'>Ir al login</a></p>";
        // 🔥 ¡ELIMINA ESTE ARCHIVO DESPUÉS DE USARLO!
    } else {
        echo "<h2 style='color:red;text-align:center;'>❌ Error al crear el superadmin.</h2>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Superadmin</title>
    <link rel="icon" type="image/jpeg" href="../img/image.jpeg">
    <style>
        body { font-family: sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        .form h2 { text-align: center; margin-bottom: 20px; }
        .form input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; }
        .form button { width: 100%; padding: 10px; background: #2575fc; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .form button:hover { background: #1a68e8; }
    </style>
</head>
<body>
    <div class="form">
        <h2>Crear Superadmin</h2>
        <form method="POST">
            <input type="text" name="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Crear cuenta</button>
        </form>
    </div>
</body>
</html>