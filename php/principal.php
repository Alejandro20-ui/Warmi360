<?php
session_start();

if (!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] !== true) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/jpeg" href="../img/image.jpeg">
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
        <h1>Warmi 360 - Seccion Ventas</h1>
        <p>Aquí irá el contenido de la página.</p>
    </div>

</body>
</html>
