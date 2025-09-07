<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include '../../conexion.php';
include '../../modelos/clientes_m.php';

// Total clientes
$totalClientes = contarClientes($conn);

// Configuración de paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$inicio = ($pagina - 1) * $limite;

// Obtener clientes paginados
$usuarios = obtenerUsuariosPaginados($conn, $inicio, $limite);

// Calcular total de páginas
$totalPaginas = ceil($totalClientes / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/clientes.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
       
        /* Estilos adicionales para la tabla de estadísticas */
        .statistics-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 1.5rem 2rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table th {
            background: #f8f9fa;
            padding: 1rem 2rem;
            text-align: left;
            font-weight: 600;
            color: #2a5298;
            border-bottom: 2px solid #e9ecef;
        }

        .stats-table td {
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }

        .stats-table tbody tr:hover {
            background: #f8f9fa;
        }

        .stats-table tbody tr:last-child td {
            border-bottom: none;
        }

        .stat-value {
            font-weight: 600;
            color: #2a5298;
        }

        .stat-currency {
            color: #f1c40f;
            font-weight: 700;
        }

        .stat-icon-cell {
            width: 50px;
            text-align: center;
        }

        .stat-icon-cell i {
            font-size: 1.2rem;
            color: #2a5298;
        }

        .stat-description {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .alert-row {
            background: #fff3cd !important;
        }

        .alert-row td {
            color: #856404;
        }

        .positive-stat {
            color: #28a745;
            font-weight: 600;
        }

        .warning-stat {
            color: #dc3545;
            font-weight: 600;
        }

        /* Added responsive styles for mobile and tablet devices */
        
        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #2a5298;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Responsive breakpoints */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                transform: none;
                transition: all 0.3s ease;
            }
            
            .sidebar.collapsed {
                height: 60px;
                overflow: hidden;
            }
            
            .mobile-menu-toggle {
                display: block;
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 1000;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .logo-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
            }
            
            .menu {
                display: none;
            }
            
            .sidebar:not(.collapsed) .menu {
                display: block;
            }
            
            .sidebar-footer {
                display: none;
            }
            
            .sidebar:not(.collapsed) .sidebar-footer {
                display: block;
            }
        }

        @media (max-width: 768px) {
            /* Stats cards responsive */
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1rem;
                padding: 0 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-info h3 {
                font-size: 0.9rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            /* Top bar responsive */
            .top-bar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-container input {
                width: 100%;
            }
            
            .user-info {
                justify-content: center;
            }
            
            /* Dashboard content */
            .dashboard-content {
                padding: 1rem;
            }
            
            .dashboard-content h1 {
                font-size: 1.8rem;
                margin-bottom: 1.5rem;
            }
            
            /* Statistics table responsive */
            .statistics-table {
                margin-top: 1.5rem;
                overflow-x: auto;
            }
            
            .table-header {
                padding: 1rem;
                font-size: 1rem;
            }
            
            .stats-table {
                min-width: 800px;
            }
            
            .stats-table th,
            .stats-table td {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }
            
            .stat-icon-cell {
                width: 40px;
            }
            
            .stat-icon-cell i {
                font-size: 1rem;
            }
            
            .stat-description {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            /* Extra small screens */
            .stats-container {
                grid-template-columns: 1fr;
                padding: 0 0.5rem;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .dashboard-content {
                padding: 0.5rem;
            }
            
            .dashboard-content h1 {
                font-size: 1.5rem;
                text-align: center;
            }
            
            .table-header {
                padding: 0.75rem;
                font-size: 0.9rem;
                text-align: center;
            }
            
            .stats-table th,
            .stats-table td {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
            
            /* Hide less important columns on very small screens */
            .stats-table th:nth-child(4),
            .stats-table td:nth-child(4) {
                display: none;
            }
            
            .top-bar {
                padding: 0.75rem;
            }
            
            .user span {
                display: none;
            }
        }

        /* Landscape orientation adjustments */
        @media (max-width: 768px) and (orientation: landscape) {
            .sidebar.collapsed {
                height: 50px;
            }
            
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .stat-card {
                padding: 1rem;
            }
        }

        /* Print styles */
        @media print {
            .sidebar,
            .top-bar,
            .mobile-menu-toggle {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .statistics-table {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .stats-container {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
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
                    <li class="active"><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
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
               

                <!-- Resumen de clientes -->
                <div class="clients-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total Clientes</h4>
                     <p class="summary-value"><?php echo $totalClientes; ?></p>
                        </div>
                    </div>
                </div>

            

                <!-- Vista de tabla de clientes -->
                <div class="clients-view table-view active">
                    <div class="table-container">
                        <table class="clients-table">
                            <thead>
                                <tr>
                                   
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Tipo</th>
                                    <th>Fecha de Registro</th>
                                
                                    <th>Estado</th>
                                  
                                </tr>
                            </thead>

<tbody>
     <?php $contador = 1; ?>
<?php foreach ($usuarios as $usuario): ?>
    <tr>
        
        <td>
            <div class="client-info">
                
                <div>
                    <h4><?= htmlspecialchars($usuario['usuario']) ?></h4>
                    <p>ID: USUARIO-<?= htmlspecialchars($usuario['id']) ?></p>
                </div>
            </div>
        </td>
        <td>
            <div class="contact-info">
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($usuario['celular'] ?: 'No definido') ?></p>
                <p><i class="fas fa-user"></i> <?= htmlspecialchars($usuario['correo']) ?></p>
            </div>
        </td>
        <td><?= htmlspecialchars($usuario['rol'] ?: 'Cliente') ?></td>
        
        <td> <?= htmlspecialchars(date("d/m/Y H:i A", strtotime($usuario['fecha_registro']))) ?></td>
        <td><span class="status active">Registrado</span></td>
    </tr>
<?php endforeach; ?>
</tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
    <?php if ($pagina > 1): ?>
        <a class="btn-page" href="?pagina=<?= $pagina - 1 ?>"><i class="fas fa-chevron-left"></i></a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a class="btn-page <?= $i == $pagina ? 'active' : '' ?>" href="?pagina=<?= $i ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagina < $totalPaginas): ?>
        <a class="btn-page" href="?pagina=<?= $pagina + 1 ?>"><i class="fas fa-chevron-right"></i></a>
    <?php endif; ?>
</div>

                </div>

          
               
                 
                       
    <div class="modal-overlay"></div>

   <script src="../../assets/js/admin/clientes.js"></script>
</body>
</html>
