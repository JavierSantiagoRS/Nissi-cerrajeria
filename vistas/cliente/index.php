<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");   
}


include '../../conexion.php';
include '../../modelos/indexcliente_m.php';

// Total servicios
$sql_servicios = "SELECT COUNT(*) AS total FROM servicios";
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
<body>
    <!-- Header y Navegación -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="../../index.php">Inicio</a></li>
                        <li><a href="../../index.php#servicios">Servicios</a></li>
                        <li><a href="../../index.php#nosotros">Nosotros</a></li>
                        <li><a href="../../index.php#contacto">Contacto</a></li>
                        <li><a href="index.php" class="active">Mi Perfil</a></li>
                    </ul>
                </nav>
                <div class="user-menu">
                    <div class="user-avatar">
                        <img src="/placeholder.svg?height=40&width=40" alt="">
                    </div>
                    <div class="user-name"><?= htmlspecialchars($usuario['usuario']) ?></div>
                    <i class="fas fa-chevron-down"></i>
                    <div class="dropdown-menu">
                        <a href="index.php"><i class="fas fa-user"></i> Mi Perfil</a>
                        <a href="#"><i class="fas fa-cog"></i> Configuración</a>
                        <a href="../../index.php" class="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
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
                            <img src="/placeholder.svg?height=100&width=100" alt="">
                            <div class="edit-avatar">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <h3><?= htmlspecialchars($usuario['usuario']) ?></h3>
                        <p>Cliente desde:  <?= htmlspecialchars(date("d/m/Y H:i A", strtotime($usuario['fecha_registro']))) ?></p>
                    </div>
                    <nav class="profile-nav">
                        <ul>
                            <li class="active"><a href="#dashboard" data-section="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="#info" data-section="info"><i class="fas fa-user"></i> Información Personal</a></li>
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
                                    <h3>Servicios Totales</h3>
                                     <p class="stat-number"><?php echo $total_servicios; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="recent-services">
                            <div class="section-header-small">
                                <h3>Servicios Recientes</h3>
                                <a href="../../index.php#servicios" class="view-all" data-section="services">Ver todos <i class="fas fa-arrow-right"></i></a>
                            </div>
                            <div class="services-list">
 <?php
 $sql = "SELECT * FROM servicios LIMIT 3";

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
                                <a href="../../index.php#galeria" class="action-card" id="view-catalog">
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
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                    <p>Todo lo relacionado con seguridad</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="index.html#servicios">Servicios</a></li>
                        <li><a href="index.html#nosotros">Nosotros</a></li>
                        <li><a href="index.html#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-services">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="index.html#servicios">Duplicado de llaves</a></li>
                        <li><a href="index.html#servicios">Instalación de chapas</a></li>
                        <li><a href="index.html#servicios">Cambio de guardas</a></li>
                        <li><a href="index.html#servicios">Apertura de carros</a></li>
                        <li><a href="index.html#servicios">Cerraduras digitales</a></li>
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
    <a href="https://wa.me/573105648667" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- JavaScript -->
     <script src="../../assets/js/cliente.js"></script>
</body>
</html>