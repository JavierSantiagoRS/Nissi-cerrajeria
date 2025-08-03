<?php
function obtenerVentasPendientes($conn) {
    $sql = "SELECT * FROM ventas WHERE estado = 'pendiente'";
    $resultado = $conn->query($sql);
    $ventas = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $ventas[] = $fila;
        }
    }

    return $ventas;
}
?>
