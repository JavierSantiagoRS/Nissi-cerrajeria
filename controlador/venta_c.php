<?php
session_start();
$json = file_get_contents("php://input");
$data = json_decode($json, true);

include "../conexion.php";

$total = $data['total'];
$id_cliente = $_SESSION["id_usuario"];

$stmt = $conn->prepare("INSERT INTO ventas (total, id_cliente) VALUES (?, ?)");
$stmt->bind_param("ii", $total, $id_cliente);  // Usa "di" si total es decimal
$stmt->execute();

// Obtener el ID de la venta insertada
$id_venta = $conn->insert_id;
if ($data['servicios']!=null) {
    foreach ($data['servicios'] as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO pedidos (cantidad, subtotal, id_servicio, id_venta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $value['cantidad'], $value['subtotal'], $value['id'],$id_venta);  // Usa "di" si total es decimal
        $stmt->execute();
    }
}


if ($data['productos'] !=null) {
    foreach ($data['productos'] as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO pedidos (cantidad, subtotal, id_producto, id_venta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $value['cantidad'], $value['subtotal'], $value['id'],$id_venta);  // Usa "di" si total es decimal
        $stmt->execute();
    }
}


$stmt->close();

// Devuelve el ID como respuesta JSON
echo json_encode([
    "status" => "ok",
    "id_venta" => $id_venta
]);


