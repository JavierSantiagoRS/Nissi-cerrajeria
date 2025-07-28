<?php
function obtenerservicio($conn) {
    $sql = "SELECT * FROM servicios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function crearservicio($conn, $data) {
    $sql = "INSERT INTO servicios (nombre, precio, imagen, descripcion) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", 
        $data['nombre'],
        $data['precio'] ,
        $data['imagen'],
        $data['descripcion']  
    );
    $stmt->execute();
}


function actualizarservicio($conn, $data) {
    $sql = "UPDATE servicios SET imagen = ?, nombre = ?, precio = ?, descripcion = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", 
        $data['imagen'],
        $data['nombre'],
        $data['precio'] ,
        $data['descripcion'],  
        $data['id']            // i = integer
    );
    $stmt->execute();
}

function eliminarservicio($conn, $id) {
    // Buscar la imagen asociada
    $stmt = $conn->prepare("SELECT imagen FROM servicios WHERE id = ?");
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
    $stmt = $conn->prepare("DELETE FROM servicios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}



function obtenerTotalservicio($conn) {
    $sql = "SELECT COUNT(*) AS total FROM servicios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['total'] ?? 0;
}


function obtenerSumaPrecios($conn) {
    $sql = "SELECT SUM(precio) AS suma_precios FROM servicios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['suma_precios'] ?? 0;
}


?>
