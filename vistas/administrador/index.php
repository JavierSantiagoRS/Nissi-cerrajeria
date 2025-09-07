<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include "../../conexion.php"; // Ajusta la ruta según tu estructura






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
$sql_valor_inventario = "SELECT SUM(precio*contenido) AS total FROM inventario";
$valor_inventario = $conn->query($sql_valor_inventario)->fetch_assoc()['total'] ?? 0;

// Estadísticas adicionales para la tabla
$sql_ventas_mes = "SELECT COUNT(*) as total FROM ventas WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND estado = 'confirmada'";
$ventas_mes = $conn->query($sql_ventas_mes)->fetch_assoc()['total'] ?? 0;

$sql_ventas_total = "SELECT COUNT(*) as total FROM ventas WHERE estado = 'confirmada'" ;
$ventas_total = $conn->query($sql_ventas_total)->fetch_assoc()['total'] ?? 0;

$sql_productos_bajo_stock = "SELECT COUNT(*) as total FROM inventario WHERE contenido < 10";
$productos_bajo_stock = $conn->query($sql_productos_bajo_stock)->fetch_assoc()['total'] ?? 0;

$sql_ingresos_mes = "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE()) AND estado = 'confirmada'";
$ingresos_mes = $conn->query($sql_ingresos_mes)->fetch_assoc()['total'] ?? 0;


