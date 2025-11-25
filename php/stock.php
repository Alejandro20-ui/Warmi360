<?php
session_start();

if (!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] !== true) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require __DIR__ . '/../backend/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $disponibles = filter_var($_POST['disponibles'] ?? 0, FILTER_VALIDATE_INT);
    $por_llegar = filter_var($_POST['por_llegar'] ?? 0, FILTER_VALIDATE_INT);

    if ($disponibles !== false && $por_llegar !== false && $disponibles >= 0 && $por_llegar >= 0) {
        $stmt = $conn->prepare("UPDATE stock_anillos SET disponibles = ?, por_llegar = ? WHERE id = 1");
        if ($stmt->execute([$disponibles, $por_llegar])) {
            $mensaje = '<div class="alert success">✅ Stock actualizado correctamente.</div>';
        } else {
            $mensaje = '<div class="alert error">❌ Error al actualizar el stock.</div>';
        }
    } else {
        $mensaje = '<div class="alert error">❌ Valores inválidos. Usa números enteros positivos.</div>';
    }
}

// Obtener datos actuales
$stmt = $conn->query("SELECT * FROM stock_anillos WHERE id = 1");
$stock = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no existe el registro, crearlo con valores por defecto
if (!$stock) {
    $conn->exec("INSERT INTO stock_anillos (disponibles, por_llegar) VALUES (0, 0)");
    $stock = ['disponibles' => 0, 'por_llegar' => 0];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/jpeg" href="../img/image.jpeg">
    <link rel="stylesheet" href="../css/stock.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-title">MENÚ</div>
        <a href="estado_flujo.php">Estados de Pedidos</a>
        <a href="pagos.php">Pagos</a>
        <a href="stock.php">Stock</a>
        <a href="proveedor.php">Proveedor</a>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>

    <div class="content">
        <h1>Warmi 360 - Stock de Anillos</h1>

        <?= $mensaje ?>

        <div class="stock-display">
            <div class="stock-card">
                <h3>Disponibles</h3>
                <div class="number"><?= htmlspecialchars($stock['disponibles']) ?></div>
            </div>
            <div class="stock-card">
                <h3>Por Llegar</h3>
                <div class="number"><?= htmlspecialchars($stock['por_llegar']) ?></div>
            </div>
        </div>

        <div class="stock-form">
            <h2>Actualizar Stock</h2>
            <form method="POST">
                <label for="disponibles">Anillos Disponibles:</label>
                <input type="number" id="disponibles" name="disponibles" value="<?= htmlspecialchars($stock['disponibles']) ?>" min="0" required>

                <label for="por_llegar">Anillos Por Llegar:</label>
                <input type="number" id="por_llegar" name="por_llegar" value="<?= htmlspecialchars($stock['por_llegar']) ?>" min="0" required>

                <button type="submit">Actualizar Stock</button>
            </form>
        </div>
    </div>

</body>
</html>