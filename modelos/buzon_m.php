<?php
function crearMensaje($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO contactos (nombre, celular, mail, servicio, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $data['nombre'], $data['celular'], $data['mail'], $data['servicio'], $data['mensaje']);
    $stmt->execute();
    $stmt->close();
}
