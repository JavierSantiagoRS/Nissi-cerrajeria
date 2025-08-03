<?php
function obtenerVentasPendientes($conn) {
    $sql = "SELECT ventas.id, ventas.total, ventas.fecha, ventas.id_cliente, ventas.estado
            FROM ventas
            WHERE ventas.estado = 'pendiente'
            ORDER BY ventas.fecha DESC";

    $result = $conn->query($sql);
    $ventas = [];

    while ($row = $result->fetch_assoc()) {
        $ventas[] = $row;
    }

    return $ventas;
}
?>
