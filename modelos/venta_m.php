<?php
function obtenerVentasPaginadas($conn, $inicio, $limite, $filtro = 'fecha_desc', $estado = '') {
    $where = " WHERE 1=1 ";
    if (!empty($estado)) {
        $estado = $conn->real_escape_string($estado);
        $where .= " AND v.estado = '$estado' ";
    }

    $order = ($filtro === 'fecha_asc') ? " ORDER BY v.fecha ASC " : " ORDER BY v.fecha DESC ";

    $sql = "SELECT v.id, v.total, v.fecha, v.id_cliente, v.estado, u.usuario AS nombre_cliente
            FROM ventas v
            INNER JOIN usuarios u ON v.id_cliente = u.id
            $where
            $order
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

function contarVentas($conn, $estado = '') {
    $where = "";
    if (!empty($estado)) {
        $estado = $conn->real_escape_string($estado);
        $where = " WHERE estado = '$estado' ";
    }
    $sql = "SELECT COUNT(*) AS total FROM ventas $where";
    $res = $conn->query($sql);
    $fila = $res->fetch_assoc();
    return $fila['total'] ?? 0;
}



function actualizarEstadoVenta($conn, $id_venta, $nuevo_estado) {
    // Obtener el estado actual de la venta
    $stmt = $conn->prepare("SELECT estado FROM ventas WHERE id = ?");
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $stmt->bind_result($estado_actual);
    $stmt->fetch();
    $stmt->close();

    // Actualizar el estado de la venta
    $stmt = $conn->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_venta);
    $resultado = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();

    if (!$resultado) {
        return $error;
    }

    // Si se confirma la venta y antes no estaba confirmada -> RESTAR stock
    if ($nuevo_estado === "confirmada" && $estado_actual !== "confirmada") {
        $sql = "SELECT id_producto, cantidad 
                FROM pedidos 
                WHERE id_venta = ? AND id_producto IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_venta);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $id_producto = $row['id_producto'];
            $cantidad = $row['cantidad'];

            $update = $conn->prepare("UPDATE inventario SET contenido = contenido - ? WHERE id = ?");
            $update->bind_param("ii", $cantidad, $id_producto);
            $update->execute();
            $update->close();
        }
        $stmt->close();
    }

    // Si estaba confirmada y pasa a cancelada -> DEVOLVER stock
    if ($estado_actual === "confirmada" && $nuevo_estado === "cancelada") {
        $sql = "SELECT id_producto, cantidad 
                FROM pedidos 
                WHERE id_venta = ? AND id_producto IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_venta);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $id_producto = $row['id_producto'];
            $cantidad = $row['cantidad'];

            $update = $conn->prepare("UPDATE inventario SET contenido = contenido + ? WHERE id = ?");
            $update->bind_param("ii", $cantidad, $id_producto);
            $update->execute();
            $update->close();
        }
        $stmt->close();
    }

    return true;
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
