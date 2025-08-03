<?php


session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include '../../conexion.php';

// Consultar totales reales
$sql_inventario = "SELECT SUM(contenido) AS total FROM inventario";
$total_inventario = $conn->query($sql_inventario)->fetch_assoc()['total'] ?? 0;


// Total clientes
$sql_clientes = "SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'cliente'";
$total_clientes = $conn->query($sql_clientes)->fetch_assoc()['total'];

// Total servicios
$sql_servicios = "SELECT COUNT(*) AS total FROM servicios";
$total_servicios = $conn->query($sql_servicios)->fetch_assoc()['total'];

// Total mensajes
$sql_mensajes = "SELECT COUNT(*) AS total FROM contactos";
$total_mensajes = $conn->query($sql_mensajes)->fetch_assoc()['total'];

// Valor de servicios
$sql_valor_servicios = "SELECT SUM(precio) AS total FROM servicios";
$valor_servicios = $conn->query($sql_valor_servicios)->fetch_assoc()['total'] ?? 0;

// Valor del inventario
$sql_valor_inventario = "SELECT SUM(precio) AS total FROM inventario";
$valor_inventario = $conn->query($sql_valor_inventario)->fetch_assoc()['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-container">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo.jpg-mzASpdnX9njoa03CVf1OthnLqW4StV.jpeg" alt="NISSI Cerrajería" class="logo">
            </div>
            <nav class="menu">
                <ul>
                    <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>
    <li><a href="venta_v.php"><i class="fas fa-tools"></i>Ventas</a></li>
<li> <a href="pendientes_v.php"><i class="fas fa-tools"></i>Ventas Pendientes</a></li>
  <li> <a href="confirmadas_v.php"><i class="fas fa-tools"></i>Ventas Confirmadas</a></li>
    <li> <a href="canceladas_v.php"><i class="fas fa-tools"></i>Ventas Canceladas</a></li>
                      <li><a href="../../index.php">Salir</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <p>© 2024 NISSI Cerrajería</p>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="search-container">
                    <input type="text" placeholder="Buscar...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="user-info">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="user">
                        <span>Admin</span>
                        <img src="/placeholder.svg?height=40&width=40" alt="Usuario">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>

            <div class="dashboard-content" id="dashboard">
                <h1>Dashboard</h1>
                <div class="stats-container">
                 
                 <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon inventory">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Inventario</h3>
                           <p class="stat-number"><?php echo $total_inventario; ?></p>

                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon messages">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Mensajes</h3>
                            <p class="stat-number"><?php echo $total_mensajes; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon services">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Servicios</h3>
                            <p class="stat-number"><?php echo $total_servicios; ?></p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon value">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Valor de los Servicios</h3>
                           <p class="stat-number"><?php echo "$" . number_format($valor_servicios); ?> COP</p>
                        </div>
                    </div>
                      <div class="stat-card">
                        <div class="stat-icon value">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Valor del inventario</h3>
                           <p class="stat-number"><?php echo "$" . number_format($valor_inventario); ?> COP</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon services">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total clientes</h3>
                         <p class="stat-number"><?php echo $total_clientes; ?></p>
                        </div>
                    </div>
                </div>

                </div>

               
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/js/admin/admin.js"></script>
</body>
</html>