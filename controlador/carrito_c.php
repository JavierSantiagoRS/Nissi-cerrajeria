<?php
session_start();
header('Content-Type: application/json');
include "../conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    echo json_encode([
        "status" => "error",
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

// Leer JSON recibido
$input = json_decode(file_get_contents("php://input"), true);

// Validaciones mínimas
if (!isset($input["total"]) || !is_numeric($input["total"])) {
    echo json_encode(["status" => "error", "message" => "Total inválido"]);
    exit;
}

$total = (int) $input["total"];
$id_cliente = $_SESSION["id_usuario"];
$fecha = date("Y-m-d H:i:s");

// 1. Insertar venta
$sqlVenta = "INSERT INTO ventas (id_cliente, total, estado) VALUES (?, ?, 'pendiente')";
$stmtVenta = $conn->prepare($sqlVenta);
$stmtVenta->bind_param("ii", $id_cliente, $total);


if (!$stmtVenta->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al registrar venta"]);
    exit;
}

$id_venta = $stmtVenta->insert_id;

// 2. Insertar productos
if (!empty($input["productos"])) {
    foreach ($input["productos"] as $p) {
        if (!isset($p["id"], $p["cantidad"], $p["subtotal"])) continue;

        $id_producto = (int) $p["id"];
        $cantidad = (int) $p["cantidad"];
        $subtotal = (int) $p["subtotal"];

        $sqlPedido = "INSERT INTO pedidos (id_producto, cantidad, subtotal, id_venta) VALUES (?, ?, ?, ?)";
        $stmtP = $conn->prepare($sqlPedido);
        $stmtP->bind_param("iiii", $id_producto, $cantidad, $subtotal, $id_venta);
        $stmtP->execute();
    }
}

// 3. Insertar servicios
if (!empty($input["servicios"])) {
    foreach ($input["servicios"] as $s) {
        if (!isset($s["id"], $s["cantidad"], $s["subtotal"])) continue;

        $id_servicio = (int) $s["id"];
        $cantidad = (int) $s["cantidad"];
        $subtotal = (int) $s["subtotal"];

        $sqlPedido = "INSERT INTO pedidos (id_servicio, cantidad, subtotal, id_venta) VALUES (?, ?, ?, ?)";
        $stmtS = $conn->prepare($sqlPedido);
        $stmtS->bind_param("iiii", $id_servicio, $cantidad, $subtotal, $id_venta);
        $stmtS->execute();
    }
}

echo json_encode([
    "status" => "ok",
    "message" => "Compra registrada",
    "id_venta" => $id_venta
]);
