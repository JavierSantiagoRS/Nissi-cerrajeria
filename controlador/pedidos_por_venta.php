<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id_venta = $data['id_venta'] ?? null;

if (!$id_venta) {
    echo json_encode(['success' => false, 'error' => 'ID de venta no enviado']);
    exit;
}

$sql = "SELECT 
            p.id,
            COALESCE(pr.titulo, s.nombre) AS nombre_item,
            CASE
                WHEN p.id_producto IS NOT NULL THEN 'Producto'
                WHEN p.id_servicio IS NOT NULL THEN 'Servicio'
                ELSE 'Desconocido'
            END AS tipo,
            p.cantidad,
            p.subtotal
        FROM pedidos p
        LEFT JOIN inventario pr ON p.id_producto = pr.id
        LEFT JOIN servicios s ON p.id_servicio = s.id
        WHERE p.id_venta = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
    $pedidos[] = $row;
}

echo json_encode(['success' => true, 'pedidos' => $pedidos]);
