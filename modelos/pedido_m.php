<?php
function obtenerPedidos($conn) {
    $sql = "SELECT * FROM pedidos";
    $resultado = $conn->query($sql);
    $pedidos = [];

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $pedidos[] = $fila;
        }
    }

    return $pedidos;
}
?>
