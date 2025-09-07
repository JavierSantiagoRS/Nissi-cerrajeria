<?php
session_start();
include 'conexion.php';

 $sql = "SELECT * FROM inventario WHERE estado = 'activo'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NISSI Cerrajería - Productos de Cerrajería Profesional</title>
    <link rel="stylesheet" href="assets/css/cliente.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    /* Estilo general para botones */
.service-link,
.solicitar,
.view-all {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-weight: 600;
  font-size: 15px;
  padding: 10px 18px;
  border-radius: 6px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

/* Botón "Solicitar" (formal, sin color) */
.solicitar {
  background: transparent;
  color: #4244c2ff;
  border: 1px solid  #4244c2ff;
}

.solicitar:hover {
  background-color:  #4244c2ff;
  color: #ffffff;
  transform: translateY(-2px);
}

/* Botón "Ver Todo" */
.view-all {
  border: 1px solid  #4244c2ff;
  color: #4244c2ff;
  background: transparent;
  font-size: 16px;
  padding: 12px 24px;
  margin-top: 20px;
  display: inline-flex;
}

/* Hover elegante */
.view-all:hover {
  background-color: #4244c2ff;
  color: #ffffff;
  transform: translateX(4px);
}

/* Contenedor del botón "Ver Todo" */
.section-header-small {
  display: flex;
  justify-content: center;   /* centrado horizontal */
  margin-top: 30px;
}

/* Responsive */
@media (max-width: 768px) {
  .view-all {
    width: 100%;
    justify-content: center;
    font-size: 15px;
  }
  .solicitar {
    width: 100%;
    justify-content: center;
  }
}

/* Card base */
.product-card{
    position:relative;
    overflow:hidden;
    border-radius:12px;
    background:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,.06);
    transition:transform .2s ease;
}
.product-card:hover{ transform:translateY(-4px); }

/* Imagen ocupa la card */
.product-image{
    width:100%;
    height:280px;
    object-fit:cover;
    display:block;
}

