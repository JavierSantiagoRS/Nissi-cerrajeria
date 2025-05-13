<?php
include '../conexion.php';
include '../modelos/notas_m.php';
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
if ($accion == 'crear') {
    crearNota($conn, $_POST);
} elseif ($accion == 'actualizar') {
    actualizarNota($conn, $_POST);
} elseif ($accion == 'eliminar') {
    eliminarNota($conn, $_GET['id']);
}
header('Location: ../vistas/notas_v.php');
?>