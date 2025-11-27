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

$stmt = $conn->query("
    SELECT p.id, p.nombre_completo, p.metodo_pago, p.estado, f.estado AS estado_flujo, f.comentario
    FROM pedidos p
    LEFT JOIN flujo_pedido f ON f.id_pedido = p.id
    WHERE p.estado = 'aprobado'
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Flujo de Pedidos</title>
<link rel="stylesheet" href="../css/estilo.css">
<link rel="icon" type="image/jpeg" href="../img/image.jpeg">
<link rel="stylesheet" href="../css/estado_flujo.css">
</head>

<body>

<div class="sidebar">
    <div class="sidebar-title">MENÚ</div>
    <a href="estado_flujo.php">Flujo de Pedidos</a>
    <a href="pagos.php">Pagos</a>
    <a href="stock.php">Stock</a>
    <a href="proveedor.php">Proveedor</a>
    <div class="logout"><a href="logout.php">Cerrar Sesión</a></div>
</div>

<div class="content">
    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
<h1>Flujo de pedidos aprobados</h1>

<?php foreach ($pedidos as $p): ?>

    <h3>Pedido #<?= $p['id'] ?> - <?= $p['nombre_completo'] ?></h3>

    <div class="flujo-container">

        <!-- Confirmado -->
        <div class="step <?= ($p['estado_flujo']=='confirmado')?'activo':'' ?>" 
             onclick="abrirModal(<?= $p['id'] ?>,'confirmado','<?= $p['metodo_pago'] ?>')">
            Confirmado
        </div>

        <!-- En camino -->
        <div class="step <?= ($p['estado_flujo']=='en_camino')?'activo':'' ?>" 
             onclick="abrirModal(<?= $p['id'] ?>,'en_camino','')">
            En camino
        </div>

        <!-- Entregado -->
        <div class="step <?= ($p['estado_flujo']=='entregado')?'activo':'' ?>" 
             onclick="abrirModal(<?= $p['id'] ?>,'entregado','')">
            Entregado
        </div>

    </div>

<?php endforeach; ?>
</div>

<!-- MODAL -->
<div id="modal">
    <div class="modal-box">
        
        <div class="btn-x" onclick="cerrarModal()">X</div>

        <h3 id="modal-titulo"></h3>

        <form method="POST" action="actualizar_flujo.php">
            <input type="hidden" name="id_pedido" id="id_pedido">
            <input type="hidden" name="estado" id="estado">
            <input type="hidden" name="aprobado" id="aprobado">

            <p id="descripcion_pago"></p>

            <div id="botonesAprobacion" style="margin-bottom:15px; display:none;">
                <button type="button" class="btn-estado btn-aprobado" onclick="setAprobado('si')">Aprobado</button>
                <button type="button" class="btn-estado btn-negado" onclick="setAprobado('no')">Negado</button>
            </div>

            <textarea name="comentario" rows="4" placeholder="Comentario..."></textarea>
            <br><br>

            <button>Guardar</button>
        </form>

    </div>
</div>
<script src="../js/flujo.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
