<?php

function loginUsuario($conn, $data){
    session_start();

    $usuario = $data["usuario"];
    $clave = $data["clave"];
    $stmt = $conn->prepare("SELECT id, clave, usuario, rol FROM usuarios WHERE usuario = ?");
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

            if ($row["rol"]=="admin") {
                header("Location: ../vistas/administrador/index.php");
            }else{
                header("Location: ../vistas/cliente/index.php");
            }
            
            exit();
        } else {
            echo "<script>alert('❌ Clave incorrecta, intenta nuevamente'); window.history.back();</script>";

        }
    } else {
        echo "<script>alert('⚠️ Usuario no encontrado, regístrate primero'); window.history.back();</script>";

    }
}

function registrarUsuario($conn, $data) {
    $hash = password_hash($data["clave"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (id, usuario, clave, correo, celular, rol) VALUES (NULL, ?, ?, ?, ?, 'cliente')");
    $stmt->bind_param("sssi", $data["usuario"], $hash, $data["nombre"], $data["celular"]);

    if ($stmt->execute()) {
        header("Location: ../vistas/login.php");
        exit();
    } else {
        // Puedes agregar manejo de errores aquí
        echo "Error al registrar usuario: " . $stmt->error;
    }
}


?>

