<?php
session_start();

include 'conexion.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM inventario WHERE id = $id";
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NISSI Cerrajería - Cerrajería Profesional</title>
         <link rel="assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
 <link rel="stylesheet" href="assets/css/producto.css">
     <link rel="stylesheet" href="assets/css/cliente.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
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
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="index.php#servicios">Servicios</a></li>
                        <li><a href="#inventarios" class="active">Productos</a></li>
                        <li><a href="index.php#nosotros">Nosotros</a></li>
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
                    </ul>
                </nav>
                <div class="header-actions">
                 
                  <a class="cart-icon" onclick="openCartModal()">
                <i class="fas fa-shopping-cart"></i>  
                <span id="cantidad-carrito" class="cart-count">0</span>
            </a>
                    <div class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </div>
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


    <!-- Product Detail Page -->
    <main class="pdp-container">
        <div class="container">
            <div class="pdp-layout">
                <!-- Product Gallery -->
                <section class="product-gallery">
    <div class="gallery-main">
        <div class="main-image-container">
            <?php if ($result && $row = $result->fetch_assoc()) : ?>
                <img id="mainImage" 
                     src="assets/<?= htmlspecialchars($row['imagen']) ?>" 
                     alt="<?= htmlspecialchars($row['titulo']) ?>" />

                <button class="zoom-btn" aria-label="Ampliar imagen">
                    <i class="fas fa-expand"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>


                <!-- Product Information -->
                <section class="product-info">
                    <div class="product-header">
                        <div class="product-meta">
                            <span class="brand">NISSI CERRAJERIA</span>
                        </div>
                        <h1 class="product-title"><?= htmlspecialchars($row['titulo']) ?></h1>

                    </div>

                    <div class="pricing">
                        <div class="price-main">
                          <span class="current-price">$<?= number_format($row['precio'], 0, ',', '.') ?> COP</span>
                        </div>
                    </div>

                    <div class="stock-info">
                        <div class="availability in-stock">
                            <i class="fas fa-check-circle"></i>
                            <span>En stock - <?= nl2br(htmlspecialchars($row['contenido'])) ?> unidades disponibles</span>
                        </div>
                        <div class="delivery-info">
                            <i class="fas fa-truck"></i>
                            <span>Envío gratuito - Entrega en 24-48h</span>
                        </div>
                    </div>

                    <div class="product-description">
                        <p><?= htmlspecialchars($row['descripcion']) ?></p>
                    </div>

                    <div class="key-features">
                        <h3>Características destacadas:</h3>
                        <ul>
                            <li><i class="fas fa-fingerprint"></i> Sensor biométrico de alta precisión</li>
                            <li><i class="fas fa-wifi"></i> Conectividad WiFi y control remoto</li>
                            <li><i class="fas fa-battery-three-quarters"></i> Batería de larga duración (12 meses)</li>
                            <li><i class="fas fa-shield-alt"></i> Resistente al agua IP65</li>
                        </ul>
                    </div>

                    <!-- Product Options -->
             <form id="form-compra" action="controlador/pedido_c.php?accion=crear" method="POST" enctype="multipart/form-data">
    <div class="product-options">
        <div class="option-group">
            <label for="quantity" class="option-label">Cantidad:</label>
            <div class="quantity-selector">
                <button type="button" class="qty-btn minus" aria-label="Disminuir cantidad">-</button>
                <input 
                    type="number" 
                    id="quantity" 
                    name="cantidad" 
                    value="1" 
                    min="1" 
                    max="<?= $row['contenido'] ?>" 
                    data-stock="<?= $row['contenido'] ?>" 
                    aria-label="Cantidad">
                <button type="button" class="qty-btn plus" aria-label="Aumentar cantidad">+</button>
            </div>
        </div>
    </div>

    <!-- Campos ocultos requeridos por pedidos -->
    <input type="hidden" name="nombre" value="<?= htmlspecialchars($row['titulo']) ?>">
    <input type="hidden" name="id_usuario" value="<?= isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : '' ?>">
    <input type="hidden" name="tipo" value="producto"> <!-- o 'servicio' según el caso -->
    <input type="hidden" name="precio" value="<?= $row['precio'] ?>">

    <div class="price-summary">
        <span class="total-label">Total:</span>
        <span class="total-price" data-precio="<?= $row['precio'] ?>">
            $<?= number_format($row['precio'], 0, ',', '.') ?> COP
        </span>
    </div>

    <div class="action-buttons">
        <button type="button" class="btn btn-primary add-to-cart"
        onclick="agregarProducto(event, '<?php echo $row['id']; ?>', '<?php echo $row['titulo']; ?>', '<?php echo $row['precio']; ?>')">
    <i class="fas fa-shopping-cart"></i> Añadir al carrito
</button>


        <button type="button" class="btn btn-secondary buy-now" id="buyNowBtn">
            <i class="fab fa-whatsapp"></i> Comprar ahora
        </button>
    </div>

    <!-- Mensaje de respuesta AJAX -->
    <div id="mensaje" style="margin-top: 10px; color: green;"></div>
