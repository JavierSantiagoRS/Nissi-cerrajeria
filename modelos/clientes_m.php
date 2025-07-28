<?php
function obtenerUsuarios($conn) {
    $sql = "SELECT id, correo, celular, rol, usuario, fecha_registro FROM usuarios";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function obtenerTotalClientes($conn) {
    $sql = "SELECT COUNT(*) AS total FROM usuarios";
    $resultado = mysqli_query($conn, $sql);
    $fila = mysqli_fetch_assoc($resultado);
    return $fila['total'] ?? 0;
}

?>