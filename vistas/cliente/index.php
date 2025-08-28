<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");   
}


include '../../conexion.php';
include '../../modelos/indexcliente_m.php';

// Total servicios
$sql_servicios = "SELECT COUNT(*) AS total FROM servicios WHERE estado = 'activo'";
$total_servicios = $conn->query($sql_servicios)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - NISSI Cerrajería</title>
 <link rel="stylesheet" href="../../assets/css/cliente/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    .profile-avatar {
  position: relative;
  width: 100px;
  height: 100px;
}

.profile-avatar img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  border: 3px solid #ddd;
}

.avatar-fallback {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  font-size: 2.5rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid rgba(255,255,255,0.3);
}

.edit-avatar {
  position: absolute;
  bottom: 5px;
  right: 5px;
  background: rgba(0,0,0,0.6);
  color: white;
  padding: 6px;
  border-radius: 50%;
  cursor: pointer;
  transition: background 0.3s ease;
}

.edit-avatar:hover {
  background: rgba(0,0,0,0.8);
}

/* Avatar genérico con tamaño dinámico */
.avatar-fallback {
  width: var(--avatar-size, 40px);   /* tamaño por defecto 40px */
  height: var(--avatar-size, 40px);
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: white;
  font-size: calc(var(--avatar-size, 40px) * 0.4); /* letra proporcional */
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Avatar del menú superior (pequeño) */
.user-avatar .avatar-fallback {
  --avatar-size: 40px;
}

/* Avatar del perfil lateral (grande) */
.profile-avatar .avatar-fallback {
  --avatar-size: 100px;
}

</style>
<body>
    <!-- Header y Navegación -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                          <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="../../index.php#servicios">Servicios</a></li>
                        <li><a href="../../index.php#nosotros">Nosotros</a></li>
                        <li><a href="../../index.php#contacto">Contacto</a></li>
                        <li><a href="index.php" class="active">Mi Perfil</a></li>
                        <li>  <div class="header-actions">
                  <a class="cart-icon" href="../../carrito.php"> <i class="fas fa-shopping-cart"></i>  <span id="cantidad-carrito" class="cart-count">0</span></a>
                    <div class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </div>
                </div></li>
                    </ul>
                </nav>
               <div class="user-menu">
    <div class="user-avatar">
        <?php if (!empty($usuario['foto'])): ?>
            <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Avatar">
        <?php else: ?>
            <div class="avatar-fallback">
                <?= strtoupper(substr($usuario['usuario'], 0, 1)) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="user-name"><?= htmlspecialchars($usuario['usuario']) ?></div>
    <i class="fas fa-chevron-down"></i>
    <div class="dropdown-menu">
        <a href="index.php"><i class="fas fa-user"></i> Mi Perfil</a>
        <a href="../../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    </div>
</div>

                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="profile-main">
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <a href="../../index.php">Inicio</a> <i class="fas fa-chevron-right"></i> <span>Mi Perfil</span>
            </div>

            <div class="profile-container">
                <!-- Sidebar de Navegación del Perfil -->
            <aside class="profile-sidebar">
    <div class="profile-user">
        <div class="profile-avatar">
            <?php if (!empty($usuario['foto'])): ?>
                <!-- Si el usuario tiene foto en BD -->
                <img src="<?= htmlspecialchars($usuario['foto']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <!-- Si NO tiene foto, se muestra inicial -->
                <div class="avatar-fallback">
                    <?= strtoupper(substr($usuario['usuario'], 0, 1)) ?>
                </div>
            <?php endif; ?>

        </div>
        <h3><?= htmlspecialchars($usuario['usuario']) ?></h3>
        <p>Cliente desde: <?= htmlspecialchars(date("d/m/Y H:i A", strtotime($usuario['fecha_registro']))) ?></p>
    </div>
    <nav class="profile-nav">
        <ul>
            <li class="active"><a href="#dashboard" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="info.php" data-section="info"><i class="fas fa-user"></i> Información Personal</a></li>
            <li><a href="compras.php" data-section="info"><i class="fas fa-shopping-cart"></i> Compras</a></li>
        </ul>
    </nav>
    <div class="help-box">
        <h4><i class="fas fa-headset"></i> ¿Necesitas ayuda?</h4>
        <p>Estamos aquí para asistirte con cualquier consulta.</p>
        <a href="tel:+573176039806" class="btn btn-outline"><i class="fas fa-phone"></i> Llamar Ahora</a>
    </div>
</aside>


                <!-- Contenido Principal del Perfil -->
                <div class="profile-content">
                    <!-- Dashboard Section -->
                    <section id="dashboard" class="profile-section active">
                        <h2>Dashboard</h2>
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Servicios Del Sistema</h3>
                                     <p class="stat-number"><?php echo $total_servicios; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="recent-services">
                            <div class="section-header-small">
                                <h3>Servicios Recientes</h3>
                                <a href="../../servicios.php" class="view-all" data-section="services">Ver todos <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="services-list">
 <?php
 $sql = "SELECT * FROM servicios WHERE estado = 'activo' LIMIT 3";

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($servicio = $resultado->fetch_assoc()) {
        ?>
        <div class="service-item">
            <div class="service-icon">
                <i class="<?= htmlspecialchars($servicio["imagen"]) ?>"></i>
            </div>
            <div class="service-details">
                <h4><?= htmlspecialchars($servicio["nombre"]) ?></h4>
                <p class="service-status completed">Disponible</p>
            </div>
            <div class="service-price">
                $<?= number_format($servicio["precio"], 2, '.', ',') ?> COP
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No hay servicios disponibles.</p>";
}
?>


                            </div>
                        </div>

                        <div class="quick-actions">
                            <h3>Acciones Rápidas</h3>
                            <div class="actions-grid">
                                <a href="../../index.php#servicios" class="action-card" id="request-service">
                                    <div class="action-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <h4>Solicitar Servicio</h4>
                                </a>
                                <a href="../../index.php#contacto" class="action-card" id="schedule-appointment">
                                    <div class="action-icon">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <h4>Programar Cita</h4>
                                </a>
                                <a href="../../index.php#contacto" class="action-card" id="contact-support">
                                    <div class="action-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <h4>Contactar Soporte</h4>
                                </a>
                                <a href="../../inventario.php" class="action-card" id="view-catalog">
                                    <div class="action-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h4>Ver Catálogo</h4>
                                </a>
                            </div>
                        </div>
                    </section>

              
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                         <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                    <p>Todo lo relacionado con seguridad</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="../../index.php#servicios">Servicios</a></li>
                        <li><a href="../../index.php#nosotros">Nosotros</a></li>
                        <li><a href="../../index.php#galeria">Productos</a></li>
                        <li><a href="../../index.php#contacto">Contacto</a></li>
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
        echo '<a href="../../servicios.php" id="' . $servicio["id"] . '">';
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
    <a href="https://wa.me/573105648667" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- JavaScript -->
     <script src="../../assets/js/cliente.js"></script>
     
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
                
</body>
</html>