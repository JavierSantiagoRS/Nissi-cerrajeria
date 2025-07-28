<?php
include '../conexion.php';
include '../modelos/pedido_m.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'crear') {
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $precio = isset($_POST['precio']) ? intval($_POST['precio']) : 0;
    $codigo = isset($_POST['codigo_pedido']) ? $_POST['codigo_pedido'] : '';

    // Validación básica
    if ($cantidad <= 0 || empty($nombre) || ($tipo !== 'producto' && $tipo !== 'servicio') || $precio <= 0) {
        echo "Datos inválidos o incompletos.";
        exit;
    }

    // Generar un código de pedido único si no se proporciona
    if (empty($codigo)) {
        $codigo = uniqid('PD-'); // ejemplo: PD-64cfa0ebae10a
    }

    $subtotal = $cantidad * $precio;

    // Preparar la consulta con tipo string para codigo_pedido
    $stmt = $conn->prepare("INSERT INTO pedidos (cantidad, nombre, subtotal, tipo, codigo_pedido) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $cantidad, $nombre, $subtotal, $tipo, $codigo);

    if ($stmt->execute()) {
        // También puedes obtener el ID insertado, si lo necesitas
        $id_pedido = $conn->insert_id;
        echo "Pedido registrado correctamente.";
    } else {
        echo "Error al registrar el pedido: " . $stmt->error;
    }


    
    $stmt->close();
    $conn->close();
}else {
    echo "Acceso no permitido.";
}

