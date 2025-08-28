<?php
session_start();

// Recuperar el id del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'] ?? 0; // 0 si no existe
include 'conexion.php';
include 'controlador/buzon_c.php';

$sql = "SELECT * FROM inventario WHERE estado = 'activo' LIMIT 3";
$result = $conn->query($sql);
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
    color: #ffffffff;
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
        <li><a href="#inicio" class="active">Inicio</a></li>
        <li><a href="#servicios">Servicios</a></li>
        <li><a href="#nosotros">Nosotros</a></li>
        <li><a href="#galeria">Productos</a></li>
        <li><a href="#contacto">Contacto</a></li>
        
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

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Soluciones de Seguridad para su Hogar y Negocio</h1>
                    <p>Servicio profesional de cerrajería con más de 15 años de experiencia. Atención 24/7 para emergencias.</p>
                    <div class="hero-buttons">
                        <a href="#contacto" class="btn btn-primary">Solicitar Servicio</a>
                        <a href="tel:+573176039806" class="btn btn-secondary"><i class="fas fa-phone"></i> Llamar Ahora</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="assets/img/logo.jpg" alt="Servicios de Cerrajería">
                </div>
            </div>
        </div>
    </section>

<!-- Servicios Destacados -->
<section class="services" id="servicios">
    <div class="container">
        <div class="section-header">
            <h2>Nuestros Servicios</h2>
            <p>Ofrecemos una amplia gama de servicios de cerrajería para satisfacer todas sus necesidades</p>
        </div>

    <div class="services-grid">
 <?php
$sql = "SELECT * FROM servicios WHERE estado = 'activo' LIMIT 2";
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

        // Mensaje para WhatsApp
        $mensaje = "Hola, soy el usuario *#${id_usuario}* estoy interesado en su servicio de {$servicio["nombre"]}, por un precio de $" . number_format($servicio["precio"], 0, ',', '.') . " COP, ¿Podría darme más información?";
        $urlWA = "https://wa.me/573176039806?text=" . urlencode($mensaje);

        if (isset($_SESSION["id_usuario"])) {
            // ✅ Usuario con sesión → guarda en BD y abre WhatsApp
            echo '<button type="button" class="service-link solicitar" 
                    onclick="enviarYRedirigirWhatsApp(\'' . $formId . '\', \'' . $urlWA . '\')">
                    Solicitar <i class=\'fas fa-arrow-right\'></i>
                  </button>';
        } else {
            // 🚪 Usuario sin sesión → alerta y luego login
            echo '<button type="button" class="service-link solicitar" 
                    onclick="alert(\'Debes iniciar sesión para solicitar un servicio\'); window.location.href=\'vistas/login.php\'">
                    Solicitar <i class=\'fas fa-arrow-right\'></i>
                  </button>';
        }

        echo '<a href="#" class="service-link" onclick="enviarFormularioYRedirigir(event, \'' . $servicio["id"] . '\', \'' . $servicio["nombre"] . '\', \'' . $servicio["precio"] . '\')">Agregar al Carrito</a>';
        
        echo '</form>';
    }
} else {
    echo "<p>No hay servicios disponibles.</p>";
}
?>

</div>
    <div class="recent-services">
                            <div class="section-header-small">
                                <a href="servicios.php" class="view-all" data-section="services">Ver Todo <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="services-list">
</section>



    <!-- Por qué elegirnos -->
    <section class="why-us">
        <div class="container">
            <div class="section-header">
                <h2>¿Por qué elegir NISSI Cerrajería?</h2>
                <p>Nos destacamos por ofrecer un servicio de calidad, rápido y confiable</p>
            </div>
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Servicio Rápido</h3>
                    <p>Atendemos sus emergencias en menos de 30 minutos en la mayoría de las zonas.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>Profesionales Certificados</h3>
                    <p>Nuestro equipo está compuesto por cerrajeros profesionales con amplia experiencia.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Precios Competitivos</h3>
                    <p>Ofrecemos servicios de alta calidad a precios justos y transparentes.</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-thumbs-up"></i>
                    </div>
                    <h3>Garantía de Satisfacción</h3>
                    <p>Todos nuestros trabajos cuentan con garantía para su tranquilidad.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section class="about-us" id="nosotros">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="assets/img/logo.jpg" alt="Equipo NISSI Cerrajería">
                </div>
                <div class="about-text">
                    <h2>Sobre NISSI Cerrajería</h2>
                    <p>Somos una empresa familiar con más de 15 años de experiencia en el sector de la cerrajería. Nuestro compromiso es brindar soluciones de seguridad confiables y de calidad para hogares y negocios.</p>
                    <p>En NISSI Cerrajería contamos con un equipo de profesionales altamente capacitados y utilizamos herramientas y tecnología de última generación para garantizar un servicio excepcional.</p>
                    <div class="about-stats">
                        <div class="stat">
                            <h3>15+</h3>
                            <p>Años de experiencia</p>
                        </div>
                        <div class="stat">
                            <h3>5000+</h3>
                            <p>Clientes satisfechos</p>
                        </div>
                        <div class="stat">
                            <h3>24/7</h3>
                            <p>Servicio de emergencia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

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
          <div class="recent-services">
                            <div class="section-header-small">
                                <a href="inventario.php" class="view-all" data-section="services">Ver Todo <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="services-list">
    </div>
