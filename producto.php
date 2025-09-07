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
                 
                  <a class="cart-icon" href="carrito.php"> <i class="fas fa-shopping-cart"></i>  <span id="cantidad-carrito" class="cart-count">0</span></a>
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
        const telefono = "573001112233"; // <-- cambia por tu número real

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



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/producto.js">
  </script>
</body>
</html>