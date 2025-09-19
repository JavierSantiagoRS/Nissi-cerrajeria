<?php
session_start();

// Si ya está logeado, redirige
if (isset($_SESSION['id_usuario'])) {
    header("Location: cliente/index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NISSI Cerrajería - Iniciar sesión</title>
             <link rel="../assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
    <link rel="stylesheet" href="assets/css/iniciar/iniciar.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  </head>
  <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

/* Animated background elements */
body::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(241, 196, 15, 0.1) 0%, transparent 50%);
    animation: rotate 20s linear infinite;
    z-index: 0;
}

body::after {
    content: '';
    position: absolute;
    top: 20%;
    right: 10%;
    width: 100px;
    height: 100px;
    background: rgba(241, 196, 15, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
    z-index: 0;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.form-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 25px;
    padding: 50px 40px;
    width: 100%;
    max-width: 450px;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logo/Icon effect */
.form-box::before {
    content: '\f084';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: #f1c40f;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 10px 25px rgba(42, 82, 152, 0.3);
    animation: keyRotate 3s ease-in-out infinite;
}

@keyframes keyRotate {
    0%, 100% { transform: translateX(-50%) rotate(0deg); }
    50% { transform: translateX(-50%) rotate(-15deg); }
}

.form {
    width: 100%;
}

.title {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #2a5298;
    text-align: center;
    margin-bottom: 10px;
    margin-top: 20px;
    position: relative;
}

.title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #f1c40f 0%, #f39c12 100%);
    border-radius: 2px;
}

.subtitle {
    display: block;
    font-size: 1rem;
    color: #666;
    text-align: center;
    margin-bottom: 40px;
    font-weight: 400;
}

.subtitle::before {
    content: 'NISSI CERRAJERÍA - Sistema de Acceso';
    color: #888;
    font-size: 0.9rem;
}

.form-container {
    margin-bottom: 30px;
}

.input {
    width: 100%;
    padding: 18px 20px;
    margin-bottom: 20px;
    border: 2px solid #e1e8ed;
    border-radius: 15px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    position: relative;
    outline: none;
}

.input:focus {
    border-color: #2a5298;
    background: white;
    box-shadow: 
        0 0 0 4px rgba(42, 82, 152, 0.1),
        0 5px 15px rgba(42, 82, 152, 0.1);
    transform: translateY(-2px);
}

.input:valid {
    border-color: #27ae60;
}

.input::placeholder {
    color: #999;
    font-weight: 400;
}

/* Input icons */
.input[name="usuario"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

.input[name="clave"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

.btn {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-succes {
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(42, 82, 152, 0.3);
}

.btn-succes:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(42, 82, 152, 0.4);
}

.btn-succes:active {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(42, 82, 152, 0.3);
}

/* Button ripple effect */
.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn:active::before {
    width: 300px;
    height: 300px;
}

.form-section {
    text-align: center;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid #e1e8ed;
}

.form-section p {
    color: #666;
    font-size: 15px;
    margin: 0;
}

.form-section a {
    color: #2a5298;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.form-section a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #f1c40f 0%, #f39c12 100%);
    transition: width 0.3s ease;
}

.form-section a:hover {
    color: #1e3c72;
}

.form-section a:hover::after {
    width: 100%;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-box {
        margin: 20px;
        padding: 40px 30px;
        max-width: none;
    }
    
    .title {
        font-size: 1.8rem;
    }
    
    .input {
        padding: 16px 18px;
        font-size: 15px;
    }
    
    .btn {
        padding: 16px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .form-box {
        margin: 15px;
        padding: 35px 25px;
        border-radius: 20px;
    }
    
    .title {
        font-size: 1.6rem;
    }
    
    .input {
        padding: 15px 16px;
        margin-bottom: 18px;
        border-radius: 12px;
    }
    
    .btn {
        padding: 15px;
        border-radius: 12px;
    }
    
    .input[name="usuario"],
    .input[name="clave"] {
        background-size: 18px;
        padding-right: 45px;
    }
}

/* Loading animation for button */
.btn.loading {
    pointer-events: none;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Focus indicators for accessibility */
.input:focus,
.btn:focus {
    outline: 2px solid #f1c40f;
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .form-box {
        border: 2px solid #000;
        background: #fff;
    }
    
    .input {
        border: 2px solid #000;
    }
    
    .btn-succes {
        background: #000;
        color: #fff;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
  </style>
  <body>
     
    <div class="form-box">
        <a href="../index.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
      <form action="../controlador/usuarios_c.php?accion=ingresar" method="POST" class="form">
        <span class="title">Acceder a cuenta</span>
        <span class="subtitle"></span>
        <div class="form-container">
          <input type="text" name="usuario" class="input" placeholder="Nombre Usuario" required/>
          <input type="password" name="clave" class="input" placeholder="Contraseña" required/>
        </div>
        <button type="submit" class="btn btn-succes">Acceder</button>
      </form>
<!-- Botón Google Sign-In elegante centrado y un poco a la derecha -->
<div style="display: flex; justify-content: center; margin-top: 20px;">
  <div style="margin-left: 20px;"> <!-- Ajusta este valor para moverlo más a la derecha -->
    <button id="google-btn" class="button">
      <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262">
        <path fill="#4285F4" d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"></path>
        <path fill="#34A853" d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"></path>
        <path fill="#FBBC05" d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"></path>
        <path fill="#EB4335" d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"></path>
      </svg>
      Acceder con google
    </button>
  </div>
</div>

<style>
.button {
  max-width: 320px;
  display: flex;
  padding: 0.5rem 1.4rem;
  font-size: 0.875rem;
  line-height: 1.25rem;
  font-weight: 700;
  text-align: center;
  text-transform: uppercase;
  vertical-align: middle;
  align-items: center;
  border-radius: 0.5rem;
  border: 1px solid rgba(0, 0, 0, 0.25);
  gap: 0.75rem;
  color: rgb(65, 63, 63);
  background-color: #fff;
  cursor: pointer;
  transition: all .6s ease;
}

.button svg {
  height: 24px;
}

.button:hover {
  transform: scale(1.02);
}
</style>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
function handleCredentialResponse(response) {
    console.log("Token recibido:", response.credential);

    fetch("google_login.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ token: response.credential })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert("Error al iniciar sesión con Google: " + (data.error || ""));
        }
    });
}

document.getElementById("google-btn").addEventListener("click", () => {
    google.accounts.id.initialize({
        client_id: "758274251302-n1mrjd3jp0r525v57f4h4a0klvp5j0ul.apps.googleusercontent.com",
        callback: handleCredentialResponse
    });
    google.accounts.id.prompt();
});
</script>


      <div class="form-section">
        <p>¿No tienes cuenta? <a href="./registro.php">Crear</a></p>
      </div>
    </div>
  </body>
</html>