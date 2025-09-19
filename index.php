<?php
session_start();

// Recuperar el id del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'] ?? 0; // 0 si no existe
include 'conexion.php';
include 'controlador/buzon_c.php';

// Obtener IP y User Agent
$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Revisar si ya hay visita de este dispositivo en esta hora
$sql = "SELECT id 
        FROM visitas 
        WHERE ip = ? 
          AND user_agent = ? 
          AND DATE(fecha) = CURDATE() 
          AND HOUR(fecha) = HOUR(NOW())
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $ip, $userAgent);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insertar nueva visita (única por hora)
    $sqlInsert = "INSERT INTO visitas (fecha, ip, user_agent) VALUES (NOW(), ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ss", $ip, $userAgent);
    $stmtInsert->execute();
}

$sql = "SELECT * FROM inventario WHERE estado = 'activo' LIMIT 3";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NISSI Cerrajería - Servicios de Cerrajería Profesional</title>
    <link rel="assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
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

.cart-icon {
  position: relative;
  color: var(--primary-blue);
  font-size: 1.3rem;
  transition: color 0.3s;
  cursor: pointer;
  text-decoration: none;
}

.cart-icon:hover {
  color: var(--secondary-blue);
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: var(--primary-yellow);
  color: var(--black);
  border-radius: 50%;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: bold;
  min-width: 18px;
}

/* MODAL STYLES */
.cart-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(3px);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  opacity: 0;
  transition: all 0.3s ease;
}

.cart-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  display: none;
  justify-content: flex-end; /* empuja el modal hacia la derecha */
  align-items: stretch;
  z-index: 1000;
  transition: opacity 0.3s ease;
  opacity: 0;
}

.cart-modal-overlay.active {
  display: flex;
  opacity: 1;
}

.cart-modal {
  background: white;
  border-radius: 16px 0 ; /* redondeado solo en el borde izquierdo */
  width: 50%; /* ocupa la mitad de la pantalla */
  max-width: 700px;
  height: 100%; /* que ocupe toda la altura */
  box-shadow: -5px 0 15px rgba(0,0,0,0.2);
  transform: translateX(100%); /* inicialmente fuera de la pantalla */
  transition: transform 0.3s ease;
  overflow-y: auto;
}

.cart-modal-overlay.active .cart-modal {
  transform: translateX(0); /* entra desde la derecha */
}


.cart-modal-header {
  padding: 24px;
  border-bottom: 1px solid #e5e5e5;
  display: flex;
  justify-content: between;
  align-items: center;
  background: linear-gradient(135deg, #4244c2ff 0%, #181ba3ff 100%);
  color: white;
  
}

.cart-modal-title {
  font-size: 24px;
  font-weight: 600;
  margin: 0;
  flex: 1;
}

.cart-modal-close {
  background: none;
  border: none;
  font-size: 28px;
  color: white;
  cursor: pointer;
  padding: 8px;
  border-radius: 50%;
  transition: all 0.2s ease;
  margin-left: auto;
}

.cart-modal-close:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: rotate(90deg);
}

.cart-modal-body {
  padding: 0;
}

/* Cart content styles */
.cart-section {
  background: white;
  margin-bottom: 0;
  border: none;
  border-radius: 0;
  box-shadow: none;
}

.cart-section-title {
  padding: 20px 24px;
  border-bottom: 1px solid rgba(66, 68, 194, 0.1);
  font-size: 18px;
  font-weight: 600;
  color: #4244c2ff;
  background: rgba(66, 68, 194, 0.05);
  margin: 0;
}

.cart-table-container {
  overflow-x: auto;
}

.cart-table {
  width: 100%;
  border-collapse: collapse;
}

.cart-table th {
  padding: 16px 24px;
  text-align: left;
  font-weight: 500;
  color: #4244c2ff;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  background: rgba(66, 68, 194, 0.05);
  border-bottom: 2px solid rgba(66, 68, 194, 0.1);
}

.cart-table th:last-child {
  text-align: center;
  width: 60px;
}

.cart-table td {
  padding: 20px 24px;
  border-bottom: 1px solid rgba(66, 68, 194, 0.08);
  color: #444;
}

.cart-table tbody tr {
  transition: all 0.2s ease;
}

.cart-table tbody tr:hover {
  background: rgba(66, 68, 194, 0.03);
}

.cart-table tbody tr:last-child td {
  border-bottom: none;
}

