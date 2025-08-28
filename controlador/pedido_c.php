<?php
include '../conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    
    // Datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $cantidad = $_POST['cantidad'] ?? 1;
    $precio = $_POST['precio'] ?? 0;
    $tipo = $_POST['tipo'] ?? 'servicio'; // puede ser producto o servicio

    $id_cliente = $_SESSION['id_usuario'] ; // o usa el ID del cliente correcto si ya tienes login
    
    $total = $precio * $cantidad;
    $estado = 'pendiente';

    // 1. Registrar la venta
 $stmtVenta = $conn->prepare("INSERT INTO ventas (total, id_cliente, estado) VALUES (?, ?, ?)");
$stmtVenta->bind_param("iis", $total, $id_cliente, $estado);

    $stmtVenta->execute();

    if ($stmtVenta->affected_rows > 0) {
        $id_venta = $conn->insert_id;

        // 2. Registrar el pedido
        $id_servicio = null;
        $id_producto = null;

        if ($tipo === 'servicio') {
            // buscar el ID del servicio por nombre
            $stmtServ = $conn->prepare("SELECT id FROM servicios WHERE nombre = ?");
            $stmtServ->bind_param("s", $nombre);
            $stmtServ->execute();
            $resultado = $stmtServ->get_result();

            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                $id_servicio = $row['id'];
            }
            $stmtServ->close();
        }

        if ($tipo === 'producto') {
    $stmtProd = $conn->prepare("SELECT id FROM inventario WHERE titulo = ?");
    $stmtProd->bind_param("s", $nombre);
    $stmtProd->execute();
    $resultado = $stmtProd->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $id_producto = $row['id'];
    }
    $stmtProd->close();
}


  $stmtPedido = $conn->prepare("INSERT INTO pedidos (cantidad, subtotal, id_producto, id_servicio, id_venta, nombre) VALUES (?, ?, ?, ?, ?, ?)");
$stmtPedido->bind_param("iiiiis", $cantidad, $total, $id_producto, $id_servicio, $id_venta, $nombre);

        $stmtPedido->execute();
        $stmtPedido->close();
    }

    $stmtVenta->close();
    $conn->close();

    // No redireccionamos, ya que es llamado por JavaScript (fetch)
    echo json_encode(["success" => true]);
exit;

    
}
