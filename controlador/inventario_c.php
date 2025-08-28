<?php
include '../conexion.php';
include '../modelos/inventario_m.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

function subirImagen($inputName = 'imagen') {
    $carpetaDestino = '../assets/uploads/';

    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $nombreTmp = $_FILES[$inputName]['tmp_name'];
    $nombreOriginal = basename($_FILES[$inputName]['name']);
    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    $extValidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extension, $extValidas)) {
        return null;
    }

    $nuevoNombre = uniqid('img_') . '.' . $extension;
    $rutaDestino = $carpetaDestino . $nuevoNombre;

    if (move_uploaded_file($nombreTmp, $rutaDestino)) {
        return 'uploads/' . $nuevoNombre; // Ruta relativa para DB
    } else {
        return null;
    }
}

if ($accion == 'crear') {
    $rutaImagen = subirImagen('imagen');

    $data = [
        'imagen' => $rutaImagen ?? '',
        'titulo' => $_POST['titulo'] ?? '',
        'contenido' => $_POST['contenido'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'descripcion' => $_POST['descripcion'] ?? '',
    ];

    crearinventario($conn, $data);

} elseif ($accion == 'actualizar') {
    $id = $_POST['id'] ?? 0;

    $result = mysqli_query($conn, "SELECT imagen FROM inventario WHERE id = $id");
    $inventarioActual = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;

    $rutaImagen = subirImagen('imagen');

    if ($rutaImagen === null) {
        $rutaImagen = $inventarioActual['imagen'] ?? '';
    } else {
        // Eliminar imagen anterior si existe
        if (!empty($inventarioActual['imagen']) && file_exists('../assets/' . $inventarioActual['imagen'])) {
            unlink('../assets/' . $inventarioActual['imagen']);
        }
    }

    $data = [
        'id' => $id,
        'imagen' => $rutaImagen,
        'titulo' => $_POST['titulo'] ?? '',
        'contenido' => $_POST['contenido'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'descripcion' => $_POST['descripcion'] ?? '',
    ];

    actualizarinventario($conn, $data);

} elseif ($accion == 'eliminar') {
    $id = $_GET['id'] ?? 0;
    include_once '../modelos/inventario_m.php';
    eliminarinventario($conn, $id); // Esta función debe hacer echo "eliminado" o echo "en uso"
    exit;
}

elseif ($accion == 'cambiar_estado') {
    $id = $_GET['id'] ?? 0;
    $estado = $_GET['estado'] ?? 'activo';
    cambiarEstadoInventario($conn, $id, $estado);
    header('Location: ../vistas/administrador/inventario_v.php');
    exit;
}


header('Location: ../vistas/administrador/inventario_v.php');

exit();
