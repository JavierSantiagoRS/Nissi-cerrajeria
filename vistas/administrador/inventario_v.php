<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/inventario_m.php';

$totalinventario = obtenerTotalinventario($conn);
$sumaPrecios = obtenerSumaPrecios($conn);


// Definir límite por página
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

// Calcular desde qué registro empezar
$inicio = ($pagina - 1) * $limite;

// Traer productos paginados
$inventario = obtenerInventarioPaginado($conn, $inicio, $limite);

// Calcular páginas
$totalProductos = contarInventario($conn);
$totalPaginas = ceil($totalProductos / $limite);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/inventario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/bootstrap/js/bootstrap.bundle.js" ></script>
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

/* Activo - verde pálido */
.badge-activo {
    background-color: #d4edda;   /* verde pastel */
    color: #155724;              /* verde oscuro texto */
    border-color: #c3e6cb;
}

/* Inactivo - rojo pálido */
.badge-inactivo {
    background-color: #f8d7da;   /* rojo pastel */
    color: #721c24;              /* rojo oscuro texto */
    border-color: #f5c6cb;
}

/* Pendiente - azul pálido (opcional) */
.badge-pendiente {
    background-color: #d1ecf1;   /* azul pastel */
    color: #0c5460;              /* azul oscuro texto */
    border-color: #bee5eb;
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

        /* === MODAL GENERAL === */
.modal-dialog {
  max-width: 550px;   /* ancho máximo */
  margin: auto;       /* centra horizontal */
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
                    <li class="active"><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>
    <li><a href="venta_v.php"><i class="fas fa-shopping-cart"></i>Ventas</a></li>

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
                <div class="page-header">
                    <h1>Inventario</h1>
                    <div class="header-actions">
                        <button data-bs-toggle="modal" data-bs-target="#productModal" class="btn btn-primary" id="btnAddProduct"><i class="fas fa-plus"></i> Añadir Producto</button>
                    </div>
                </div>

                <!-- Resumen del inventario -->
                <div class="inventory-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total inventario</h4>
                         <p class="summary-value"><?= $totalinventario ?></p>

                        </div>
                    </div>
                
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Valor del inventario</h4>
                            <p class="summary-value">$ <?= number_format($sumaPrecios, 0, ',', '.') ?> COP</p>

                        </div>
                    </div>
                </div>

              <!-- Filtros y búsqueda -->
<div class="filter-section">
  <form method="GET" class="filter-container">

    <!-- Ordenar por precios -->
    <select name="precio" onchange="this.form.submit()">
      <option value="">Ordenar por precios</option>
      <option value="asc" <?= ($_GET['precio'] ?? '') == "asc" ? "selected" : "" ?>>Menor a Mayor</option>
      <option value="desc" <?= ($_GET['precio'] ?? '') == "desc" ? "selected" : "" ?>>Mayor a Menor</option>
    </select>

    <!-- Estados -->
    <select name="estado" onchange="this.form.submit()">
      <option value="">Estados (Todos)</option>
      <option value="activo" <?= ($_GET['estado'] ?? '') == "activo" ? "selected" : "" ?>>Activos</option>
      <option value="inactivo" <?= ($_GET['estado'] ?? '') == "inactivo" ? "selected" : "" ?>>Inactivos</option>
    </select>

    <!-- Stock -->
    <select name="stock" onchange="this.form.submit()">
      <option value="">Todos los niveles</option>
      <option value="bajo" <?= ($_GET['stock'] ?? '') == "bajo" ? "selected" : "" ?>>Stock Bajo</option>
      <option value="alto" <?= ($_GET['stock'] ?? '') == "alto" ? "selected" : "" ?>>Stock Alto</option>
    </select>

    <!-- Nombre -->
    <select name="nombre" onchange="this.form.submit()">
      <option value="">Ordenar por nombre</option>
      <option value="asc" <?= ($_GET['nombre'] ?? '') == "asc" ? "selected" : "" ?>>A - Z</option>
      <option value="desc" <?= ($_GET['nombre'] ?? '') == "desc" ? "selected" : "" ?>>Z - A</option>
    </select>

  </form>
</div>

                
                <!-- Vista de tabla de inventario -->
                <div class="inventory-view table-view active">
                    <div class="table-container">
                        <table class="inventory-table">
                        
                            
                <tr>
                <th>#</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>descripción</th>
                <th>Estado</th>
                <th></th>
                <th>Acciones</th>
                <th></th>
            </tr>
            <?php $contador = 1; ?>
      <?php foreach ($inventario as $nota): ?>
<tr>
    <td><?= $contador++ ?></td>
    <td>
        <img src="../../assets/<?= htmlspecialchars($nota['imagen']) ?>" alt="Producto" style="width: 80px;">
    </td>
    <td><?= htmlspecialchars($nota['titulo']) ?></td>
    <td><?= htmlspecialchars($nota['contenido']) ?></td>
    <td><?= number_format($nota['precio'], 0, ',', '.') ?></td>
    <td><?= htmlspecialchars($nota['descripcion']) ?></td>
  <td>
    <?php if ($nota['estado'] === 'activo'): ?>
        <span class="badge badge-activo">Activo</span>
    <?php else: ?>
        <span class="badge badge-inactivo">Inactivo</span>
    <?php endif; ?>
</td>


    <td>
        <!-- Botón Activar/Desactivar -->
        <?php if ($nota['estado'] === 'activo'): ?>
            <a href="../../controlador/inventario_c.php?accion=cambiar_estado&id=<?= $nota['id'] ?>&estado=inactivo" 
               class="btn btn-warning btn-sm">Desactivar</a>
        <?php else: ?>
            <a href="../../controlador/inventario_c.php?accion=cambiar_estado&id=<?= $nota['id'] ?>&estado=activo" 
               class="btn btn-success btn-sm">Activar</a>
        <?php endif; ?>

        <!-- Botón Editar -->
         <td>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $nota['id'] ?>">Editar</button>
</td>
        <!-- Botón Eliminar -->
         <td>
        <a href="#" onclick="eliminarInventario(<?= $nota['id'] ?>)" class="btn btn-danger btn-sm">
            <i class="fas fa-trash-alt"></i>
            </td>
        </a>
    </td>
</tr>

<!-- Modal para editar -->
<div class="modal fade" id="modalEdit<?= $nota['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form action="../../controlador/inventario_c.php?accion=actualizar" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $nota['id'] ?>">
                            <div class="file-upload mb-3">
    <label class="form-label">Imagen</label>
    <input type="file" id="importFile" name="imagen" accept="image/*,.csv,.xlsx,.xls"
 class="form-control">
    <label for="importFile" class="file-label">
        <i class="fas fa-cloud-upload-alt"></i>
        <span>Seleccionar Imagen</span>
    </label>
</div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="titulo" class="form-control" value="<?= $nota['titulo'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="contenido" class="form-control" value="<?= $nota['contenido'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio</label>
                        <input type="number" name="precio" class="form-control" value="<?= $nota['precio'] ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcion" class="form-control" value="<?= $nota['descripcion'] ?>">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


        </table>
    </div>
                            </tbody>
                        </table>
                    </div>
                 <div class="pagination">
  <?php if ($pagina > 1): ?>
    <a class="btn-page" href="?pagina=<?= $pagina - 1 ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&stock=<?= $_GET['stock'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
      <i class="fas fa-chevron-left"></i>
    </a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
    <a class="btn-page <?= $i == $pagina ? 'active' : '' ?>"
       href="?pagina=<?= $i ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&stock=<?= $_GET['stock'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
       <?= $i ?>
    </a>
  <?php endfor; ?>

  <?php if ($pagina < $totalPaginas): ?>
    <a class="btn-page" href="?pagina=<?= $pagina + 1 ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&stock=<?= $_GET['stock'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
      <i class="fas fa-chevron-right"></i>
    </a>
  <?php endif; ?>
</div>


                </div>
            </div>
        </main>
    </div>

    <!-- Modal para añadir producto -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Añadir Nuevo Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form action="../../controlador/inventario_c.php?accion=crear" method="POST" enctype="multipart/form-data">
          
          
                           <div class="file-upload mb-3">
    <label class="form-label">Imagen</label>
    <input class="form-control" type="file" id="importFile" name="imagen" accept="image/*,.csv,.xlsx,.xls" class="form-control" required>
    <label for="importFile" class="file-label">
        <i class="fas fa-cloud-upload-alt"></i>
        <span>Seleccionar Imagen</span>
    </label>
</div>

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="titulo" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="contenido" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" step="0.01" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
          </div>
          <br>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </form>

      </div>

    </div>
  </div>
</div>



   

    <div class="modal-overlay"></div>

    <script src="../../assets/js/admin/inventario.js"></script>

    <script>
       function eliminarInventario(id) {
  if (confirm("¿Estás seguro de eliminar este producto del inventario?")) {
    fetch("../../controlador/inventario_c.php?accion=eliminar&id=" + id)
      .then(response => response.text())
      .then(data => {
        data = data.trim();
        if (data === "eliminado") {
          alert("Producto eliminado correctamente.");
          location.reload();
       
        } else {
          alert("No se puede eliminar porque existen ventas con este producto. " );
        }
      })
      .catch(error => {
        alert("Error al eliminar: " + error.message);
      });
  }
}

    </script>
    
</body>
</html>