</section>




    <!-- Contacto -->
    <section class="contact" id="contacto">
        <div class="container">
            <div class="section-header">
                <h2>Contáctenos</h2>
                <p>Estamos listos para atender sus necesidades de cerrajería</p>
            </div>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Dirección</h3>
                        <p>Calle Principal #123, Ciudad</p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Teléfonos</h3>
                        <p><a href="tel:+573105648667">310 564 8667</a></p>
                        <p><a href="tel:+573102414997">310 241 4997</a></p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p><a href="mailto:info@nissicerrajeria.com">info@nissicerrajeria.com</a></p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Horario</h3>
                        <p>Lunes a Sábado: 8am - 8pm</p>
                        <p>Emergencias: 24/7</p>
                    </div>
                    <div class="social-media">
                        <h3>Síguenos</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Contactar</h3>
                <form action="index.php" method="POST">
    <div class="form-group">
        <label for="name">Nombre Completo</label>
        <input type="text" id="name" name="nombre" required>
    </div>

    <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="number" id="phone" name="celular" minlength="10" maxlength="10" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="mail" required>
    </div>

    <div class="form-group">
        <label for="service">Servicio que necesita</label>
        <?php
        $sql = "SELECT * FROM servicios";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            echo '<select id="service" name="servicio" >';
            echo '<option value="">Seleccionar</option>';
            while ($servicio = $resultado->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($servicio["nombre"]) . '">' . htmlspecialchars($servicio["nombre"]) . '</option>';
            }
            echo '<option value="Otro">Otro</option>';
            echo '</select>';
        } else {
            echo "<p>No hay servicios disponibles.</p>";
        }
        ?>
    </div>

   <div class="form-group">
    <label for="message">Mensaje</label>
    <input required maxlength="80" id="message" name="mensaje" type="text">
    <small id="messageCounter">80 caracteres restantes</small>
</div>


    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
</form>

                </div>
            </div>
        </div>
    </section>

    <!-- Llamada a la acción -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>¿Necesita un servicio de cerrajería urgente?</h2>
                <p>Estamos disponibles 24/7 para atender sus emergencias</p>
                <a href="tel:+573176039806" class="btn btn-light"><i class="fas fa-phone"></i> Llamar Ahora</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                         <img src="assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                    <p>Todo lo relacionado con seguridad</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#galeria">Productos</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-services">
                    <h3>Servicios</h3>
                   <?php
$sql = "SELECT * FROM servicios WHERE estado = 'activo' LIMIT 5";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    echo '<ul>';
    while ($servicio = $resultado->fetch_assoc()) {
        echo '<li>';
        echo '<a href="servicios.php" id="' . $servicio["id"] . '">';
        echo htmlspecialchars($servicio["nombre"]);
        echo '</a>';
        echo '</li>';
    }
    echo '</ul>';
}
?>

                </div>
                <div class="footer-contact">
                    <h3>Contacto</h3>
                    <p><i class="fas fa-phone"></i> 310 564 8667 | 310 241 4997</p>
                    <p><i class="fas fa-envelope"></i> info@nissicerrajeria.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Calle Principal #123, Ciudad</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 NISSI Cerrajería. Todos los derechos reservados.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Flotante -->
<a href="https://wa.me/573176039806?text=Hola%2C%20estoy%20interesado%20en%20sus%20servicios.%20¿Podría%20darme%20más%20información%3F" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>


    <!-- JavaScript -->
    <script src="assets/js/cliente.js"></script>
    <script>
  function agregarAlCarrito(event, id, nombre, precio) {
    event.preventDefault(); // Evita que el form se envíe y recargue

    let productos = JSON.parse(sessionStorage.getItem("productos")) || [];

    // Validar si ya está agregado
    const existe = productos.some(item => item.id === id);
    if (existe) {
        alert("Ups! este producto ya fue agregado");
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
    alert(nombre + " ha sido añadido al carrito");
}

    </script>
<script>
    
document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("message");
  const counter = document.getElementById("messageCounter");
  const max = input.getAttribute("maxlength");

  input.addEventListener("input", function () {
    const restante = max - input.value.length;
    counter.textContent = `${restante} caracteres restantes`;
  });
});
</script>

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
        // Opcional: notificación visual
    alert(nombre + " ha sido añadido al carrito");
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
        alert("Debes iniciar sesión para solicitar un servicio.");
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

