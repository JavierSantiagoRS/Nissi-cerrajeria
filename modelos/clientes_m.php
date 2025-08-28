<?php
function obtenerUsuariosPaginados($conn, $inicio, $limite) {
    $sql = "SELECT id, correo, celular, rol, usuario, fecha_registro
            FROM usuarios
            WHERE rol = 'cliente'
            ORDER BY id DESC
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limite);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}

function contarClientes($conn) {
    $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'cliente'";
    $res = $conn->query($sql);
    $fila = $res->fetch_assoc();
    return $fila['total'] ?? 0;
}
?>
