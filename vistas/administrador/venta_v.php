<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/venta_m.php';

include '../../modelos/indexcliente_m.php';
include '../../controlador/ventascliente_c.php';
$idUsuario = $_SESSION["id_usuario"];
$ventas = obtenerVentasCliente($conn, $idUsuario);
$sql_ventas_confirmadas = "SELECT COUNT(*) AS total FROM ventas ";
$total_ventas = $conn->query($sql_ventas_confirmadas)->fetch_assoc()['total'];

// Todas las ventas del cliente
$ventas = obtenerVentasCliente($conn, $idUsuario);


$filtro = $_GET['filtro'] ?? 'fecha_desc';
$orden = ($filtro === 'fecha_asc') ? 'ASC' : 'DESC';

$sql_ventas = "SELECT COUNT(*) AS total FROM ventas";
$total_ventas = $conn->query($sql_ventas)->fetch_assoc()['total'];

$sql_ventas_pendientes = "SELECT COUNT(*) AS total FROM ventas WHERE estado = 'pendiente'";
$total_ventasPendientes = $conn->query($sql_ventas_pendientes)->fetch_assoc()['total'];

$sql_ventas_confirmadas = "SELECT COUNT(*) AS total FROM ventas WHERE estado = 'confirmada'";
$total_ventasConfirmadas = $conn->query($sql_ventas_confirmadas)->fetch_assoc()['total'];

$sql_ventas_canceladas = "SELECT COUNT(*) AS total FROM ventas WHERE estado = 'cancelada'";
$total_ventasCanceladas = $conn->query($sql_ventas_canceladas)->fetch_assoc()['total'];




// Inicializar filtros
$estado = isset($_GET['estado']) ? $_GET['estado'] : "";
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : "fecha_desc";
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Total ventas
$totalVentas = contarVentas($conn, $estado); // para paginación coherente

// paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// Obtener ventas paginadas
$ventas = obtenerVentasPaginadas($conn, $inicio, $limite, $filtro, $estado);
// Calcular total de páginas
$totalPaginas = ceil($totalVentas / $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - NISSI Cerrajería</title>
    <link rel="../../assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../../assets/css/admin/venta.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<style>
    .badge {
    display: inline-block;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.35em 0.75em;
    border-radius: 50px;
    text-transform: capitalize;
    letter-spacing: 0.3px;
    border: 1px solid transparent;
}

/* Pendiente - azul pastel */
.badge-pendiente {
     background-color: #fff3cd;   /* amarillo suave */
    color: #856404;              /* marrón oscuro para contraste */
    border-color: #ffeeba;

}

/* Confirmada - verde pastel */
.badge-confirmada {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

/* Cancelada - rojo pastel */
.badge-cancelada {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

   
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


.modal-content {
  border-radius: 16px;
  border: none;
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
  animation: fadeIn 0.3s ease;
  overflow: hidden;
}

/* === CABECERA === */
.modal-header {
  background:  #2a5298;
  color: #fff;
  border-radius: 16px 16px 0 0;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-title {
  font-size: 18px;
  font-weight: 600;
  margin: 0;
}
.btn-close {
  background: transparent;
  border: none;
  font-size: 22px;
  font-weight: bold;
  color: #fff;
  opacity: 0.9;
  cursor: pointer;
}
.btn-close:hover {
  opacity: 1;
  color: #ddd;
}

/* === CUERPO === */
.modal-body {
  padding: 25px;
}
.modal-body .form-label {
  font-weight: 600;
  color: #333;
  margin-bottom: 6px;
  display: block;
}
.modal-body .form-control {
  border-radius: 10px;
  padding: 10px;
  border: 1px solid #ccc;
  width: 100%;
  transition: border 0.3s, box-shadow 0.3s;
}
.modal-body .form-control:focus {
  border-color: #2a5298;
  box-shadow: 0 0 6px rgba(13,110,253,0.3);
  outline: none;
}

/* === BOTÓN GUARDAR === */
.btn-primary {
  background:  #2a5298;
  border: none;
  border-radius: 10px;
  padding: 10px 18px;
  font-weight: 600;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  background:  #234580ff;
  transform: translateY(-1px);

}

/* === ANIMACIÓN DE APARICIÓN === */
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-20px);}
  to {opacity: 1; transform: translateY(0);}
}

/* === RESPONSIVE === */
@media (max-width: 576px) {
  .modal-dialog {
    max-width: 90%;
  }
  .modal-body {
    padding: 20px 15px;
  }
}

.sidebar-footer {
  margin-top: auto;
  padding: 15px;
  text-align: center;
  font-size: 0.8rem;
  background-color: var(--secondary-blue);
}

          .inicial-perfil {
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    border-radius: 50%;
    background: #0011ffff; /* azul */
    color: #ffffffff;
    font-weight: bold;
    text-align: center;
    font-size: 16px;
}

   :root {
      --primary-color: #2563eb;
      --primary-dark: #1d4ed8;
      --secondary-color: #f59e0b;
      --accent-color: #10b981;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --text-light: #9ca3af;
      --bg-primary: #ffffff;
      --bg-secondary: #f8fafc;
      --bg-tertiary: #f1f5f9;
      --border-color: #e5e7eb;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
      --radius-sm: 0.375rem;
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --radius-xl: 1rem;
    }


 .ver-pedidos-btn {
  background: var(--primary-color);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: var(--radius-md);
  cursor: pointer;
  font-size: 0.875rem;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s ease;
}

.ver-pedidos-btn:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
}
@media (max-width: 1024px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;

    z-index: 2000;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .toggle-menu {
    display: block;
    cursor: pointer;
    font-size: 1.5rem;
    z-index: 2100;
    position: fixed;
    top: 10px;
    left: 10px;
  }
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
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-boxes"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-box"></i>Pedidos</a></li>
    <li class="active"> <a href="venta_v.php"><i class="fas fa-shopping-cart"></i>Ventas</a></li>
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
                   
                    <div class="user-profile">
                        
                        <span>Admin</span>
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
                    <span class="inicial-perfil"><?php echo $inicial; ?></span>
        <?php else: ?>
            <a href="vistas/login.php">Iniciar sesión</a></li>
        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Contenedor principal -->
            <div class="content-container">


                <!-- Resumen de clientes -->
                <div class="clients-summary">
          
                      <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="summary-info">
                        <h4>Ventas pendientes</h4>
<p class="summary-value"><?php echo $total_ventasPendientes; ?></p>


                        </div>
                    </div>
                        <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="summary-info">
                        <h4>Ventas confirmadas</h4>
<p class="summary-value"><?php echo $total_ventasConfirmadas; ?></p>


                        </div>
                    </div>
                      <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="summary-info">
                        <h4>Ventas canceladas</h4>
<p class="summary-value"><?php echo $total_ventasCanceladas; ?></p>
                        </div>
                    </div>
                </div>

<!-- Filtros y búsqueda -->
<div class="filter-section w-100 mb-3">
  <form method="GET" class="filter-container d-flex gap-2">

    <!-- Filtrar por estado -->
    <select name="estado" class="form-select" onchange="this.form.submit()">
      <option value="">Filtrar por Estado (Todos)</option>
      <option value="pendiente" <?= ($_GET['estado'] ?? '') == "pendiente" ? "selected" : "" ?>>Pendiente</option>
      <option value="confirmada" <?= ($_GET['estado'] ?? '') == "confirmada" ? "selected" : "" ?>>Confirmada</option>
      <option value="cancelada" <?= ($_GET['estado'] ?? '') == "cancelada" ? "selected" : "" ?>>Cancelada</option>
    </select>

    <!-- Ordenar por fecha -->
   <select name="filtro" class="form-select" onchange="this.form.submit()">
  <option value="fecha_desc" <?= ($_GET['filtro'] ?? '') == "fecha_desc" ? "selected" : "" ?>>Más recientes</option>
  <option value="fecha_asc" <?= ($_GET['filtro'] ?? '') == "fecha_asc" ? "selected" : "" ?>>Más antiguas</option>
</select>


  </form>
</div>



                <!-- Vista de tabla de clientes -->
                <div class="clients-view table-view active">
                    <div class="table-container">
                        <table id="tablaPedidos" class="clients-table">
                            <thead>
                                <tr>
                                   
                                    <th>Cliente</th>
                                    <th>ID Cliente</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                             <th>Estado</th>
                                  <th>Acciones</th>
                                </tr>
                            </thead>

<tbody>
<?php foreach ($ventas as $venta): ?>
   <tr data-id="<?= $venta['id'] ?>" data-estado="<?= $venta['estado'] ?>">
        <td><?= htmlspecialchars($venta['nombre_cliente'])?></td>
        <td><?= htmlspecialchars($venta['id_cliente']) ?></td>
       <td>
    <?php if (!empty($venta['total'])): ?>
        <?= number_format($venta['total'], 0, ',', '.') ?> COP
    <?php else: ?>
        <em>Precio a convenir en chat</em>
    <?php endif; ?>
</td>

        <td><?= htmlspecialchars(date("d/m/Y H:i A", strtotime($venta['fecha']))) ?></td>
        <td class="estado">
            <span class="badge badge-<?= $venta['estado'] ?>">
                <?= ucfirst($venta['estado']) ?>
            </span>
        </td>

        <td>
            <button class="btn-confirmar btn btn-success btn-sm">Confirmar</button>
            <button class="btn-cancelar btn btn-danger btn-sm">Cancelar</button>
            <button class="btn-eliminar"><i class="fas fa-trash-alt"></i></button>
        
            <!-- 🧾 Botón ver factura -->
            <button class="ver-pedidos-btn" onclick="verFactura(<?= $venta['id'] ?>)">
              <i class="fas fa-file-invoice"></i> Ver factura
            </button>
        </td>
    </tr>
<?php endforeach; ?>




                        </table>
                    </div>
  <div class="pagination">
    <?php if ($pagina > 1): ?>
        <!-- Botón Anterior -->
        <a class="btn-page" 
           href="?pagina=<?= $pagina - 1 ?>&filtro=<?= urlencode($filtro) ?>&estado=<?= urlencode($estado) ?>">
           <i class="fas fa-chevron-left"></i>
        </a>
    <?php endif; ?>

    <!-- Números de página -->
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a class="btn-page <?= $i == $pagina ? 'active' : '' ?>" 
           href="?pagina=<?= $i ?>&filtro=<?= urlencode($filtro) ?>&estado=<?= urlencode($estado) ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagina < $totalPaginas): ?>
        <!-- Botón Siguiente -->
        <a class="btn-page" 
           href="?pagina=<?= $pagina + 1 ?>&filtro=<?= urlencode($filtro) ?>&estado=<?= urlencode($estado) ?>">
           <i class="fas fa-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>


                </div>

          
                       

<!-- Modal Factura -->
<div id="facturaModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
  <div style="background:white; padding:20px; border-radius:10px; max-width:600px; width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3);">
    <h2 style="margin-bottom:15px;"><i class="fas fa-file-invoice"></i> Factura</h2>
    <div id="facturaContenido"></div>
    <div style="text-align:right; margin-top:15px;">
      <button onclick="cerrarFactura()" class="ver-pedidos-btn" style="background:#ef4444;">
        <i class="fas fa-times"></i> Cerrar
      </button>
     <button onclick="imprimirFactura()" class="ver-pedidos-btn" style="background:#16a34a; margin-right:10px;">
  <i class="fas fa-download"></i> Descargar PDF
</button>


    </div>
  </div>
</div>


   <script src="../../assets/js/admin/venta.js"></script>
   <script src="../../assets/bootstrap/js/bootstrap.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.querySelector(".toggle-menu");
  const sidebar = document.querySelector(".sidebar");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });
});
</script>

   <script>

