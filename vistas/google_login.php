<?php
session_start();
require '../conexion.php'; // Ajusta la ruta según tu estructura

header("Content-Type: application/json");

// Recibir token desde JS
$input = json_decode(file_get_contents("php://input"), true);
if (!isset($input['token'])) {
    echo json_encode(["success" => false, "error" => "Token no recibido"]);
    exit;
}

$token = $input['token'];

// Decodificar el JWT (token de Google)
$parts = explode(".", $token);
$payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

// Datos del usuario de Google
$correo = $payload['email'] ?? '';
$usuario = $payload['given_name'] ?? 'usuario';
$nombre_completo = $payload['name'] ?? '';
$clave = password_hash(uniqid(), PASSWORD_DEFAULT); // Contraseña aleatoria

if (!$correo) {
    echo json_encode(["success" => false, "error" => "Correo no disponible"]);
    exit;
}

// Buscar si ya existe en la BD
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario ya existe
    $user = $result->fetch_assoc();
    $_SESSION["id_usuario"] = $user["id"];
    $_SESSION["usuario"] = $user["usuario"];
    $_SESSION["rol"] = $user["rol"];
} else {
    // Insertar nuevo usuario con rol cliente por defecto
    $rol = "cliente";
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, clave, correo, rol, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $usuario, $clave, $correo, $rol);
    $stmt->execute();

    $id_usuario = $stmt->insert_id;
    $_SESSION["id_usuario"] = $id_usuario;
    $_SESSION["usuario"] = $usuario;
    $_SESSION["rol"] = $rol;
}

// Redirigir según rol
if ($_SESSION["rol"] === "admin") {
    echo json_encode(["success" => true, "redirect" => "administrador/index.php"]);
} else {
    echo json_encode(["success" => true, "redirect" => "cliente/index.php"]);
}
