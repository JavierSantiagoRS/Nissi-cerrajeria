<?php
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
    <title>Cerradura Digital Inteligente Pro - NISSI Cerrajería</title>
 <link rel="stylesheet" href="assets/css/producto.css">
     <link rel="stylesheet" href="assets/css/cliente.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="search-toggle">
                        <i class="fas fa-search"></i>
                    </div>
                    <a href="#" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                    <div class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>


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
  <input id="code" type="hidden" name="codigo_pedido">
    <input type="hidden" name="tipo" value="producto"> <!-- o 'servicio' según el caso -->
    <input type="hidden" name="precio" value="<?= $row['precio'] ?>">

    <div class="price-summary">
        <span class="total-label">Total:</span>
        <span class="total-price" data-precio="<?= $row['precio'] ?>">
            $<?= number_format($row['precio'], 0, ',', '.') ?> COP
        </span>
    </div>

    <div class="action-buttons">
        <button type="button" class="btn btn-primary add-to-cart">
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
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                    <p>Tu seguridad es nuestra prioridad. Especialistas en cerrajería con más de 15 años de experiencia.</p>
                </div>
                <div class="footer-links">
                    <h3>Enlaces rápidos</h3>
                    <ul>
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Servicios</a></li>
                        <li><a href="#inventarios">Productos</a></li>
                        <li><a href="#sobre-nosotros">Sobre nosotros</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-services">
                    <h3>Servicios</h3>
                    <ul>
                        <li><a href="#">Cerrajería 24h</a></li>
                        <li><a href="#">Instalación de cerraduras</a></li>
                        <li><a href="#">Duplicado de llaves</a></li>
                        <li><a href="#">Sistemas de seguridad</a></li>
                        <li><a href="#">Reparaciones</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>Contacto</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Calle Principal 123, Madrid</p>
                    <p><i class="fas fa-phone"></i> +34 900 123 456</p>
                    <p><i class="fas fa-envelope"></i> info@nissicerrajeria.com</p>
                    <p><i class="fas fa-clock"></i> 24/7 Emergencias</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 NISSI Cerrajería. Todos los derechos reservados.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/573176039806?text=Hola%2C%20estoy%20interesado%20en%20sus%20servicios.%20¿Podría%20darme%20más%20información%3F" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>



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
  const addToCartBtn = document.querySelector(".add-to-cart");
  const buyNowBtn = document.querySelector("#buyNowBtn");
  const qtyBtns = document.querySelectorAll(".qty-btn");
  const cartCount = document.querySelector(".cart-count");

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

  // Añadir al carrito (opcional visual)
  addToCartBtn?.addEventListener("click", () => {
    const cantidad = parseInt(quantityInput.value || 1);
    const current = parseInt(cartCount?.textContent || 0);
    if (cartCount) cartCount.textContent = current + cantidad;
    alert(`Se añadieron ${cantidad} productos al carrito.`);
  });

  // Comprar ahora
  buyNowBtn?.addEventListener("click", function () {
    const form = document.getElementById("form-compra");
    const formData = new FormData(form);

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
  });

  // Cálculo inicial
  updatePrice();
});
</script>


  <script src="assets/js/producto.js">
  </script>
</body>
</html>