function verFactura(idVenta) {
    // Buscar fila por data-id
    const fila = document.querySelector(`tr[data-id="${idVenta}"]`);
    if (!fila) return;

    // Datos básicos
    const cliente = fila.cells[0].innerText;
    const idCliente = fila.cells[1].innerText;
    const total = fila.cells[2].innerText;
    const fecha = fila.cells[3].innerText;

    // Pedidos (los obtenemos vía fetch igual que en el modal)
    fetch('../../controlador/pedidos_por_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_venta: idVenta })
    })
    .then(res => res.json())
    .then(data => {
        let tablaPedidos = '';

        if (data.success && data.pedidos.length > 0) {
            tablaPedidos = `
            <table border="1" cellpadding="5" cellspacing="0" width="100%" style="margin-top:10px;">
                <thead>
                    <tr>
                        <th>Producto/Servicio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.pedidos.map(p => `
                        <tr>
                            <td>${p.nombre_item}</td>
                            <td>${p.cantidad}</td>
                            <td>$${parseInt(p.subtotal).toLocaleString()} COP</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>`;
        } else {
            tablaPedidos = '<p>No hay pedidos.</p>';
        }

        // Contenido del modal
        const contenido = `
            <p><strong>Cliente:</strong> ${data.cliente.usuario}</p>
            <p><strong>Dirección:</strong> ${data.cliente.calle}, ${data.cliente.ciudad}, ${data.cliente.departamento}, ${data.cliente.codigo_postal}</p>

            <p><strong>Email:</strong> ${data.cliente.correo}</p>
            <p><strong>Teléfono:</strong> ${data.cliente.celular}</p>
            <p><strong>Fecha:</strong> ${fecha}</p>
            <p><strong>Total:</strong> ${total}</p>
            <h3 style="margin-top:10px;">Detalles:</h3>
            ${tablaPedidos}
        `;

        document.getElementById('facturaContenido').innerHTML = contenido;
        document.getElementById('facturaModal').style.display = 'flex';
    });
}



function cerrarFactura() {
  document.getElementById('facturaModal').style.display = 'none';
}

function imprimirFactura() {
  const contenido = document.getElementById("facturaContenido").innerHTML;

  const ventana = window.open("", "_blank", "width=800,height=600");
  ventana.document.write(`
    <html>
      <head>
        <title>Factura</title>
        <style>
          body { font-family: 'Arial', sans-serif; padding: 30px; color: #333; }
          .factura-box {
            max-width: 700px;
            margin: auto;
            border: 1px solid #eee;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,.15);
          }
          .factura-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 10px;
            margin-bottom: 20px;
          }
          .factura-header h1 {
            font-size: 24px;
            margin: 0;
            color: #16a34a;
          }
          .factura-info {
            margin-bottom: 20px;
          }
          .factura-info p {
            margin: 4px 0;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
          }
          table, th, td {
            border: 1px solid #ccc;
          }
          th {
            background: #16a34a;
            color: white;
            padding: 8px;
            text-align: left;
          }
          td {
            padding: 8px;
          }
          .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
          }
          .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
          }
        </style>
      </head>
      <body>
        <div class="factura-box">
          <div class="factura-header">
            <h1>Nissi Cerrajeria</h1>
          </div>

          <div class="factura-info">
            <p><strong>Empresa:</strong> NISSI Cerrajería</p>
          </div>

          ${contenido}

          <div class="footer">
            <p>Gracias por su compra 💚</p>
            <p>Esta factura fue generada automáticamente por el sistema.</p>
          </div>
        </div>
      </body>
    </html>
  `);
  ventana.document.close();
  ventana.print();
}
</script>

<script>
    document.querySelectorAll('.btn-ver-pedidos').forEach(btn => {
    btn.addEventListener('click', function () {
        const idVenta = this.closest('tr').dataset.id;

        fetch('../../controlador/pedidos_por_venta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_venta: idVenta })
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('tabla-pedidos-body');
            tbody.innerHTML = '';

            if (data.success) {
                data.pedidos.forEach(p => {
                    tbody.innerHTML += `
                        <tr>
                            <th>${p.id}</th>
                            <th>${p.nombre_item}</th>
                            <th>${p.tipo}</th>
                            <th>${p.cantidad}</th>
                            <th>$${parseInt(p.subtotal).toLocaleString()}</th>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="5">No hay pedidos.</td></tr>`;
            }

            new bootstrap.Modal(document.getElementById('modal-pedidos')).show();
        });
    });
});

</script>
   
  <script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Eliminar venta ---
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.closest("tr").getAttribute("data-id");

            Swal.fire({
                title: "¿Eliminar venta?",
                text: "Esta acción no se puede deshacer",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (result.isConfirmed) {
                   fetch("../../controlador/venta_c.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ action: "delete", id })
})

                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Elimina la fila
                            document.querySelector(`tr[data-id='${id}']`).remove();

                            Swal.fire({
                                icon: "success",
                                title: "Eliminado",
                                text: "La venta fue eliminada con éxito",
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire("Error", data.error, "error");
                        }
                    })
                    .catch(err => {
                        Swal.fire("Error de red", err.message, "error");
                    });
                }
            });
        });
    });

    // --- Confirmar / Cancelar venta ---
    document.querySelectorAll(".btn-confirmar").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.closest("tr").getAttribute("data-id");
            actualizarEstadoVenta(id, "confirmada");
        });
    });

    document.querySelectorAll(".btn-cancelar").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.closest("tr").getAttribute("data-id");
            actualizarEstadoVenta(id, "cancelada");
        });
    });

    function actualizarEstadoVenta(id, estado) {
        fetch("../../controlador/venta_c.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, estado })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const fila = document.querySelector(`tr[data-id='${id}']`);
                const badge = fila.querySelector(".estado .badge");

                badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                badge.classList.remove("badge-pendiente", "badge-confirmada", "badge-cancelada");
                badge.classList.add(`badge-${estado}`);

                Swal.fire({
                    icon: "success",
                    title: "Actualizado",
                    text: `La venta se marcó como ${estado}`,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire("Error", data.error, "error");
            }
        })
        .catch(err => Swal.fire("Error de red", err.message, "error"));
    }

    // --- Filtro de estado ---
    document.getElementById("filtroEstado").addEventListener("change", function() {
        const filtro = this.value.toLowerCase();
        document.querySelectorAll("#tablaPedidos tbody tr").forEach(fila => {
            const estado = fila.getAttribute("data-estado").toLowerCase();
            fila.style.display = (filtro === "" || estado === filtro) ? "" : "none";
        });
    });
});
</script>




</body>
</html>
