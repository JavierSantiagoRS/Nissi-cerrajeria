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
    try {
        $sql = "DELETE FROM servicios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        echo "eliminado";
    } catch (PDOException $e) {
        echo "en uso";
    }
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

function cambiarEstadoServicio($conn, $id, $estado) {
    $sql = "UPDATE servicios SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
}

function obtenerServiciosPaginados($conn, $inicio, $limite) {
    $where = " WHERE 1=1 ";  // siempre verdadero
    $order = " ORDER BY id ASC "; // por defecto

    // Filtro por estado
    if (!empty($_GET['estado'])) {
        $estado = $_GET['estado'];
        $where .= " AND estado = '" . $conn->real_escape_string($estado) . "' ";
    }

    // Ordenar por precio
    if (!empty($_GET['precio'])) {
        if ($_GET['precio'] == "asc") {
            $order = " ORDER BY precio ASC ";
        } elseif ($_GET['precio'] == "desc") {
            $order = " ORDER BY precio DESC ";
        }
    }

    // Ordenar por nombre
    if (!empty($_GET['nombre'])) {
        if ($_GET['nombre'] == "asc") {
            $order = " ORDER BY nombre ASC ";
        } elseif ($_GET['nombre'] == "desc") {
            $order = " ORDER BY nombre DESC ";
        }
    }

    $sql = "SELECT * FROM servicios $where $order LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limite);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function contarServicios($conn) {
    $sql = "SELECT COUNT(*) AS total FROM servicios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['total'] ?? 0;
}

?>
