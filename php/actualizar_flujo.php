<?php
require __DIR__ . '/../backend/conexion.php';

$id = $_POST['id_pedido'];
$estado = $_POST['estado'];
$comentario = $_POST['comentario'];
$aprobado = $_POST['aprobado'];

// Si está vacío (confirmado), dejar null
$aprobado = ($aprobado == "") ? null : $aprobado;

$stmt = $conn->prepare("
    UPDATE flujo_pedido 
    SET estado = ?, comentario = ?, aprobado = ?
    WHERE id_pedido = ?
");

$stmt->execute([$estado, $comentario, $aprobado, $id]);

header("Location: estado_flujo.php");
exit;
