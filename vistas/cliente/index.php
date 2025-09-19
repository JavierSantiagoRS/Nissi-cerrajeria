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
    <link rel="../../assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
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


.cart-icon {
  position: relative;
  color: var(--primary-blue);
  font-size: 1.3rem;
  transition: color 0.3s;
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
                          <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
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
        <a href="tel:+5731056486676" class="btn btn-outline"><i class="fas fa-phone"></i> Llamar Ahora</a>
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
                  <td>
    <?php if (!empty($servicio['precio'])): ?>
        <?= number_format($servicio['precio'], 0, ',', '.') ?> COP
    <?php else: ?>
        <em>Precio a convenir en chat</em>
    <?php endif; ?>
</td>
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
     <script src="../../assets/js/cliente.js"></script>
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

                    document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.querySelector(".mobile-menu-btn");
  const nav = document.querySelector(".main-nav ul");

  menuBtn.addEventListener("click", () => {
    nav.classList.toggle("active");
  });
});

                </script>
                
</body>
</html>