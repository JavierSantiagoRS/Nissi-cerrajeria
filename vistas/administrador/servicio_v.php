<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/servicios_m.php';
$servicio = obtenerservicio($conn);
$totalservicio = obtenerTotalservicio($conn);
$sumaPrecios = obtenerSumaPrecios($conn);

$totalservicio = contarServicios($conn);

// Límite de servicios por página
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$inicio = ($pagina - 1) * $limite;
$servicio = obtenerServiciosPaginados($conn, $inicio, $limite);

$totalPaginas = ceil($totalservicio / $limite);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>servicios - NISSI Cerrajería</title>
    <link rel="../../assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../../assets/css/admin/servicios.css">
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
                    <img src="../../assets\img\logo.jpg" alt="NISSI Cerrajería">
                </div>
                <h3>NISSI Cerrajería</h3>
            </div>
            <div class="sidebar-menu">
                <ul>
                      <li ><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-boxes"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li class="active"><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
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
                <div class="page-header">
                    <h1>Servicios</h1>
                    <div class="header-actions">
                        <button data-bs-toggle="modal" data-bs-target="#productModal" class="btn btn-primary" id="btnAddProduct"><i class="fas fa-plus"></i> Añadir Servicio</button>
                    </div>
                </div>

                <!-- Resumen del servicio -->
                <div class="inventory-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total servicios</h4>
                         <p class="summary-value"><?= $totalservicio ?></p>

                        </div>
                    </div>
                
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Valor de los servicios</h4>
                            <p class="summary-value">$ <?= number_format($sumaPrecios, 0, ',', '.') ?> COP</p>

                        </div>
                    </div>
                </div>

             <!-- Filtros y búsqueda -->
<div class="filter-section">
  <form method="GET" class="search-filters">
    <div class="filter-container">

      <!-- Estado -->
      <select name="estado" onchange="this.form.submit()">
        <option value="">Estados (Todos)</option>
        <option value="activo" <?= isset($_GET['estado']) && $_GET['estado']=="activo" ? 'selected' : '' ?>>Activos</option>
        <option value="inactivo" <?= isset($_GET['estado']) && $_GET['estado']=="inactivo" ? 'selected' : '' ?>>Inactivos</option>
      </select>

      <!-- Precio -->
      <select name="precio" onchange="this.form.submit()">
        <option value="">Ordenar por Precio</option>
        <option value="asc" <?= isset($_GET['precio']) && $_GET['precio']=="asc" ? 'selected' : '' ?>>Precio (Menor a Mayor)</option>
        <option value="desc" <?= isset($_GET['precio']) && $_GET['precio']=="desc" ? 'selected' : '' ?>>Precio (Mayor a Menor)</option>
      </select>

      <!-- Nombre -->
      <select name="nombre" onchange="this.form.submit()">
        <option value="">Ordenar por Nombre</option>
        <option value="asc" <?= isset($_GET['nombre']) && $_GET['nombre']=="asc" ? 'selected' : '' ?>>Nombre (A-Z)</option>
        <option value="desc" <?= isset($_GET['nombre']) && $_GET['nombre']=="desc" ? 'selected' : '' ?>>Nombre (Z-A)</option>
      </select>
    </div>
  </form>
</div>

                <!-- Vista de tabla de servicio -->
                <div class="inventory-view table-view active">
                    <div class="table-container">
                        <table class="inventory-table">
                        
                            
            <tr>
                <th>#</th>
                <th>Icono</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th></th>
                <th>Acciones</th>
                <th></th>
            </tr>
            <?php $contador = 1; ?>
            <?php foreach ($servicio as $nota): ?>
            <tr>
                  <td><?= $contador++ ?></td> <!-- Número visual en orden -->
           <td>
    <div class="icon-box">
        <i class="<?= htmlspecialchars($nota['imagen']) ?>"></i>
    </div>
</td>


                <td><?= $nota['nombre'] ?></td>
<td>
    <?php if (!empty($nota['precio'])): ?>
        <?= number_format($nota['precio'], 0, ',', '.') ?> COP
    <?php else: ?>
        <em>Precio a convenir en chat</em>
    <?php endif; ?>
