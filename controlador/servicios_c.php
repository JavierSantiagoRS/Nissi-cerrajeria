<?php
include '../conexion.php';
include '../modelos/servicios_m.php';

$accion = $_GET['accion'] ?? '';

if ($accion == 'crear') {
    $data = [
        'imagen' => $_POST['imagen'] ?? '',  // Solo clases de ícono
        'nombre' => $_POST['nombre'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'descripcion' => $_POST['descripcion'] ?? ''
    ];

    crearservicio($conn, $data);

} elseif ($accion == 'actualizar') {
    $data = [
        'id' => $_POST['id'] ?? 0,
        'imagen' => $_POST['imagen'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'descripcion' => $_POST['descripcion'] ?? ''
    ];

    actualizarservicio($conn, $data);

} elseif ($accion == 'eliminar') {
    $id = $_GET['id'] ?? 0;
    include_once '../modelos/servicios_m.php';
    eliminarservicio($conn, $id); // Esta función debe hacer echo "eliminado" o echo "en uso"
    exit;
}

elseif ($accion == 'cambiar_estado') {
    $id = $_GET['id'] ?? 0;
    $estado = $_GET['estado'] ?? 'activo';
    cambiarEstadoServicio($conn, $id, $estado);
    header('Location: ../vistas/administrador/servicio_v.php');
    exit;
}

header('Location: ../vistas/administrador/servicio_v.php');
exit();
