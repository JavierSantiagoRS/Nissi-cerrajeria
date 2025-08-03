<?php
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
                        <li><a href="index.php#galeria">Galería</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
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

 
<!-- Servicios Destacados -->
<section class="services" id="servicios">
    <div class="container">
        <div class="section-header">
            <h2>Nuestros Servicios</h2>
            <p>Ofrecemos una amplia gama de servicios de cerrajería para satisfacer todas sus necesidades</p>
        </div>

    <div class="services-grid">
    <?php
     $sql = "SELECT * FROM servicios";
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

</section>



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