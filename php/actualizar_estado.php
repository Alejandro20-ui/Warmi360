<?php
require __DIR__ . '/../backend/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['estado'])) {
    die("Datos faltantes");
}

$id = $_GET['id'];
$estado = $_GET['estado'];

// Validar estados válidos
$permitidos = ['aprobado', 'negado'];
if (!in_array($estado, $permitidos)) {
    die("Estado inválido");
}

// Actualizar en la base de datos
$stmt = $conn->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':id', $id);
if ($estado == 'aprobado') {
    // Crear flujo inicial
    $crearFlujo = $conn->prepare("INSERT INTO flujo_pedido (id_pedido) VALUES (?)");
    $crearFlujo->execute([$id]);
}


if ($stmt->execute()) {
    // Mostrar alerta y luego redirigir (por ejemplo, a la página anterior)
    echo '<script>
        alert("Cambio realizado");
        window.location.href = "pagos.php";
    </script>';
    exit;
} else {
    echo '<script>
        alert("Error al actualizar.");
        window.history.back();
    </script>';
}
?>
