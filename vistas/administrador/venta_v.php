<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/venta_m.php';



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
    <title>Clientes - NISSI Cerrajería</title>
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
                    <li><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>
    <li class="active"> <a href="venta_v.php"><i class="fas fa-shopping-cart"></i>Ventas</a></li>
 <li><a href="../../logout.php">Cerrar Sesión</a></li>
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
                            <h4>Total ventas</h4>
                    <p class="summary-value"><?php echo $totalVentas; ?></p>

                        </div>
                    </div>
                      <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                        <h4>Ventas pendientes</h4>
<p class="summary-value"><?php echo $total_ventasPendientes; ?></p>


                        </div>
                    </div>
                        <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                        <h4>Ventas confirmadas</h4>
<p class="summary-value"><?php echo $total_ventasConfirmadas; ?></p>


                        </div>
                    </div>
                      <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
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
    <select name="fecha" class="form-select" onchange="this.form.submit()">
      <option value="">Ordenar por Fecha</option>
      <option value="asc" <?= ($_GET['fecha'] ?? '') == "asc" ? "selected" : "" ?>>Más antiguas</option>
      <option value="desc" <?= ($_GET['fecha'] ?? '') == "desc" ? "selected" : "" ?>>Más recientes</option>
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

            <td><?=  htmlspecialchars($venta['nombre_cliente'])?></td>
            <td><?= htmlspecialchars($venta['id_cliente']) ?></td>
                      <td>$<?= number_format($venta['total'], 0, ',', '.') ?> COP</td>
            <td><?= htmlspecialchars(date("d/m/Y H:i A", strtotime($venta['fecha']))) ?></td>
            <td class="estado">
    <span class="badge badge-<?= $venta['estado'] ?>">
        <?= ucfirst($venta['estado']) ?>
    </span>
</td>

            <td>
                <button class="btn-confirmar btn btn-success btn-sm">Confirmar</button>
                <button class="btn-cancelar btn btn-danger btn-sm">Cancelar</button>
                <button class="btn-eliminar"> <i class="fas fa-trash-alt"></i></button>
                <button class="btn-ver-pedidos btn btn-info btn-sm"> <i class="fas fa-eye"></i></button>

            </td>
        </tr>
    <?php endforeach; ?>
</tbody>


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

          
                       
<!-- Modal mejorado -->
<div class="modal fade" id="modal-pedidos" tabindex="-1" aria-labelledby="modalPedidosLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="modalPedidosLabel">🧾 Pedidos de la Venta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>ID_pedido</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody id="tabla-pedidos-body">
              <!-- Se insertan pedidos aquí -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



   <script src="../../assets/js/admin/venta.js"></script>
   <script src="../../assets\bootstrap\js\bootstrap.js"></script>

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
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
    btn.addEventListener("click", function () {
        if (!confirm("¿Estás seguro de eliminar esta venta?")) return;

        const id = this.closest("tr").getAttribute("data-id");

        fetch("../../controlador/venta_c.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-id='${id}']`).remove();
            } else {
                alert("Error al eliminar: " + data.error);
            }
        })
        .catch(err => alert("Error de red: " + err));
    });
});

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

            // Cambiar el texto
            badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);

            // Quitar clases anteriores
            badge.classList.remove("badge-pendiente", "badge-confirmada", "badge-cancelada");

            // Agregar la clase nueva según estado
            badge.classList.add(`badge-${estado}`);
        } else {
            alert("Error al actualizar estado: " + data.error);
        }
    })
    .catch(err => alert("Error de red: " + err));
}

});
document.getElementById("filtroEstado").addEventListener("change", function() {
    const filtro = this.value.toLowerCase();
    document.querySelectorAll("#tablaPedidos tbody tr").forEach(fila => {
        const estado = fila.getAttribute("data-estado").toLowerCase();
        fila.style.display = (filtro === "" || estado === filtro) ? "" : "none";
    });
});

</script>



</body>
</html>
