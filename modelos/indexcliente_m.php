<?php

$id_usuario = $_SESSION["id_usuario"];

// Traer datos del usuario
$sql = "SELECT fecha_registro, usuario, correo, celular, calle, ciudad, codigo_postal, departamento 
        FROM usuarios WHERE id = $id_usuario";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $fecha = date("m/Y", strtotime($usuario["fecha_registro"]));
}

// Función para actualizar dirección
function actualizarDireccion($conn, $id_usuario, $calle, $ciudad, $codigo_postal, $departamento) {
    $stmt = $conn->prepare("UPDATE usuarios SET calle=?, ciudad=?, codigo_postal=?, departamento=? WHERE id=?");
    $stmt->bind_param("ssssi", $calle, $ciudad, $codigo_postal, $departamento, $id_usuario);
    return $stmt->execute();
}
?>
