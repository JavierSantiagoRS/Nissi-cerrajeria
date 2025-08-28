<?php
function obtenerinventario($conn) {
    $sql = "SELECT * FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function obtenerInventarioPaginado($conn, $inicio, $limite) {
    $where = " WHERE 1=1 ";
    $order = " ORDER BY id ASC ";

    // Filtro por estado
    if (!empty($_GET['estado'])) {
        $estado = $conn->real_escape_string($_GET['estado']);
        $where .= " AND estado = '$estado' ";
    }

    // Filtro por stock
    if (!empty($_GET['stock'])) {
        if ($_GET['stock'] == "bajo") {
            $where .= " AND contenido < 10 "; // ajusta el número límite
        } elseif ($_GET['stock'] == "alto") {
            $where .= " AND contenido >= 10 ";
        }
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
            $order = " ORDER BY titulo ASC ";
        } elseif ($_GET['nombre'] == "desc") {
            $order = " ORDER BY titulo DESC ";
        }
    }

    $sql = "SELECT * FROM inventario $where $order LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $inicio, $limite);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function contarInventario($conn) {
    $sql = "SELECT COUNT(*) AS total FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['total'] ?? 0;
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
    try {
        $sql = "DELETE FROM inventario WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "eliminado";
    } catch (Exception $e) {
        echo "en uso";
    }
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
    $sql = "SELECT SUM(precio*contenido) AS suma_precios FROM inventario";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $fila = $result->fetch_assoc();
    return $fila['suma_precios'] ?? 0;
}

function cambiarEstadoInventario($conn, $id, $estado) {
    $sql = "UPDATE inventario SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
}
?>
