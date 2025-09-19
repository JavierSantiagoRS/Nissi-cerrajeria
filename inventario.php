<?php
session_start();
include 'conexion.php';

 $sql = "SELECT * FROM inventario WHERE estado = 'activo'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NISSI Cerrajería - Productos de Cerrajería Profesional</title>
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
    color: #fff;
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
   width: 100%;  /* en móviles pequeños ocupa toda la pantalla */
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
             <li><a href="index.php#inicio">Inicio</a></li>
                        <li><a href="index.php#servicios">Servicios</a></li>
                        <li><a href="index.php#nosotros">Nosotros</a></li>
                        <li><a href="#galeria"  class="active">Productos</a></li>
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
        
    </div>
</section>

 <script src="assets/js/cliente.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                </script>

                <?php
// Antes de tu loop
$usuario_logueado = isset($_SESSION['usuario']); // Cambia por tu variable real de sesión
?>
<script>
    const usuarioLogueado = <?php echo $usuario_logueado ? 'true' : 'false'; ?>;

    function enviarYRedirigirWhatsApp(formId, urlWA) {
        if (!usuarioLogueado) {
            alert("Debes iniciar sesión para solicitar un servicio.");
            return; // Detiene la función
        }
        document.getElementById(formId).submit();
        window.location.href = urlWA;
    }
</script>
                
    <script>
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

    // Opcional: notificación visual
Swal.fire({
  position: "top-end",
  icon: "success",
 title: nombre + " ha sido añadido al carrito",
  showConfirmButton: false,
  timer: 1500
});
   
}

    </script>

</body>
</html>