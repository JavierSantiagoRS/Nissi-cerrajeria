<?php
include 'conexion.php';

include 'controlador/buzon_c.php';


  $sql = "SELECT * FROM inventario LIMIT 3";
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
                        <li><a href="#galeria">Galería</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                        <li><a href="vistas\login.php">Perfil</a></li>
                        <li> <a class="cart-icon" href="carrito.php"> <i class="fas fa-shopping-cart"></i>  <span id="cantidad-carrito" class="cart-count">0</span></a></li>
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
                    <img src="/placeholder.svg?height=400&width=500" alt="Servicios de Cerrajería">
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
     $sql = "SELECT * FROM servicios LIMIT 3";
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
            $mensaje = "Hola, estoy interesado en su servicio de {$servicio["nombre"]}, por un precio de $" . number_format($servicio["precio"], 0, ',', '.') . " COP, ¿Podría darme más información?";
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
                    <img src="/placeholder.svg?height=400&width=500" alt="Equipo NISSI Cerrajería">
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

    <!-- Testimonios -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Lo que dicen nuestros clientes</h2>
                <p>La satisfacción de nuestros clientes es nuestra mejor carta de presentación</p>
            </div>
            <div class="testimonial-slider">
                <div class="testimonial-slide active">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            <p>"Excelente servicio. Llegaron en menos de 20 minutos cuando me quedé fuera de casa. Muy profesionales y precios justos."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-image">
                                <img src="/placeholder.svg?height=60&width=60" alt="Cliente">
                            </div>
                            <div class="author-info">
                                <h4>Carlos Rodríguez</h4>
                                <p>Cliente Residencial</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-slide">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            <p>"Instalaron cerraduras digitales en mi negocio y el servicio fue impecable. Muy recomendados para cualquier tipo de trabajo de cerrajería."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-image">
                                <img src="/placeholder.svg?height=60&width=60" alt="Cliente">
                            </div>
                            <div class="author-info">
                                <h4>María López</h4>
                                <p>Propietaria de Negocio</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-slide">
                    <div class="testimonial-content">
                        <div class="testimonial-text">
                            <p>"Me ayudaron a abrir mi carro cuando perdí las llaves. Rápidos, eficientes y no dañaron nada. Definitivamente los llamaré de nuevo si necesito un cerrajero."</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-image">
                                <img src="/placeholder.svg?height=60&width=60" alt="Cliente">
                            </div>
                            <div class="author-info">
                                <h4>Juan Pérez</h4>
                                <p>Cliente Automotriz</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-controls">
                <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                <div class="testimonial-dots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                </div>
                <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
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
            
            echo '<a href="producto.php?id=' . $row["id"] . '" class="gallery-item">';
            echo '    <img src="assets/' . htmlspecialchars($row["imagen"]) . '" alt="' . htmlspecialchars($row["titulo"]) . '">';
            echo '    <div class="gallery-overlay">';
            echo '        <p>' . htmlspecialchars($row["titulo"]) . '</p>';
            echo '        <h3>$' . number_format($row["precio"], 0, ',', '.') . ' COP</h3>';
            echo '    </div>';
            echo '</a>';
         

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



<script>
function agregarProducto(event, id, nombre, precio) {
    event.preventDefault(); // Evita que el enlace se abra de inmediato
    let productos = JSON.parse(localStorage.getItem("productos"));
    if (productos == null) {
        productos = []
    }
    let validoExistencia = false;
    productos.forEach((val) => {
        if (val.id == id) {
            validoExistencia = true;
        }
    });
    if(validoExistencia){
        alert("UPPS: este producto ya fue agregado");
            return;
    }
    const producto = {
        'id': id,
        'nombre': nombre,
        'cantidad': 1,
        'precio': precio,
        'subtotal': precio
    };
    productos.push(producto);
    
    productos = JSON.stringify(productos);
    localStorage.setItem('productos',productos.toString())  

    alert("Producto agregado satisfactoriamente");
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
                    <h3>Solicite</h3>
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
            echo '<select id="service" name="servicio" required>';
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
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                    <p>Todo lo relacionado con seguridad</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#galeria">Galería</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-services">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="#servicios">Duplicado de llaves</a></li>
                        <li><a href="#servicios">Instalación de chapas</a></li>
                        <li><a href="#servicios">Cambio de guardas</a></li>
                        <li><a href="#servicios">Apertura de carros</a></li>
                        <li><a href="#servicios">Cerraduras digitales</a></li>
                    </ul>
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
    let servicios = JSON.parse(localStorage.getItem("servicios"));
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
    localStorage.setItem('servicios',servicios.toString())  

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
                        let p = JSON.parse(localStorage.getItem("productos"));
                        if (p==null){
                            p=0
                        }else{
                            p=p.length
                        }

                        let s = JSON.parse(localStorage.getItem("servicios"));                        
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


</body>
</html>

