<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="assets/css/iniciar/iniciar.css" />
  </head>
  <body>
    <div class="form-box">
      <form action="../controlador/usuarios_c.php?accion=ingresar" method="POST"class="form">
        <span class="title">Acceder a cuenta</span>
        <span class="subtitle"></span>
        <div class="form-container">
          <input type="text" name="usuario" class="input" placeholder="Usuario" />
          <input type="password" name="clave" class="input" placeholder="Contraseña" />
        </div>
        <button  type="submit" class="btn btn-succes">Acceder</button>
      </form>
      <div class="form-section">
        <p>¿No tienes cuenta? <a href="./registro.php">Crear</a></p>
      </div>
    </div>
  </body>
</html>