</form>




                        <div class="secondary-actions">
                            <button class="share-btn" aria-label="Compartir inventario">
                                <i class="fas fa-share-alt"></i>
                                <span>Compartir</span>
                            </button>
                        </div>
                    </div>

                    <!-- Trust Signals -->
                    <div class="trust-signals">
                        <div class="trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Garantía 2 años</strong>
                                <span>Cobertura completa</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Envío gratuito</strong>
                                <span>En pedidos +€100</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-undo"></i>
                            <div>
                                <strong>Devolución 30 días</strong>
                                <span>Sin preguntas</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>Soporte 24/7</strong>
                                <span>Asistencia técnica</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Image Zoom Modal -->
    <div id="imageZoomModal" class="zoom-modal">
        <div class="zoom-modal-content">
            <button class="zoom-close" aria-label="Cerrar zoom">&times;</button>
            <img id="zoomedImage" src="/placeholder.svg" alt="Imagen ampliada">
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                         <img src="assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                    <p>Tu seguridad es nuestra prioridad. Especialistas en cerrajería con más de 15 años de experiencia.</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces rápidos</h3>
                    <ul>
                        <li><a href="index,php#inicio">Inicio</a></li>
                        <li><a href="index.php#servicios">Servicios</a></li>
                        <li><a href="index.php#inventarios">Productos</a></li>
                        <li><a href="index.php#sobre-nosotros">Sobre nosotros</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
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

    <!-- WhatsApp Float -->
    <a href="https://wa.me/573105648667?text=Hola%2C%20estoy%20interesado%20en%20sus%20productos%20y%20servicios.%20¿Podría%20darme%20más%20información%3F" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>


 <script src="assets/js/cliente.js"></script>
<script>
   
  fetch(form.action, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("mensaje").innerText = data;
    })
    .catch((error) => {
      document.getElementById("mensaje").innerText =
        "Error al registrar el pedido.";
      console.error(error);
    });


</script>
  <script>
document.addEventListener("DOMContentLoaded", function () {
  const quantityInput = document.getElementById("quantity");
  const totalPriceElement = document.querySelector(".total-price");
  const precioUnitario = parseFloat(totalPriceElement?.getAttribute("data-precio") || 0);

  const buyNowBtn = document.querySelector("#buyNowBtn");
  const qtyBtns = document.querySelectorAll(".qty-btn");
  

  // Actualiza el total según cantidad
  function updatePrice() {
    const cantidad = parseInt(quantityInput?.value || 1);
    const total = precioUnitario * cantidad;
    totalPriceElement.textContent = `$${total.toLocaleString("es-CO", {
      minimumFractionDigits: 0,
    })} COP`;
  }

  // Botones + y -
  qtyBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const isPlus = btn.classList.contains("plus");
      let cantidad = parseInt(quantityInput.value);
      const min = parseInt(quantityInput.min);
      const max = parseInt(quantityInput.max);

      if (isPlus && cantidad < max) {
        quantityInput.value = cantidad + 1;
      } else if (!isPlus && cantidad > min) {
        quantityInput.value = cantidad - 1;
      }
      updatePrice();
    });
  });

  // Cambio manual en input
  quantityInput?.addEventListener("change", () => {
    let val = parseInt(quantityInput.value);
    const min = parseInt(quantityInput.min);
    const max = parseInt(quantityInput.max);

    if (val < min) val = min;
    if (val > max) val = max;
    quantityInput.value = val;
    updatePrice();
  });


// Comprar ahora
buyNowBtn?.addEventListener("click", function () {
  const form = document.getElementById("form-compra");
  const formData = new FormData(form);

  fetch(form.action, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      try {
        return response.json(); // Asegura que sea JSON
      } catch (e) {
        throw new Error("Respuesta no válida");
      }
    })
    .then((data) => {
      if (data.success) {
        const cantidad = document.getElementById("quantity").value;
        const nombre = form.querySelector('input[name="nombre"]').value;
        const precio = form.querySelector('input[name="precio"]').value;
        const tipo = form.querySelector('input[name="tipo"]').value;
        const id_usuario = form.querySelector('input[name="id_usuario"]')?.value || "no identificado";
        const total = cantidad * precio;

        const mensaje = `Hola, soy el usuario *#${id_usuario}* y quiero comprar *${cantidad} ${nombre}* (${tipo}) por un total de *$${total.toLocaleString('es-CO')} COP*`;
        const telefono = "573105648667"; // <-- cambia por tu número real

        const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
        window.location.href = url;
      } else {
             Swal.fire({
  title: "Necesitas iniciar sesión para realizar la compra.",
  icon: "success",
  draggable: true
});
            window.location.href = "vistas/login.php"; 
      }
    })
    .catch((error) => {
      console.error("Error en la petición:", error);
     
       Swal.fire({
  title: "Necesitas iniciar sesión para realizar la compra.",
  icon: "success",
  draggable: true
});
          window.location.href = "vistas/login.php"; 
    });
});




  // Cálculo inicial
  updatePrice();
});
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
function agregarProducto(event, id, nombre, precio) {
    event.preventDefault();

    let productos = JSON.parse(sessionStorage.getItem("productos"));
    if (!productos) productos = [];

    let existe = productos.some(p => parseInt(p.id) === parseInt(id));
    if (existe) {
   
        Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "¡Este producto ya está en el carrito!",
});
        return;
    }

    const cantidad = parseInt(document.getElementById("quantity").value);
    const subtotal = precio * cantidad;

    const producto = {
        id: parseInt(id),
        nombre: nombre,
        cantidad: cantidad,
        precio: parseFloat(precio),
        subtotal: subtotal
    };

    productos.push(producto);
    sessionStorage.setItem("productos", JSON.stringify(productos));

    // ✅ Llamar a la función para actualizar la vista del carrito
    cantidadCarro();

    // ❌ Solo si realmente necesitas recargar (por ejemplo, si hay cambios visuales fuera del contador)
    // window.location.reload();
}

</script>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/producto.js">
  </script>
</body>
</html>