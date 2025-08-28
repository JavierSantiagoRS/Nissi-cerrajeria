<?php
function obtenerVentasCliente($conn, $id_cliente) {
    $sql = "
        SELECT 
            v.id AS id_venta,
            v.total,
            v.fecha,
            v.estado,  
            COALESCE(i.titulo, s.nombre) AS nombre_item,
            p.cantidad,
            p.subtotal,
            CASE 
                WHEN i.id IS NOT NULL THEN 'producto'
                WHEN s.id IS NOT NULL THEN 'servicio'
                ELSE 'otro'
            END AS tipo
        FROM ventas v
        INNER JOIN pedidos p ON v.id = p.id_venta
        LEFT JOIN inventario i ON p.id_producto = i.id
        LEFT JOIN servicios s ON p.id_servicio = s.id
        WHERE v.id_cliente = ?
        ORDER BY v.fecha DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    $ventas = [];
    while ($row = $result->fetch_assoc()) {
        $ventaId = $row['id_venta'];

        if (!isset($ventas[$ventaId])) {
            $ventas[$ventaId] = [
                'id_venta' => $row['id_venta'],
                'fecha'    => $row['fecha'],
                'total'    => $row['total'],
                'estado'   => $row['estado'],
                'pedidos'  => []
            ];
        }

        $ventas[$ventaId]['pedidos'][] = [
            'nombre_item' => $row['nombre_item'],
            'cantidad'    => $row['cantidad'],
            'subtotal'    => $row['subtotal'],
            'tipo'        => $row['tipo']
        ];
    }

    $stmt->close();
    return array_values($ventas);
}
