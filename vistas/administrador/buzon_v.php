<?php
include_once '../../conexion.php';

$mensajes = [];
$sql = "SELECT * FROM contactos ORDER BY id DESC";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $mensajes[] = $fila;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Mensajes - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/buzon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    .sender-avatar.initials {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #4a90e2;
  color: white;
  font-weight: bold;
  font-size: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-footer {
  margin-top: auto;
  padding: 15px;
  text-align: center;
  font-size: 0.8rem;
  background-color: var(--secondary-blue);
}


</style>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                </div>
                <h3>NISSI Cerrajería</h3>
            </div>
            <div class="sidebar-menu">
                <ul>
                <li ><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-boxes"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li class="active"><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-box"></i>Pedidos</a></li>
    <li><a href="venta_v.php"><i class="fas fa-shopping-cart"></i>Ventas</a></li>

                       <li><a href="../../logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
              <div class="sidebar-footer">
                <p>© 2024 NISSI Cerrajería</p>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <!-- Barra superior -->
            <div class="top-bar">
                <div class="toggle-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <div class="user-area">
                    <div class="notifications">
                        <i class="far fa-bell"></i>
                        <span class="notification-count">3</span>
                    </div>
                    <div class="user-profile">
                        <img src="/placeholder.svg?height=40&width=40" alt="Admin">
                        <span>Admin</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Contenedor principal -->
            <div class="content-container">
                <div class="page-header">
                    <h1>Buzón de Mensajes</h1>
                    <div class="header-actions">
                    </div>
                </div>

                <!-- Resumen de mensajes -->
                <div class="messages-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total Mensajes</h4>
                           <p class="summary-value"><?= count($mensajes) ?></p>

                        </div>
                    </div>
                   
                </div>

             

                <!-- Lista de mensajes -->
             <?php foreach ($mensajes as $mensaje): ?>
<div class="message-item unread" data-id="<?= $mensaje['id'] ?>">
  
    <div class="message-sender">
    <div class="sender-avatar initials">
        <?= strtoupper(substr($mensaje['nombre'], 0, 1)) ?>
    </div>
    <div class="sender-info">
        <h4><?= htmlspecialchars($mensaje['nombre']) ?></h4>
        <p><?= htmlspecialchars($mensaje['mail']) ?></p>
    </div>
</div>

    <div class="message-content">
        <div class="message-subject">
            <span class="category-tag general"><?= htmlspecialchars($mensaje['servicio']) ?></span>
   
        </div>
        <div class="message-preview">
            <?= htmlspecialchars(mb_strimwidth($mensaje['mensaje'], 0, 80, "...")) ?>
        </div>
    </div>
    <div class="message-meta">
        <div class="message-date"><?= date("d/m/Y H:i", strtotime($mensaje['created_at'] ?? 'now')) ?></div>
        <div class="message-status">
            <span class="status nuevo">Fecha</span>
        </div>
    </div>
    <div class="message-actions">
       <a 
  href="https://wa.me/57<?= $mensaje['celular'] ?>?text=Hola%20<?= urlencode($mensaje['nombre']) ?>,%20somos%20Nissi%20Cerrajeria,%20recibimos%20tu%20mensaje%20sobre%20'<?= urlencode($mensaje['servicio']) ?>'.%20Gracias%20por%20contactarnos." 
  target="_blank" 
  class="btn-icon" 
  title="Responder por WhatsApp"
>
  <i class="fab fa-whatsapp"></i>
</a>

<a 
  href="../../controlador/buzon_c.php?accion=eliminar&id=<?= $mensaje['id'] ?>" 
  onclick="return confirmarEliminar(event, this)" 
  class="btn-icon" 
  title="Eliminar"
>
  <i class="fas fa-trash-alt"></i>
</a>



    </div>
</div>
<?php endforeach; ?>

            </div>
        </main>
    </div>



   
    <div class="modal-overlay"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/admin/buzon.js"></script>
<script>
  function confirmarEliminar(e, el) {
    // Evita abrir el modal del mensaje (si el contenedor tiene click)
    e.preventDefault();
    e.stopPropagation();

    // Si por alguna razón SweetAlert no cargó, usa confirm nativo (fallback)
    if (!window.Swal) {
      return confirm('¿Eliminar este mensaje?');
    }

    Swal.fire({
      title: "¿Eliminar este mensaje?",
      text: "Esta acción no se puede deshacer",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirige a tu PHP tal cual lo hacías
        window.location.href = el.href;
      }
    });

    // Impide que el enlace navegue mientras decides en el SweetAlert
    return false;
  }
</script>

</body>
</html>
