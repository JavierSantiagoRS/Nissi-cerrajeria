<?php
function obtenerPedidos($conn) {
    $sql = "SELECT 
                p.*, 
                v.fecha,
                u.usuario AS cliente,
                CASE 
                    WHEN p.id_producto IS NOT NULL THEN i.titulo
                    WHEN p.id_servicio IS NOT NULL THEN s.nombre
                    ELSE 'Desconocido'
                END AS nombre_item,
                CASE 
                    WHEN p.id_producto IS NOT NULL THEN 'producto'
                    WHEN p.id_servicio IS NOT NULL THEN 'servicio'
                    ELSE 'otro'
                END AS tipo
            FROM pedidos p
            INNER JOIN ventas v ON p.id_venta = v.id
            INNER JOIN usuarios u ON v.id_cliente = u.id
            LEFT JOIN inventario i ON p.id_producto = i.id
            LEFT JOIN servicios s ON p.id_servicio = s.id
            ORDER BY p.id DESC";

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
