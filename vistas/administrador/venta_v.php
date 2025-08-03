<?php

session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}


include '../../conexion.php';
include '../../modelos/venta_m.php';


$ventas = obtenerventas($conn);


$sql_ventas = "SELECT COUNT(*) AS total FROM ventas";
$total_ventas = $conn->query($sql_ventas)->fetch_assoc()['total'];

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
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="inventario_v.php"><i class="fas fa-box"></i> Inventario</a></li>
                    <li><a href="clientes_v.php"><i class="fas fa-users"></i> Clientes</a></li>
                     <li><a href="buzon_v.php"><i class="fas fa-envelope"></i>Buzón</a></li>
 <li><a href="servicio_v.php"><i class="fas fa-tools"></i> servicios</a></li>
  <li><a href="pedido_v.php"><i class="fas fa-tools"></i>Pedidos</a></li>
    <li class="active"> <a href="venta_v.php"><i class="fas fa-tools"></i>Ventas</a></li>
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
               

                <!-- Resumen de clientes -->
                <div class="clients-summary">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-info">
                            <h4>Total ventas</h4>
                    <p class="summary-value"><?php echo $total_ventas; ?></p>

                        </div>
                    </div>
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
        <tr data-id="<?= $venta['id'] ?>">
            <td><?=  htmlspecialchars($venta['nombre_cliente'])?></td>
            <td><?= htmlspecialchars($venta['id_cliente']) ?></td>
                      <td>$<?= number_format($venta['total'], 0, ',', '.') ?> COP</td>
            <td><?= date("d/m/Y H:i A", strtotime($venta['fecha'])) ?></td>
            <td class="estado"><?= ucfirst($venta['estado']) ?></td>
            <td>
                <button class="btn-confirmar btn btn-success btn-sm">Confirmar</button>
                <button class="btn-cancelar btn btn-danger btn-sm">Cancelar</button>
                <button class="btn-eliminar">🗑️ Eliminar</button>
                <button class="btn-ver-pedidos btn btn-info btn-sm">Ver pedidos</button>

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
                <th>ID</th>
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
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
                            <td>${p.id}</td>
                            <td>${p.nombre_item}</td>
                            <td>${p.tipo}</td>
                            <td>${p.cantidad}</td>
                            <td>$${parseInt(p.subtotal).toLocaleString()}</td>
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
                fila.querySelector(".estado").textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            } else {
                alert("Error al actualizar estado: " + data.error);
            }
        })
        .catch(err => alert("Error de red: " + err));
    }
});
</script>

</body>
</html>
