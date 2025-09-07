<?php
session_start();
include 'conexion.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NISSI Cerrajería - Servicios de Cerrajería Profesional</title>
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
                        <li><a href="#servicios"  class="active">Servicios</a></li>
                        <li><a href="index.php#nosotros">Nosotros</a></li>
                        <li><a href="index.php#galeria">Productos</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
        
                        <?php
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : "Invitado";
?>

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

 
<!-- Servicios Destacados -->
<section class="services" id="servicios">
    <div class="container">
        <div class="section-header">
            <h2>Nuestros Servicios</h2>
            <p>Ofrecemos una amplia gama de servicios de cerrajería para satisfacer todas sus necesidades</p>
        </div>

    <div class="services-grid">
    <?php
     $sql = "SELECT * FROM servicios WHERE estado = 'activo'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        while ($servicio = $resultado->fetch_assoc()) {
            // ID único para el formulario
            $formId = 'form-' . $servicio["id"];
            
            echo '<form id="' . $formId . '" action="controlador/pedido_c.php?accion=crear" method="POST" enctype="multipart/form-data" class="service-card">';
            echo '  <div class="service-icon"><i class="' . htmlspecialchars($servicio["imagen"]) . '"></i></div>';
            echo '  <h3>' . htmlspecialchars($servicio["nombre"]) . '</h3>';
            echo '  <p>' . htmlspecialchars($servicio["descripcion"]) . '</p>';

            // Inputs ocultos
            echo '<input type="hidden" name="cantidad" value="1">';
            echo '<input type="hidden" name="nombre" value="' . htmlspecialchars($servicio["nombre"]) . '">';
            echo '<input type="hidden" name="tipo" value="servicio">';
            echo '<input type="hidden" name="precio" value="' . intval($servicio["precio"]) . '">';

            // Enlace con evento JavaScript
            $mensaje = "Hola, soy el usuario *#${id_usuario}* y estoy interesado en su servicio de {$servicio["nombre"]}, por un precio de $" . number_format($servicio["precio"], 0, ',', '.') . " COP, ¿Podría darme más información?";
            $urlWA = "https://wa.me/573176039806?text=" . urlencode($mensaje);

             echo '<button type="button" class="service-link solicitar" onclick="enviarYRedirigirWhatsApp(\'' . $formId . '\', \'' . $urlWA . '\')">Solicitar <i class=\'fas fa-arrow-right\'></i></button>';

            echo '<a href="#" class="service-link" onclick="enviarFormularioYRedirigir(event, \'' . $servicio["id"] . '\', \'' . $servicio["nombre"] . '\', \'' . $servicio["precio"] . '\')">Agregar al Carrito</a>';
           

            echo '</form>';
        }
    } else {
        echo "<p>No hay servicios disponibles.</p>";
    }
    ?>
</div>

</section>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="assets/js/cliente.js"></script>
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
             Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "Este servicio ya fue agregado!",
});
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

                <script>
function enviarYRedirigirWhatsApp(formId, urlWA) {
    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Espera medio segundo y luego redirige a WhatsApp
            setTimeout(() => {
                window.open(urlWA, '_blank');
            }, 500);
        } else {
            alert('Error al registrar la solicitud.');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        alert('No se pudo registrar la venta.');
    });
}
</script>

<?php
// Antes de tu loop
$usuario_logueado = isset($_SESSION['usuario']); // Cambia por tu variable real de sesión
?>
<script>
const usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;

function enviarYRedirigirWhatsApp(formId, urlWA) {
    if (!usuarioLogueado) {
  
        Swal.fire({
  title: "Debes iniciar sesión para solicitar un servicio.",
  icon: "warning",
  draggable: true
});

        window.location.href = "vistas/login.php";
        return;
    }

    const form = document.getElementById(formId);
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            setTimeout(() => {
                window.open(urlWA, '_blank');
            }, 500);
        } else {
            alert('Error al registrar la solicitud.');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
        alert('No se pudo registrar la venta.');
    });
}
</script>
</body>
</html>