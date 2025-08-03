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
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería">
                </div>
                <h3>NISSI Cerrajería</h3>
            </div>
            <div class="sidebar-menu">
                <ul>
                <li ><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li class="active"><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>
    <li><a href="venta_v.php"><i class="fas fa-tools"></i>Ventas</a></li>
    <li> <a href="pendientes_v.php"><i class="fas fa-tools"></i>Ventas Pendientes</a></li>
        <li> <a href="confirmadas_v.php"><i class="fas fa-tools"></i>Ventas Confirmadas</a></li>
         <li> <a href="canceladas_v.php"><i class="fas fa-tools"></i>Ventas Canceladas</a></li>
                      <li><a href="../../index.php">Salir</a></li>
                </ul>
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
        <div class="sender-avatar">
            <img src="/placeholder.svg?height=40&width=40" alt="<?= htmlspecialchars($mensaje['nombre']) ?>">
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
  href="https://wa.me/57<?= $mensaje['celular'] ?>?text=Hola%20<?= urlencode($mensaje['nombre']) ?>,%20recibimos%20tu%20mensaje%20sobre%20'<?= urlencode($mensaje['servicio']) ?>'.%20Gracias%20por%20contactarnos." 
  target="_blank" 
  class="btn-icon" 
  title="Responder por WhatsApp"
>
  <i class="fab fa-whatsapp"></i>
</a>

       <a 
  href="../../controlador/buzon_c.php?accion=eliminar&id=<?= $mensaje['id'] ?>" 
  onclick="return confirm('¿Eliminar este mensaje?');" 
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

    <script src="../../assets/js/admin/buzon.js"></script>
 

</body>
</html>
