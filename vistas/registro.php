
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="assets/css/crear/crear.css" />
   
  </head>
  <body>
    <!-- From Uiverse.io by alexruix -->
    <div class="form-box">
    <form action="../controlador/usuarios_c.php?accion=registro" method="POST">
        <span class="title">Crear cuenta</span>
        <span class="subtitle"
          >Solo personal autorizado podra crear cuenta.</span
        >
        <div class="form-container">

          <input type="email" name="nombre" class="input" placeholder="Email" required/>
          <input type="text" name="usuario" class="input" placeholder="Nombre Usuario" required/>
          <input type="number" name="celular" class="input" minlength="10" maxlength="10" placeholder="Celular" required/>
          <input type="password" name="clave" class="input" placeholder="Contraseña" required/>

           <input type="hidden" value="cliente">

        </div>
        <button type="submit" class="btn btn-succes">Crear</button>
      </form>
      <div class="form-section">
        <p>¿ya tienes cuenta? <a href="login.php">Acceder</a></p>
      </div>
    </div>
  </body>
</html>



     
