<?php
$id_usuario = $_SESSION["id_usuario"];

$sql = "SELECT fecha_registro, usuario FROM usuarios WHERE id = $id_usuario";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $fecha = date("m/Y", strtotime($usuario["fecha_registro"]));
}

