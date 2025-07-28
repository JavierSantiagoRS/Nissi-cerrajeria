<?php
function obtenerinventario($conn) {
    $sql = "SELECT * FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function crearinventario($conn, $data) {
    $sql = "INSERT INTO inventario (imagen, titulo, contenido, precio, descripcion) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", 
        $data['imagen'], 
        $data['titulo'], 
        $data['contenido'], 
        $data['precio'], 
        $data['descripcion']
    );
    $stmt->execute();
}

function actualizarinventario($conn, $data) {
    $sql = "UPDATE inventario SET imagen = ?, titulo = ?, contenido = ?, precio = ?, descripcion = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisi", 
        $data['imagen'], 
        $data['titulo'], 
        $data['contenido'], 
        $data['precio'], 
        $data['descripcion'], 
        $data['id']
    );
    $stmt->execute();
}
function eliminarinventario($conn, $id) {
    // Buscar la imagen asociada
    $stmt = $conn->prepare("SELECT imagen FROM inventario WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    // Eliminar imagen si existe
    if ($producto && !empty($producto['imagen'])) {
        $rutaImagen = __DIR__ . '/../assets/' . $producto['imagen'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    // Eliminar producto de la base de datos
    $stmt = $conn->prepare("DELETE FROM inventario WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}



function obtenerTotalinventario($conn) {
    $sql = "SELECT SUM(contenido) AS total FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['total'] ?? 0;
}

function obtenerSumaPrecios($conn) {
    $sql = "SELECT SUM(precio) AS suma_precios FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['suma_precios'] ?? 0;
}


?>