.cart-quantity-input {
  width: 70px;
  padding: 8px;
  border: 2px solid #e1e8ed;
  border-radius: 8px;
  text-align: center;
  font-size: 14px;
  background: white;
  color: #4244c2ff;
  font-weight: 500;
  transition: all 0.3s ease;
}

.cart-quantity-input:focus {
  outline: none;
  border-color: #4244c2ff;
  box-shadow: 0 0 0 3px rgba(66, 68, 194, 0.1);
}

.cart-remove-btn {
  width: 32px;
  height: 32px;
  border: none;
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.2s ease;
}

.cart-remove-btn:hover {
  background: #ef4444;
  color: white;
  transform: scale(1.05);
}

.cart-price {
  font-weight: 500;
  color: #4244c2ff;
}

.cart-subtotal {
  font-weight: 600;
  color: #10b981;
  font-size: 16px;
}

.cart-empty-state {
  text-align: center;
  padding: 48px 24px;
  color: #9ca3af;
}

.cart-empty-state h3 {
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 8px;
  color: #6b7280;
}

.cart-empty-state p {
  font-size: 15px;
}

.cart-total-section {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 0 0 16px 16px;
  padding: 24px;
  border-top: 1px solid #e5e5e5;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.cart-total-info {
  display: flex;
  align-items: baseline;
  gap: 16px;
}

.cart-total-label {
  font-size: 20px;
  font-weight: 500;
  color: #4244c2ff;
}

.cart-total-amount {
  font-size: 32px;
  font-weight: 600;
  color: #10b981;
  text-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
}

.cart-confirm-btn {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  border: none;
  padding: 16px 32px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.cart-confirm-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.cart-confirm-btn:active {
  transform: translateY(0);
}

.cart-confirm-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.cart-loading {
  opacity: 0.7;
}

.cart-spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
 
  animation: spin 1s linear infinite;
  margin-right: 8px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}


/* Responsive */
@media (max-width: 768px) {
  .cart-modal {
    width: 90%;        /* en tablet/móvil ocupa casi toda la pantalla */
    max-width: none;   /* quitar límite fijo */
   
  }

  .cart-modal-header {
    padding: 16px;
  }

  .cart-modal-title {
    font-size: 18px;
  }

  .cart-section-title {
    padding: 14px 16px;
    font-size: 15px;
  }

  .cart-table th, .cart-table td {
    padding: 10px 12px;
    font-size: 13px;
  }

  .cart-total-section {
    flex-direction: column;
    text-align: center;
    gap: 16px;
    padding: 16px;
  }

  .cart-total-amount {
    font-size: 24px;
  }

  .cart-confirm-btn {
    width: 100%;
    padding: 14px 20px;
  }
}

@media (max-width: 480px) {
  .cart-modal {
    width: 100%;   /* en móviles pequeños ocupa toda la pantalla */
  }

  .cart-section-title {
    padding: 12px;
    font-size: 14px;
  }

  .cart-table th, .cart-table td {
    padding: 10px;
    font-size: 12px;
  }

  .cart-quantity-input {
    width: 55px;
    padding: 5px;
  }

  .cart-remove-btn {
    width: 26px;
    height: 26px;
    font-size: 13px;
  }

  .cart-total-section {
    padding: 14px;
  }

  .cart-total-amount {
    font-size: 22px;
  }
}

/* Modal */
.cart-modal {

  animation: slideIn 0.4s ease forwards; /* 🔹 animación al abrir */

}

.cart-modal-overlay:not(.active) .cart-modal {
  animation: none; /* evita que se vea raro al cerrarse */
}