$sql_ingresos_total = "SELECT SUM(total) as total FROM ventas WHERE estado = 'confirmada'";
$ingresos_total = $conn->query($sql_ingresos_total)->fetch_assoc()['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Estilos para gráficas interactivas */
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(35, 69, 128, 0.1);
            padding: 2rem;
            border: 1px solid rgba(35, 69, 128, 0.1);
        }

        .chart-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            color: #234580;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .chart-header i {
            font-size: 1.3rem;
            color: #234580;
        }

        .chart-canvas {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .analytics-summary {
            background: linear-gradient(135deg, #234580 0%, #1a3660 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 20px rgba(35, 69, 128, 0.2);
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .analytics-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .analytics-item h4 {
            margin: 0 0 0.5rem 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .analytics-item .value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .trend-up {
            color: #4ade80;
        }

        .trend-down {
            color: #f87171;
        }

        .export-controls {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .export-btn {
            background: #234580;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .export-btn:hover {
            background: #1a3660;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(35, 69, 128, 0.3);
        }

        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .chart-card {
                padding: 1.5rem;
            }

            .chart-canvas {
                height: 250px;
            }

            .analytics-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .export-controls {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <aside class="sidebar collapsed" id="sidebar">
            <div class="logo-container">
                <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería" class="logo">
                <!-- Added mobile menu toggle button -->
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="menu">
                <ul>
                    <li class="active"><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-boxes"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                    <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
                    <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
                    <li><a href="pedido_v.php"><i class="fas fa-box"></i>Pedidos</a></li>
                    <li><a href="venta_v.php"><i class="fas fa-shopping-cart"></i>Ventas</a></li>

                    <li><a href="../../logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <p>© 2024 NISSI Cerrajería</p>
            </div>
        </aside>

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
                            <p class="stat-number"><?php echo "" . number_format($valor_servicios); ?> COP</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon value">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Valor del inventario</h3>
                            <p class="stat-number"><?php echo "" . number_format($valor_inventario);?> COP</p>
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

                <!-- Añadiendo sección de gráficas interactivas -->
                <div class="charts-container">
                    <div class="chart-card">
                        <div class="chart-header">
                            <i class="fas fa-chart-bar"></i>
                            Comparativa de Valores
                        </div>
                        <div class="chart-canvas">
                            <canvas id="valuesChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <i class="fas fa-chart-pie"></i>
                            Distribución de Recursos
                        </div>
                        <div class="chart-canvas">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    </div>
                </div>

               

                  <div class="chart-card">
    <div class="chart-header">
        <i class="fas fa-chart-line"></i>
        Tendencia de Visitas
    </div>   
    <div class="filter-section">
        <form id="formFechas">
            <label>Desde: <input type="date" name="inicio" required></label>
            <label>Hasta: <input type="date" name="fin" required></label>
            <button type="submit">Filtrar</button>
        </form>
    </div>
    <div class="chart-canvas">
        <canvas id="revenueChart"></canvas>
    </div>
</div>



                <div class="statistics-table">
                    <div class="table-header">
                        <i class="fas fa-chart-bar"></i> Estadísticas Detalladas del Sistema
                    </div>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Métrica</th>
                                <th>Valor Actual</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-boxes"></i></td>
                                <td><strong>Total de Inventario</strong></td>
                                <td class="stat-value"><?php echo number_format($total_inventario); ?> unidades</td>
                                <td class="stat-description">Cantidad total de productos en stock</td>
                                <td><span class="positive-stat">Disponible</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-users"></i></td>
                                <td><strong>Clientes Registrados</strong></td>
                                <td class="stat-value"><?php echo number_format($total_clientes); ?> clientes</td>
                                <td class="stat-description">Total de usuarios con rol de cliente</td>
                                <td><span class="positive-stat">Activo</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-tools"></i></td>
                                <td><strong>Servicios</strong></td>
                                <td class="stat-value"><?php echo number_format($total_servicios); ?> servicios</td>
                                <td class="stat-description">Cantidad de servicios ofrecidos</td>
                                <td><span class="positive-stat">Operativo</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-envelope"></i></td>
                                <td><strong>Mensajes Recibidos</strong></td>
                                <td class="stat-value"><?php echo number_format($total_mensajes); ?> mensajes</td>
                                <td class="stat-description">Total de contactos en el buzón</td>
                                <td><span class="positive-stat">Pendiente</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-chart-line"></i></td>
                                <td><strong>Ventas confirmadas/Mes</strong></td>
                                <td class="stat-value"><?php echo number_format($ventas_mes); ?> ventas</td>
                                <td class="stat-description">Ventas realizadas en el mes actual</td>
                                <td><span class="positive-stat">En progreso</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-shopping-cart"></i></td>
                                <td><strong>Total Ventas Confirmadas</strong></td>
                                <td class="stat-value"><?php echo number_format($ventas_total); ?> ventas</td>
                                <td class="stat-description">Ventas históricas totales</td>
                                <td><span class="positive-stat">Completado</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-dollar-sign"></i></td>
                                <td><strong>Valor Total de Servicios</strong></td>
                                <td class="stat-currency">$<?php echo number_format($valor_servicios); ?> COP</td>
                                <td class="stat-description">Valor monetario de todos los servicios</td>
                                <td><span class="positive-stat">Valorado</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-warehouse"></i></td>
                                <td><strong>Valor del Inventario</strong></td>
                                <td class="stat-currency">$<?php echo number_format($valor_inventario); ?> COP</td>
                                <td class="stat-description">Valor monetario total del inventario</td>
                                <td><span class="positive-stat">Valorado</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-money-bill-wave"></i></td>
                                <td><strong>Ingresos del Mes</strong></td>
                                <td class="stat-currency">$<?php echo number_format($ingresos_mes); ?> COP</td>
                                <td class="stat-description">Ingresos generados en el mes actual</td>
                                <td><span class="positive-stat">Activo</span></td>
                            </tr>
                            <tr>
                                <td class="stat-icon-cell"><i class="fas fa-piggy-bank"></i></td>
                                <td><strong>Ingresos Totales</strong></td>
                                <td class="stat-currency">$<?php echo number_format($ingresos_total); ?> COP</td>
                                <td class="stat-description">Ingresos históricos totales</td>
                                <td><span class="positive-stat">Acumulado</span></td>
                            </tr>
                            <?php if ($productos_bajo_stock > 0): ?>
                            <tr class="alert-row">
                                <td class="stat-icon-cell"><i class="fas fa-exclamation-triangle"></i></td>
                                <td><strong>Productos Bajo Stock</strong></td>
                                <td class="warning-stat"><?php echo number_format($productos_bajo_stock); ?> productos</td>
                                <td class="stat-description">Productos con menos de 10 unidades</td>
                                <td><span class="warning-stat">Atención Requerida</span></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Added JavaScript for mobile menu toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.querySelector('.mobile-menu-toggle i');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-bars';
            } else {
                icon.className = 'fas fa-times';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                document.querySelector('.mobile-menu-toggle i').className = 'fas fa-bars';
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('collapsed');
            } else {
                sidebar.classList.add('collapsed');
            }
        });
    </script>
    

    <!-- Añadiendo script para gráficas interactivas -->
    <script>
        // Datos PHP para JavaScript
        const statsData = {
            totalInventario: <?php echo $total_inventario; ?>,
            totalClientes: <?php echo $total_clientes; ?>,
            totalServicios: <?php echo $total_servicios; ?>,
            totalMensajes: <?php echo $total_mensajes; ?>,
            valorServicios: <?php echo $valor_servicios; ?>,
            valorInventario: <?php echo $valor_inventario; ?>,
            ventasMes: <?php echo $ventas_mes; ?>,
            ventasTotal: <?php echo $ventas_total; ?>,
            ingresosMes: <?php echo $ingresos_mes; ?>,
            ingresosTotal: <?php echo $ingresos_total; ?>,
            productosBajoStock: <?php echo $productos_bajo_stock; ?>
        };

        // Configuración de colores basada en #234580
        const chartColors = {
            primary: '#234580',
            secondary: '#1a3660',
            accent: '#3d5aa0',
            light: '#e8f0ff',
            success: '#4ade80',
            warning: '#f59e0b',
            danger: '#ef4444'
        };

        // Gráfica de Comparativa de Valores
        const valuesCtx = document.getElementById('valuesChart').getContext('2d');
        new Chart(valuesCtx, {
            type: 'bar',
            data: {
                labels: ['Valor Servicios', 'Valor Inventario', 'Ingresos Mes', 'Ingresos Total'],
                datasets: [{
                    label: 'Valores en COP',
                    data: [statsData.valorServicios, statsData.valorInventario, statsData.ingresosMes, statsData.ingresosTotal],
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.accent,
                        chartColors.light
                    ],
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Gráfica de Distribución de Recursos
        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Inventario', 'Clientes', 'Servicios', 'Mensajes'],
                datasets: [{
                    data: [statsData.totalInventario, statsData.totalClientes, statsData.totalServicios, statsData.totalMensajes],
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.accent,
                        chartColors.warning
                    ],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let chart;

    function cargarDatos(inicio, fin) {
        fetch(`../../modelos/visitas_m.php?inicio=${inicio}&fin=${fin}`)
        .then(res => res.json())
        .then(data => {
            const horas = Array.from({length: 24}, (_, i) => i + ":00");
            const datasets = [];
            let colors = ["#234580", "#f0ad4e", "#5cb85c", "#d9534f", "#5bc0de"];
            let i = 0;

            for (const [dia, valores] of Object.entries(data)) {
                datasets.push({
                    label: dia,
                    data: valores,
                    borderColor: colors[i % colors.length],
                    backgroundColor: colors[i % colors.length] + "33",
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors[i % colors.length],
                    pointBorderColor: "#fff",
                    pointRadius: 5
                });
                i++;
            }

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: horas,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: value => value + " visitas" }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Error al obtener datos:', err));
    }

    // --- Cargar automáticamente HOY ---
   const hoy = new Date();
const yyyy = hoy.getFullYear();
const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
const dd = String(hoy.getDate()).padStart(2, '0');
const fechaHoy = `${yyyy}-${mm}-${dd}`;

    // Actualizamos también los inputs del formulario con hoy
document.querySelector('input[name="inicio"]').value = fechaHoy;
document.querySelector('input[name="fin"]').value = fechaHoy;

cargarDatos(fechaHoy, fechaHoy);

    // --- Capturar envío de formulario ---
    document.getElementById('formFechas').addEventListener('submit', e => {
        e.preventDefault();
        const inicio = e.target.inicio.value;
        const fin = e.target.fin.value;
        cargarDatos(inicio, fin);
    });
});





        // Gráfica de Análisis de Inventario
        const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(inventoryCtx, {
            type: 'radar',
            data: {
                labels: ['Stock Total', 'Valor Inventario', 'Rotación', 'Disponibilidad', 'Eficiencia'],
                datasets: [{
                    label: 'Métricas de Inventario',
                    data: [
                        Math.min(statsData.totalInventario / 100, 100),
                        Math.min(statsData.valorInventario / 100000, 100),
                        75, // Simulado
                        85, // Simulado
                        90  // Simulado
                    ],
                    backgroundColor: chartColors.light,
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Calcular métricas avanzadas
        function calculateAdvancedMetrics() {
            const roi = statsData.valorInventario > 0 ? 
                ((statsData.ingresosTotal / statsData.valorInventario) * 100).toFixed(1) : 0;
            
            const efficiency = statsData.totalServicios > 0 ? 
                ((statsData.ventasTotal / statsData.totalServicios) * 100).toFixed(1) : 0;
            
            const avgSale = statsData.ventasTotal > 0 ? 
                (statsData.ingresosTotal / statsData.ventasTotal).toFixed(0) : 0;
            
            const stockRotation = statsData.totalInventario > 0 ? 
                (statsData.ventasTotal / statsData.totalInventario * 12).toFixed(1) : 0;

            document.getElementById('roiValue').textContent = roi + '%';
            document.getElementById('efficiencyValue').textContent = efficiency + '%';
            document.getElementById('avgSaleValue').textContent = '$' + parseInt(avgSale).toLocaleString();
            document.getElementById('stockRotation').textContent = stockRotation + 'x';
        }

        // Funciones de exportación
        function exportToPDF() {
            alert('Función de exportación a PDF en desarrollo');
        }

        function exportToExcel() {
            alert('Función de exportación a Excel en desarrollo');
        }

        function printReport() {
            window.print();
        }

        // Inicializar métricas al cargar
        document.addEventListener('DOMContentLoaded', function() {
            calculateAdvancedMetrics();
        });


    </script>
    
    <script src="../../assets/js/admin/admin.js"></script>
</body>
</html>
