<?php
function obtenerventas($conn) {
    $sql = "SELECT ventas.id, ventas.total, ventas.fecha, ventas.id_cliente, ventas.estado, u.usuario AS nombre_cliente
            FROM ventas
            INNER JOIN usuarios u ON ventas.id_cliente = u.id
            ORDER BY ventas.fecha DESC";

    $result = $conn->query($sql);
    $ventas = [];

    while ($row = $result->fetch_assoc()) {
        $ventas[] = $row;
    }

    return $ventas;
}



function actualizarEstadoVenta($conn, $id_venta, $nuevo_estado) {
    $stmt = $conn->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_venta);
    
    $resultado = $stmt->execute();
    $error = $stmt->error;

    $stmt->close();

    return $resultado ? true : $error;
}

function eliminarVenta($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM ventas WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }
}
