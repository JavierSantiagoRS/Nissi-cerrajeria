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
    eliminarservicio($conn, $id);
}

header('Location: ../vistas/administrador/servicio_v.php');
exit();
