<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/pedido_m.php';


$sql_pedidos = "SELECT COUNT(*) AS total FROM pedidos";
$total_pedidos = $conn->query($sql_pedidos)->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/pedido.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.bundle.css">
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
                    <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>

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
               

                <!-- Resumen de clientes -->
                <div class="clients-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total Pedidos</h4>
                    <p class="summary-value"><?php echo $total_pedidos; ?></p>

                        </div>
                    </div>
                </div>

            

                <!-- Vista de tabla de clientes -->
                <div class="clients-view table-view active">
                    <div class="table-container">
                        <table class="clients-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID Pedido</th>
                                    <th>Nombre</th>
                                    <th>Cantidad/Precio</th>
                                    <th>Tipo</th>
                                    <th>Fecha de Pedido</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                  
                                </tr>
                            </thead>
 <?php
$pedidos = obtenerPedidos($conn);

?>
<tbody>
    <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><input type="checkbox" class="client-checkbox"></td>
            <td>
                <div class="client-info">
                    <div>
                        
                        <p><p><?= htmlspecialchars($pedido['codigo_pedido']) ?></p></p>
                    </div>
                    
                </div>
            </td>
            <td>
                <h4><?= htmlspecialchars($pedido['nombre']) ?></h4>
            </td>
            <td>
                <p><i class="fas fa-box"></i> <?= htmlspecialchars($pedido['cantidad']) ?> unidades</p>
                <p><i class="fas fa-dollar-sign"></i> $<?= number_format($pedido['subtotal'], 0, ',', '.') ?></p>
            </td>
            <td><?= htmlspecialchars(ucfirst($pedido['tipo'])) ?></td>
          <td><?= date("d/m/Y H:i A", strtotime($pedido['fecha'])) ?></td>


            <td><span class="status active">Pendiente</span></td>
            <td>
         <a href="../../controlador/pedido_c.php?accion=eliminar&id=<?= $nota['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>


                  
                        </table>
                    </div>
                    <div class="pagination">
                        <button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>
                        <button class="btn-page active">1</button>
                        <button class="btn-page">2</button>
                        <button class="btn-page">3</button>
                        <span class="pagination-dots">...</span>
                        <button class="btn-page">15</button>
                        <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

          
               
                 
                       
    <div class="modal-overlay"></div>

   <script src="../../assets/js/admin/pedido.js"></script>
</body>
</html>
