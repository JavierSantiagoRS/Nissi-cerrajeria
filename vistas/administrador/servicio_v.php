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


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>servicio - NISSI Cerrajería</title>
    <link rel="stylesheet" href="../../assets/css/admin/servicios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/bootstrap/js/bootstrap.bundle.js" ></script>
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
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li class="active"><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
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
                            <p class="summary-value">$ <?= number_format($sumaPrecios, 2, ',', '.') ?> COP</p>

                        </div>
                    </div>
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
                <th>Acciones</th>
                
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
                 <td>$ <?= $nota['precio'] ?> COP</td>
                <td><?= $nota['descripcion'] ?></td>

                <td>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $nota['id'] ?>">Editar</button>
                    <a href="../../controlador/servicios_c.php?accion=eliminar&id=<?= $nota['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash-alt"></i></a>
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
        <select name="imagen">
            <option value="">-- Selecciona un ícono --</option>
            <option value="fas fa-lock">🔒 Cerradura</option>
            <option value="fas fa-key">🔑 Llave</option>
            <option value="fas fa-door-open">🚪 Puerta abierta</option>
            <option value="fas fa-tools">🛠️ Herramientas</option>
            <option value="fas fa-shield-alt">🛡️ Seguridad</option>
            <option value="fas fa-unlock">🔓 Apertura</option>
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
                        <button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>
                        <button class="btn-page active">1</button>
                        <button class="btn-page">2</button>
                        <button class="btn-page">3</button>
                        <span class="pagination-dots">...</span>
                        <button class="btn-page">10</button>
                        <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
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
            <input type="number" name="precio" step="0.01" class="form-control" required>
          </div>

            <div class="mb-3">
            <label class="form-label">Descripción</label>
            <input type="text" name="descripcion" step="0.01" class="form-control" required>
          </div>


          <button type="submit" class="btn btn-primary">Guardar servicio</button>
        </form>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>



   

    <div class="modal-overlay"></div>

    <script src="../../assets/js/admin/servicio.js"></script>
</body>
</html>