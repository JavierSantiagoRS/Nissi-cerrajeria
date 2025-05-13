<?php
function obtenerNotas($conn) {
    $result = mysqli_query($conn, "SELECT * FROM notas");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function crearNota($conn, $data) {
    $sql = "INSERT INTO notas (titulo, contenido) VALUES ('{$data['titulo']}', '{$data['contenido']}')";
    mysqli_query($conn, $sql);
}
function actualizarNota($conn, $data) {
    $sql = "UPDATE notas SET titulo='{$data['titulo']}', contenido='{$data['contenido']}' WHERE id={$data['id']}";
    mysqli_query($conn, $sql);
}
function eliminarNota($conn, $id) {
    mysqli_query($conn, "DELETE FROM notas WHERE id=$id");
}
?>