</td>

                <td><?= $nota['descripcion'] ?></td>
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
            <a href="../../controlador/servicios_c.php?accion=cambiar_estado&id=<?= $nota['id'] ?>&estado=inactivo" 
               class="btn btn-warning btn-sm">Desactivar</a>
        <?php else: ?>
            <a href="../../controlador/servicios_c.php?accion=cambiar_estado&id=<?= $nota['id'] ?>&estado=activo" 
               class="btn btn-success btn-sm">Activar</a>
        <?php endif; ?>
        <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $nota['id'] ?>">Editar</button>
                    </td>
                <td>
                    <a href="#" onclick="eliminarservicio(<?= $nota['id'] ?>)" class="btn btn-danger">
  <i class="fas fa-trash-alt"></i>
  </td>  
</a>
                </td>
            </tr>
            <!-- Modal Editar -->
            <div class="modal fade" id="modalEdit<?= $nota['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar servicios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body">
                           <form action="../../controlador/servicios_c.php?accion=actualizar" method="POST" enctype="multipart/form-data">

                                <input type="hidden" name="id" value="<?= $nota['id'] ?>">
                                 <div class="mb-3">
                                      <div class="form-group">
        <label for="imagen">Ícono:</label>
       <select name="imagen" class="form-select">
    
    <option value="fas fa-lock" <?= $nota['imagen'] == 'fas fa-lock' ? 'selected' : '' ?>>🔒 Cerradura</option>
    <option value="fas fa-key" <?= $nota['imagen'] == 'fas fa-key' ? 'selected' : '' ?>>🔑 Llave</option>
    <option value="fas fa-door-open" <?= $nota['imagen'] == 'fas fa-door-open' ? 'selected' : '' ?>>🚪 Puerta abierta</option>
    <option value="fas fa-tools" <?= $nota['imagen'] == 'fas fa-tools' ? 'selected' : '' ?>>🛠️ Herramientas</option>
    <option value="fas fa-shield-alt" <?= $nota['imagen'] == 'fas fa-shield-alt' ? 'selected' : '' ?>>🛡️ Seguridad</option>
    <option value="fas fa-unlock" <?= $nota['imagen'] == 'fas fa-unlock' ? 'selected' : '' ?>>🔓 Apertura</option>
</select>

    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= $nota['nombre'] ?>">
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
    <a class="btn-page" href="?pagina=<?= $pagina - 1 ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
      <i class="fas fa-chevron-left"></i>
    </a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
    <a class="btn-page <?= $i == $pagina ? 'active' : '' ?>" 
       href="?pagina=<?= $i ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
       <?= $i ?>
    </a>
  <?php endfor; ?>

  <?php if ($pagina < $totalPaginas): ?>
    <a class="btn-page" href="?pagina=<?= $pagina + 1 ?>&estado=<?= $_GET['estado'] ?? '' ?>&precio=<?= $_GET['precio'] ?? '' ?>&nombre=<?= $_GET['nombre'] ?? '' ?>">
      <i class="fas fa-chevron-right"></i>
    </a>
  <?php endif; ?>
</div>


                </div>
            </div>
        </main>
    </div>

    <!-- Modal para añadirservicio -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Añadir Nuevo servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form action="../../controlador/servicios_c.php?accion=crear" method="POST" enctype="multipart/form-data">
          
              <div class="form-group">
        <label for="imagen">Ícono:</label>
        <select name="imagen" required>
            <option value="">-- Selecciona un ícono --</option>
            <option value="fas fa-lock">🔒 Cerradura</option>
            <option value="fas fa-key">🔑 Llave</option>
            <option value="fas fa-door-open">🚪 Puerta abierta</option>
            <option value="fas fa-tools">🛠️ Herramientas</option>
            <option value="fas fa-shield-alt">🛡️ Seguridad</option>
            <option value="fas fa-unlock">🔓 Apertura</option>
        </select>
    </div>

          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" step="0.01" class="form-control">
          </div>

            <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" step="0.01" class="form-control" required>
          </div>
<br>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar servicio</button>
        </form>

      </div>

    </div>
  </div>
</div>



   

    <div class="modal-overlay"></div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/admin/servicio.js"></script>
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
  function eliminarservicio(id) {
    Swal.fire({
      title: "¿Estás seguro?",
      text: "No podrás deshacer esta acción",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("../../controlador/servicios_c.php?accion=eliminar&id=" + id)
          .then(response => response.text())
          .then(data => {
            data = data.trim();
            if (data === "eliminado") {
              Swal.fire({
                icon: "success",
                title: "Eliminado",
                text: "Servicio eliminado correctamente",
                timer: 1500,
                showConfirmButton: false
              }).then(() => {
                location.reload();
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "No se puede eliminar",
                text: "Existen ventas con este servicio."
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: "error",
              title: "Error al eliminar",
              text: error.message
            });
          });
      }
    });
  }
</script>


</body>
</html>