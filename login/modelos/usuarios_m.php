<?php

function loginUsuario($conn, $data){
    session_start();

    $usuario = $data["usuario"];
    $clave = $data["clave"];

    $stmt = $conn->prepare("SELECT id, clave, nombre, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();

        if (password_verify($clave, $row["clave"])) {
            $_SESSION["id_usuario"] = $row["id"];
            $_SESSION["usuario"] = $usuario;
            $_SESSION["rol"] = $row["rol"];
            $_SESSION["nombre"] = $row["nombre"];

            header("Location: ../vistas/notas_v.php");
            exit();
        } else {
            echo "Clave incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

function registrarUsuario($conn, $data){
    $hash = password_hash($data["clave"], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios VALUES (NULL, '".$data["usuario"]."', '".$hash."', '".$data["nombre"]."', ".$data["celular"].", 'cliente')");
    // $stmt->bind_param( $data["usuario"], $hash, $data["nombre"], $data["celular"]);
    $stmt->execute();

    header("Location: ../vistas/login.php");
    exit();
}
?>