/* Animación de entrada */
@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0.6;
  }
  to {
    transform: translateX(0);
    opacity: 1;
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
    </ul>
</nav>
  
            <a class="cart-icon" onclick="openCartModal()">
                <i class="fas fa-shopping-cart"></i>  
                <span id="cantidad-carrito" class="cart-count">0</span>
            </a>
        
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Cart Modal -->
    <div id="cartModal" class="cart-modal-overlay" onclick="closeCartModal(event)">
        <div class="cart-modal" onclick="event.stopPropagation()">
            <div class="cart-modal-header">
                <h2 class="cart-modal-title">Carrito de Compras</h2>
                <button class="cart-modal-close" onclick="closeCartModal()">&times;</button>
            </div>
            <div class="cart-modal-body">
                <!-- Products -->
                <div class="cart-section">
                    <div class="cart-section-title">Productos</div>
                    <div class="cart-table-container">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="datosProductos"></tbody>
                        </table>
                        <div id="emptyProducts" class="cart-empty-state" style="display: none;">
                            <h3>No hay productos</h3>
                            <p>Los productos aparecerán aquí</p>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="cart-total-section">
                    <div class="cart-total-info">
                        <span class="cart-total-label">Total:</span>
                        <span class="cart-total-amount">$<span id="total">0</span></span>
                    </div>
                    <button class="cart-confirm-btn" onclick="enviarCompra()" id="confirmBtn">
                        Confirmar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Soluciones de Seguridad para su Hogar y Negocio</h1>
                    <p>Servicio profesional de cerrajería con más de 15 años de experiencia. Atención 24/7 para emergencias.</p>
                    <div class="hero-buttons">
                        <a href="#contacto" class="btn btn-primary">Solicitar Servicio</a>
                        <a href="tel:+573105648667" class="btn btn-secondary"><i class="fas fa-phone"></i> Llamar Ahora</a>
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

      if (!empty($servicio['precio']) && $servicio['precio'] > 0) {
    $precioTexto = "con precio de $" . number_format($servicio['precio'], 0, ',', '.');
} else {
    $precioTexto = ",";
}

$mensaje = "Hola, soy el usuario *#{$id_usuario}* y estoy interesado en su servicio de {$servicio['nombre']} {$precioTexto}. ¿Podría darme más información?";

        $urlWA = "https://wa.me/573105648667?text=" . urlencode($mensaje);

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
                        <p> Calle 25 Carrera SUR #23-37 Barrio CANAIMA, Neiva</p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Teléfonos</h3>
                        <p><a href="tel:+573105648667">310 564 8667</a></p>
                    
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p><a href="mailto:info@nissicerrajeria.com"> 960donjulio@gmail.com</a></p>
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
                            <a href="https://www.facebook.com/people/Nissi-Cerrajer%C3%ADa/100050027354173/#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/nissi.solution/reel/C_GLsMJu_qy/" class="social-icon"><i class="fab fa-instagram"></i></a>
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
                <a href="tel:+573105648667" class="btn btn-light"><i class="fas fa-phone"></i> Llamar Ahora</a>
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
                    <p><i class="fas fa-phone"></i> 310 564 8667 </p>
                    <p><i class="fas fa-envelope"></i> 960donjulio@gmail.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Calle 25 Carrera SUR #23-37 Barrio CANAIMA, Neiva</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 NISSI Cerrajería. Todos los derechos reservados.</p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/people/Nissi-Cerrajer%C3%ADa/100050027354173/#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/nissi.solution/reel/C_GLsMJu_qy/" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/573105648667?text=Hola%2C%20estoy%20interesado%20en%20sus%20productos%20y%20servicios.%20¿Podría%20darme%20más%20información%3F" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Flotante -->
<a href="https://wa.me/573105648667?text=Hola%2C%20estoy%20interesado%20en%20sus%20productos%20y%20servicios.%20¿Podría%20darme%20más%20información%3F" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

    <!-- JavaScript -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/cliente.js"></script>
    
    <script>
// Modal functions
function openCartModal() {
    const modal = document.getElementById('cartModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    mostrarDatos();
}

function closeCartModal(event) {
    if (event && event.target !== event.currentTarget) return;
    const modal = document.getElementById('cartModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCartModal();
    }
});

// Cart functions
function enviarCompra() {
  Swal.fire({
    title: "¿Confirmar compra?",
    text: "Esta acción no se puede deshacer",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, comprar",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (!result.isConfirmed) return;

    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.innerHTML = '<span class="cart-spinner"></span>Procesando...';
    confirmBtn.disabled = true;

    const productos = JSON.parse(sessionStorage.getItem("productos")) || [];

    // Verificar si hay algo en el carrito
    if (productos.length === 0) {
      Swal.fire({
        title: "Carrito vacío",
        text: "No hay productos en el carrito.",
        icon: "info"
      });
      confirmBtn.innerHTML = 'Confirmar Compra';
      confirmBtn.disabled = false;
      return;
    }

    let total = 0;
    productos.forEach(p => total += Number(p.subtotal));

    const data = {
      total: total,
      productos: productos
    };

    fetch("controlador/carrito_c.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })
      .then(res => res.json())
      .then(respuesta => {
        console.log("Respuesta del servidor:", respuesta);

        if (respuesta.status === "ok") {
          Swal.fire({
            title: "Compra registrada con éxito!",
            icon: "success"
          });

          // Mensaje WhatsApp
          let mensaje = `Hola, soy el usuario ID: ${respuesta.id_usuario}%0A%0A y quiero `;
          mensaje += "*Productos:*%0A";
          productos.forEach(p => {
            mensaje += `- ${p.nombre} x${p.cantidad} %0A`;
          });

          let numero = "573105648667";
          let url = `https://api.whatsapp.com/send?phone=${numero}&text=${mensaje}`;
          window.open(url, "_blank");

          // Limpiar carrito
          sessionStorage.removeItem("productos");
          mostrarDatos();
          cantidadCarro();
          closeCartModal();
        } else {
          Swal.fire({
            title: "Acceso requerido",
            text: "Necesitas iniciar sesión para realizar la compra.",
            icon: "warning"
          }).then(() => {
            window.location.href = "vistas/login.php";
          });
        }
      })
      .catch(error => {
        Swal.fire({
          title: "Error",
          text: "Error al enviar la compra.",
          icon: "error"
        });
        console.error(error);
      })
      .finally(() => {
        confirmBtn.innerHTML = 'Confirmar Compra';
        confirmBtn.disabled = false;
      });
  });
}

function mostrarDatos() {
  let total = 0;
  let productos = JSON.parse(sessionStorage.getItem("productos")) || [];
  let dataP = "";

  const productosTable = document.getElementById("datosProductos");
  const emptyProducts = document.getElementById("emptyProducts");

  if (productos.length > 0) {
    productos.forEach((val) => {
      total += Number(val.subtotal);
      dataP += `
        <tr>
          <td>${val.nombre}</td>
          <td class="cart-price">$${Number(val.precio).toLocaleString()}</td>
          <td>
            <input type="number" class="cart-quantity-input" onChange="cambiarSubtotalP(event, '${val.id}')" value="${val.cantidad}" min="1">
          </td>
          <td class="cart-subtotal">$${Number(val.subtotal).toLocaleString()}</td>
          <td style="text-align: center;">
            <button class="cart-remove-btn" onClick="quitarP(event, '${val.id}')">×</button>
          </td>
        </tr>
      `;
    });
    productosTable.innerHTML = dataP;
    emptyProducts.style.display = 'none';
  } else {
    productosTable.innerHTML = '';
    emptyProducts.style.display = 'block';
  }

  document.getElementById("total").innerHTML = total.toLocaleString();
  document.getElementById('confirmBtn').disabled = total === 0;
}

function cambiarSubtotalP(event, id) {
  let cant = parseInt(event.target.value);
  if (isNaN(cant) || cant < 1) {
    cant = 1;
    event.target.value = 1;
  }

  let prods = JSON.parse(sessionStorage.getItem("productos")) || [];
  let prodsActualizados = prods.map((prod) => {
    if (prod.id == id) {
      let subt = Number(cant) * Number(prod.precio);
      return { ...prod, cantidad: cant, subtotal: subt };
    }
    return prod;
  });

  sessionStorage.setItem("productos", JSON.stringify(prodsActualizados));
  mostrarDatos();
  cantidadCarro();
}

function quitarP(event, id) {
  event.preventDefault();
  let prods = JSON.parse(sessionStorage.getItem("productos"));
  const index = prods.findIndex((prod) => prod.id == id);
  if (index != -1) {
    prods.splice(index, 1);
  }
  sessionStorage.setItem("productos", JSON.stringify(prods));
  mostrarDatos();
  cantidadCarro();
}

// Existing functions
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

Swal.fire({
  position: "top-end",
  icon: "success",
 title: nombre + " ha sido añadido al carrito",
  showConfirmButton: false,
  timer: 1500
});
   
}

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

// Character counter for contact form
document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("message");
  if (input) {
    const counter = document.getElementById("messageCounter");
    const max = input.getAttribute("maxlength");

    input.addEventListener("input", function () {
      const restante = max - input.value.length;
      counter.textContent = `${restante} caracteres restantes`;
    });
  }

  cantidadCarro();
});

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
        // Opcional: notificación visual
Swal.fire({
  position: "top-end",
  icon: "success",
 title: nombre + " ha sido añadido al carrito",
  showConfirmButton: false,
  timer: 1500
});
}

function enviarYRedirigirWhatsApp(formId, urlWA) {
    const usuarioLogueado = <?php echo isset($_SESSION['id_usuario']) ? 'true' : 'false'; ?>;
    
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