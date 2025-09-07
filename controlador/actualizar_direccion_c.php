<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include '../conexion.php';
include '../modelos/indexcliente_m.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION["id_usuario"];
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $codigo_postal = $_POST['codigo_postal'];
    $departamento = $_POST['departamento'];

    if(actualizarDireccion($conn, $id_usuario, $calle, $ciudad, $codigo_postal, $departamento)) {
        header("Location: ../vistas/cliente/info.php"); // redirige al perfil
        exit();
    } else {
        echo "Error al actualizar la dirección.";
    }
}
?>
