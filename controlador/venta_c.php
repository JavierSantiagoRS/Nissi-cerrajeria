<?php
include '../conexion.php';
include '../modelos/venta_m.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    // Si viene "estado", significa que es actualización
    if (isset($data['estado'])) {
        $id_venta = intval($data['id']);
        $nuevo_estado = $data['estado'];

        $resultado = actualizarEstadoVenta($conn, $id_venta, $nuevo_estado);

        if ($resultado === true) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $resultado]);
        }
    }
    // Si viene "action=delete", significa que es eliminación
    elseif (isset($data['action']) && $data['action'] === 'delete') {
        $id_venta = intval($data['id']);

        $resultado = eliminarVenta($conn, $id_venta);

        if ($resultado === true) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $resultado]);
        }
    }
    else {
        echo json_encode(["success" => false, "error" => "Solicitud inválida"]);
    }
}
