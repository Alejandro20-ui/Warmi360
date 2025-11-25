<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if (isset($_SESSION['superadmin']) && $_SESSION['superadmin'] === true) {
    header("Location: principal.php");
    exit;
}

require_once __DIR__ . '/../backend/conexion.php';

function getRealIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'];
}

$ip = getRealIP();
$error = '';

// === Verificar bloqueo: 2+ intentos fallidos en 60s ===
$check_blocked = $conn->prepare("
    SELECT COUNT(*) AS fallidos
    FROM intrusiones_web
    WHERE ip_address = ?
      AND estado = 'fallido'
      AND fecha > NOW() - INTERVAL 60 SECOND
");
$check_blocked->execute([$ip]);
if ($check_blocked->fetch()['fallidos'] >= 2) {

    $log = $conn->prepare("
        INSERT INTO intrusiones_web (ip_address, contraseña_usada, intento_exitoso, estado)
        VALUES (?, '(bloqueado)', false, 'fallido')
    ");
    $log->execute([$ip]);
    die("<h2 style='text-align:center; color:red;'>🔒 Bloqueado temporalmente.</h2>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($contrasena)) {
        $error = "Ingresa la contraseña.";
    } else {
        $stmt = $conn->prepare("SELECT contraseña FROM superadmin LIMIT 1");
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin && password_verify($contrasena, $admin['contraseña'])) {
            $check_sospechoso = $conn->prepare("
                SELECT COUNT(*) AS previos
                FROM intrusiones_web
                WHERE ip_address = ?
                  AND estado = 'fallido'
            ");
            $check_sospechoso->execute([$ip]);
            $es_sospechoso = $check_sospechoso->fetch()['previos'] > 0;

            if ($es_sospechoso) {
                $estado = 'ingreso_sospechoso';
            } else {
                $estado = 'legítimo';
            }
            $log = $conn->prepare("
                INSERT INTO intrusiones_web (ip_address, contraseña_usada, intento_exitoso, estado)
                VALUES (?, ?, true, ?)
            ");
            $log->execute([$ip, $contrasena, $estado]);

            $_SESSION['superadmin'] = true;
            header("Location: principal.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
            $log = $conn->prepare("
                INSERT INTO intrusiones_web (ip_address, contraseña_usada, intento_exitoso, estado)
                VALUES (?, ?, false, 'fallido')
            ");
            $log->execute([$ip, $contrasena]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Superadmin - Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" type="image/jpeg" href="../img/image.jpeg">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Acceso Superadmin</h2>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="input-group">
                    <input type="password" name="contrasena" required autofocus placeholder="Contraseña">
                </div>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </div>
</body>
</html>