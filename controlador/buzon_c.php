<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'conexion.php';
    include_once 'modelos/buzon_m.php';

    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'celular' => $_POST['celular'] ?? '',
        'mail' => $_POST['mail'] ?? '',
        'servicio' => $_POST['servicio'] ?? '',
        'mensaje' => $_POST['mensaje'] ?? ''
    ];

    if ($data['nombre'] && $data['celular'] && $data['mensaje']) {
        crearMensaje($conn, $data);
    }

    session_start();
    $_SESSION['form_enviado'] = true;
    header("Location: index.php");
    exit;
}

// ✅ Eliminar mensaje si se recibe por GET
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    include_once '../conexion.php'; // Ajusta esta ruta si es necesario
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM contactos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../vistas/administrador/buzon_v.php");
    exit;
}
