<?php
include '../conexion.php';
include '../modelos/venta_m.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $id_venta = $data['id'];
    $nuevo_estado = $data['estado']; // ✅ Usar el estado recibido, no "pendiente" fijo

    $resultado = actualizarEstadoVenta($conn, $id_venta, $nuevo_estado);

    if ($resultado === true) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $resultado]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);
    $id_venta = $data['id'];

    $resultado = eliminarVenta($conn, $id_venta);

    if ($resultado === true) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $resultado]);
    }
}