/* Info debajo */
.product-info{ padding:12px; text-align:center; }
.product-title{ font-size:16px; font-weight:600; color:#222; margin:6px 0 4px; }
.product-price{ font-size:18px; font-weight:700; color: #181ba3ff; }

/* Toda la card clickeable (excepto el botón) */
.card-hit{
    position:absolute;
    inset:0;
    z-index:1;            /* debajo del overlay/botón */
}

/* Overlay azul oscuro translúcido */
.cart-overlay{
    position:absolute;
    inset:0;
    display:flex;
    justify-content:center;
    align-items:center;
    background:rgba(0,17,51,0.45); /* azul muy oscuro con transparencia */
    opacity:0;
    transition:opacity .25s ease;
    z-index:2;
    pointer-events:none;  /* deja pasar el clic a .card-hit */
}
.product-card:hover .cart-overlay{ opacity:1; }

/* Botón azul marino */
.btn-add-cart{
    pointer-events:auto;  /* el botón SÍ recibe clics */
    padding:12px 20px;
    font-size:15px;
    font-weight:600;
    border:0;
    border-radius:8px;
    background:linear-gradient(135deg,#003366,#001f4d);
    color:#fff;
    display:inline-flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    transform:translateY(10px);
    transition:transform .2s ease, background .3s ease;
}
.product-card:hover .btn-add-cart{ transform:translateY(0); }
.btn-add-cart:hover{ background:linear-gradient(135deg,#00264d,#001233); }

.inicial-perfil {
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    border-radius: 50%;
    background: #0011ffff; /* azul */
    color: #fff;
    font-weight: bold;
    text-align: center;
    font-size: 16px;
}

@media (max-width: 768px) {
  /* Quitamos el overlay translúcido en móviles */
  .cart-overlay {
    position: relative;     /* ya no cubre toda la card */
    inset: auto;            /* resetea top/right/bottom/left */
    background: transparent; /* sin fondo oscuro */
    opacity: 1 !important;   /* siempre visible */
    display: block;
    pointer-events: auto;    /* vuelve a permitir clics normales */
    text-align: center;
    margin: 10px 0 15px;
  }

  /* Botón ocupa todo el ancho */
  .btn-add-cart {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    font-size: 15px;
    background: #4244c2;   /* tu color corporativo */
    background: linear-gradient(135deg,#4244c2,#181ba3);
  }

  .btn-add-cart:hover {
    background: linear-gradient(135deg,#2a2c8a,#0f1066);
  }
}

</style>
<body>
    <!-- Header y Navegación -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="assets\img\logo.jpg" alt="NISSI Cerrajería">
                </div>
               
           
          <nav class="main-nav">
    <ul>
             <li><a href="index.php#inicio">Inicio</a></li>
                        <li><a href="index.php#servicios">Servicios</a></li>
                        <li><a href="index.php#nosotros">Nosotros</a></li>
                        <li><a href="#galeria"  class="active">Productos</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
        
        <?php if (isset($_SESSION["id_usuario"])): ?>
            <?php
            // Traer el nombre de usuario
            $id_usuario = $_SESSION["id_usuario"];
            $sql_user = "SELECT usuario FROM usuarios WHERE id = $id_usuario";
            $res_user = $conn->query($sql_user);
            $inicial = "?";

            if ($res_user && $res_user->num_rows > 0) {
                $row_user = $res_user->fetch_assoc();
                $inicial = strtoupper(substr($row_user["usuario"], 0, 1)); // inicial
            }
            ?>
            <li>
                <a href="vistas/cliente/index.php" class="perfil-icon">
                    <span class="inicial-perfil"><?php echo $inicial; ?></span>
                </a>
            </li>
        <?php else: ?>
            <li><a href="vistas/login.php">Iniciar sesión</a></li>
        <?php endif; ?>
        
        <li>
            <a class="cart-icon" href="carrito.php">
                <i class="fas fa-shopping-cart"></i>  
                <span id="cantidad-carrito" class="cart-count">0</span>
            </a>
        </li>
    </ul>
</nav>
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Galería -->
<section class="gallery" id="galeria">
    <div class="container">
        <div class="section-header">
            <h2>Galería de Productos</h2>
            <p>Algunos de nuestros Productos y servicios</p>
        </div>
        <div class="gallery-grid">
  <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id     = (int)$row['id'];
        $titulo = htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8');
        $precio = (int)$row['precio'];
        $img    = htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8');
?>
    <div class="product-card">
        <!-- Contenido visual -->
        <img src="assets/<?php echo $img; ?>" alt="<?php echo $titulo; ?>" class="product-image">
        <div class="product-info">
            <p class="product-title"><?php echo $titulo; ?></p>
            <h3 class="product-price">$<?php echo number_format($precio, 0, ',', '.'); ?> COP</h3>
        </div>

        <!-- Capa de clic para toda la card -->
        <a class="card-hit" href="producto.php?id=<?php echo $id; ?>" aria-label="Ver <?php echo $titulo; ?>"></a>

        <!-- Overlay con botón (solo el botón captura el clic) -->
       <form action="controlador/pedido_c.php?accion=crear" method="POST" class="cart-overlay" 
      onsubmit="agregarAlCarrito(event, <?php echo $id; ?>, '<?php echo $titulo; ?>', <?php echo $precio; ?>)">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="nombre" value="<?php echo $titulo; ?>">
    <input type="hidden" name="precio" value="<?php echo $precio; ?>">
    <input type="hidden" name="cantidad" value="1">
    <input type="hidden" name="tipo" value="producto">
    <button type="submit" class="btn-add-cart" onclick="event.stopPropagation();">
        <i class="fas fa-shopping-cart"></i> Añadir al Carrito
    </button>
</form>


    </div>
<?php
    }
} else {
    echo "<p>No hay inventarios registrados aún.</p>";
}
?>
        </div>
        
    </div>
</section>

 <script src="assets/js/cliente.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function enviarFormularioYRedirigir(event, id, nombre, precio) {
    event.preventDefault(); // Evita que el enlace se abra de inmediato
    let servicios = JSON.parse(sessionStorage.getItem("servicios"));
    if (servicios == null) {
        servicios = []
    }

    let validoExistencia = false;
    servicios.forEach((val) => {
        if (val.id == id) {
            validoExistencia = true;
        }
    });
    if(validoExistencia){
        alert("Ups! este servicio ya fue agregado");
            return;
    }

    const servicio = {
        'id': id,
        'nombre': nombre,
        'cantidad': 1,
        'precio': precio,
        'subtotal': precio
    };
    servicios.push(servicio);
    
    servicios = JSON.stringify(servicios);
    sessionStorage.setItem('servicios',servicios.toString())  

    cantidadCarro();
    // const form = document.getElementById(formId);

    // // Envía el formulario usando fetch (sin recargar la página)
    // const formData = new FormData(form);
    // fetch(form.action, {
    //     method: "POST",
    //     body: formData
    // }).then(() => {
    //     // Después de guardar, redirige a WhatsApp
    //     window.open(whatsappUrl, "_blank");
    // }).catch(error => {
    //     alert("Error al enviar el formulario.");
    //     console.error(error);
    // });
}
</script>
     <script>
                    function cantidadCarro() {
                        let p = JSON.parse(sessionStorage.getItem("productos"));
                        if (p==null){
                            p=0
                        }else{
                            p=p.length
                        }

                        let s = JSON.parse(sessionStorage.getItem("servicios"));                        
                        if (s==null){
                            s=0
                        }else{
                            s=s.length
                        }
                        let cant = p + s;
                        document.getElementById("cantidad-carrito").innerHTML = cant;
                    }
                    cantidadCarro();
                </script>

                <?php
// Antes de tu loop
$usuario_logueado = isset($_SESSION['usuario']); // Cambia por tu variable real de sesión
?>
<script>
    const usuarioLogueado = <?php echo $usuario_logueado ? 'true' : 'false'; ?>;

    function enviarYRedirigirWhatsApp(formId, urlWA) {
        if (!usuarioLogueado) {
            alert("Debes iniciar sesión para solicitar un servicio.");
            return; // Detiene la función
        }
        document.getElementById(formId).submit();
        window.location.href = urlWA;
    }
</script>
                
    <script>
  function agregarAlCarrito(event, id, nombre, precio) {
    event.preventDefault(); // Evita que el form se envíe y recargue

    let productos = JSON.parse(sessionStorage.getItem("productos")) || [];

    // Validar si ya está agregado
    const existe = productos.some(item => item.id === id);
    if (existe) {
              Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "Este producto ya fue agregado!",
  
});
        return;
    }

    // Agregar al carrito
    const producto = {
        id: id,
        nombre: nombre,
        cantidad: 1,
        precio: precio,
        subtotal: precio
    };

    productos.push(producto);
    sessionStorage.setItem("productos", JSON.stringify(productos));

    // Actualizar contador
    cantidadCarro();

    // Opcional: notificación visual
Swal.fire({
  position: "top-end",
  icon: "success",
 title: nombre + " ha sido añadido al carrito",
  showConfirmButton: false,
  timer: 1500
});
   
}

    </script>

</body>
</html>