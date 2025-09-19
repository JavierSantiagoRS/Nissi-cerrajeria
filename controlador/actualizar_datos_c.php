<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

include '../conexion.php';

$id_usuario = $_SESSION["id_usuario"];

// Si viene celular, actualizar solo celular
if (isset($_POST['celular'])) {
    $celular = trim($_POST['celular']);

    $stmt = $conn->prepare("UPDATE usuarios SET celular = ? WHERE id = ?");
    $stmt->bind_param("si", $celular, $id_usuario);

    if ($stmt->execute()) {
        header("Location: ../vistas/cliente/info.php");
        exit();
    } else {
        echo "Error al actualizar el teléfono.";
    }
}

// Si viene dirección, actualizar dirección
if (isset($_POST['calle'], $_POST['ciudad'], $_POST['codigo_postal'], $_POST['departamento'])) {
    $calle = $_POST['calle'];
    $ciudad = $_POST['ciudad'];
    $codigo_postal = $_POST['codigo_postal'];
    $departamento = $_POST['departamento'];

    $stmt = $conn->prepare("UPDATE usuarios SET calle = ?, ciudad = ?, codigo_postal = ?, departamento = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $calle, $ciudad, $codigo_postal, $departamento, $id_usuario);

    if ($stmt->execute()) {
        header("Location: ../vistas/cliente/info.php");
        exit();
    } else {
        echo "Error al actualizar la dirección.";
    }
}
?>
