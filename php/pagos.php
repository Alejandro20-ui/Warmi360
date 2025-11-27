<?php
session_start();
if (!isset($_SESSION['superadmin']) || $_SESSION['superadmin'] !== true) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Conexión a la base de datos
require __DIR__ . '/../backend/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estados de Pedidos</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" type="image/jpeg" href="../img/image.jpeg">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-title">MENÚ</div>
        
        <div class="sidebar-nav">
            <a href="estado_flujo.php">Estados de Pedidos</a>
            <a href="pagos.php">Pagos</a>
            <a href="stock.php">Stock</a>
            <a href="proveedor.php">Proveedor</a>
        </div>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>

<div class="content">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
    <h1>Lista de Pedidos</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Cantidad</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Método</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php
        $stmt = $conn->query("SELECT * FROM pedidos ORDER BY fecha DESC");
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as $row) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['cantidad']}</td>
                <td>{$row['nombre_completo']}</td>
                <td>{$row['direccion']}</td>
                <td>{$row['metodo_pago']}</td>
                <td><strong>{$row['estado']}</strong></td>

                <td>
                    <a href='../php/actualizar_estado.php?id={$row['id']}&estado=aprobado'>Aprobar</a> |
                    <a href='../php/actualizar_estado.php?id={$row['id']}&estado=negado'>Negar</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
