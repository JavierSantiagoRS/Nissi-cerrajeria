<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
}
include '../../conexion.php';
include '../../modelos/indexcliente_m.php';
include '../../controlador/ventascliente_c.php';
$idUsuario = $_SESSION["id_usuario"];
$ventas = obtenerVentasCliente($conn, $idUsuario);
$sql_ventas_confirmadas = "SELECT COUNT(*) AS total FROM ventas ";
$total_ventas = $conn->query($sql_ventas_confirmadas)->fetch_assoc()['total'];

// Todas las ventas del cliente
$ventas = obtenerVentasCliente($conn, $idUsuario);

// Confirmadas
$ventas_confirmadas = array_filter($ventas, function($venta) {
    return strtolower($venta['estado']) === 'confirmada';
});

// Pendientes
$ventas_pendientes = array_filter($ventas, function($venta) {
    return strtolower($venta['estado']) === 'pendiente';
});

// Total confirmadas + pendientes
$total_confirmadas_pendientes = count($ventas_confirmadas) + count($ventas_pendientes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perfil del Cliente</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
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

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      color: var(--text-primary);
      line-height: 1.6;
      font-size: 14px;
      margin: 30px;
      position: relative;
    }

    /* Sophisticated background pattern */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
      z-index: 0;
      pointer-events: none;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: var(--bg-primary);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-xl);
      overflow: hidden;
      position: relative;
      z-index: 1;
    }

    /* Header Section */
    .header {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 2rem;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .header-content {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .avatar {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      font-weight: 600;
      backdrop-filter: blur(10px);
      border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .header-info h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      letter-spacing: -0.025em;
    }

    .header-subtitle {
      font-size: 1rem;
      opacity: 0.9;
      font-weight: 400;
    }

    /* Main Content */
    .main-content {
      padding: 2rem;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    /* Info Panel */
    .info-panel {
      background: var(--bg-secondary);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
      border: 1px solid var(--border-color);
    }

    .info-panel h2 {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-panel h2 i {
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      padding: 1rem 0;
      border-bottom: 1px solid var(--border-color);
    }

    .info-item:last-child {
      border-bottom: none;
    }

    .info-icon {
      width: 40px;
      height: 40px;
      background: var(--primary-color);
      color: white;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .info-content {
      flex: 1;
    }

    .info-label {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-secondary);
      margin-bottom: 0.25rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .info-value {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-primary);
    }

    /* Stats Panel */
    .stats-panel {
      background: var(--bg-primary);
      border-radius: var(--radius-lg);
      padding: 1.5rem;
      border: 1px solid var(--border-color);
      box-shadow: var(--shadow-sm);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .stat-card {
      background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-secondary) 100%);
      padding: 1.5rem;
      border-radius: var(--radius-md);
      text-align: center;
      border: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-color);
      display: block;
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 0.875rem;
      color: var(--text-secondary);
      font-weight: 500;
    }

    /* History Section */
    .history-section {
      background: var(--bg-primary);
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }

    .section-header {
      background: var(--bg-secondary);
      padding: 1.5rem;
      border-bottom: 1px solid var(--border-color);
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .section-icon {
      width: 32px;
      height: 32px;
      background: var(--secondary-color);
      color: white;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.875rem;
    }

    /* Table */
    .table-container {
      overflow-x: auto;
    }

    .purchase-history {
      margin-top: 15px;
    }

    .purchase-history table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
    }

    .purchase-history th {
      background: var(--bg-tertiary);
      color: var(--text-primary);
      font-weight: 600;
      text-align: left;
      padding: 1rem;
      border-bottom: 2px solid var(--border-color);
      font-size: 0.8125rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .purchase-history td {
      padding: 1rem;
      border-bottom: 1px solid var(--border-color);
      color: var(--text-secondary);
      vertical-align: middle;
    }

    .purchase-history tbody tr {
      transition: all 0.2s ease;
    }

    .purchase-history tbody tr:hover {
      background: var(--bg-secondary);
    }

    .purchase-history tbody tr:last-child td {
      border-bottom: none;
    }

    /* Status Badge */
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.375rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }

    .status-confirmada {
      background: rgba(16, 185, 129, 0.1);
      color: var(--accent-color);
    }

    .status-pendiente {
      background: rgba(245, 158, 11, 0.1);
      color: var(--secondary-color);
    }

    .status-cancelada {
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    /* Price styling */
    .price {
      font-weight: 600;
      color: var(--text-primary);
      font-variant-numeric: tabular-nums;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: var(--text-light);
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    .empty-state h3 {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--text-secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      body {
        margin: 15px;
      }

      .header {
        padding: 1.5rem;
      }

      .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }

      .avatar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }

      .header-info h1 {
        font-size: 1.5rem;
      }

      .main-content {
        padding: 1.5rem;
      }

      .content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .purchase-history th,
      .purchase-history td {
        padding: 0.75rem 0.5rem;
        font-size: 0.8125rem;
      }

      .table-container {
        margin: 0 -1.5rem;
        padding: 0 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .header {
        padding: 1rem;
      }

      .main-content {
        padding: 1rem;
      }

      .info-panel,
      .stats-panel {
        padding: 1rem;
      }

      .section-header {
        padding: 1rem;
      }

      .purchase-history th,
      .purchase-history td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
      }

      .table-container {
        margin: 0 -1rem;
        padding: 0 1rem;
      }
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .container {
      animation: fadeInUp 0.6s ease-out;
    }

    .info-item,
    .stat-card {
      animation: fadeInUp 0.6s ease-out;
      animation-fill-mode: both;
    }

    .info-item:nth-child(1) { animation-delay: 0.1s; }
    .info-item:nth-child(2) { animation-delay: 0.2s; }
    .info-item:nth-child(3) { animation-delay: 0.3s; }
    .info-item:nth-child(4) { animation-delay: 0.4s; }

    .stat-card:nth-child(1) { animation-delay: 0.2s; }
    .stat-card:nth-child(2) { animation-delay: 0.3s; }

    /* Print styles */
    @media print {
      body {
        background: white;
        margin: 0;
      }

      .container {
        box-shadow: none;
        border: 1px solid #ccc;
      }

      .header {
        background: #f5f5f5 !important;
        color: #333 !important;
      }

      .purchase-history th {
        background: #f0f0f0 !important;
        color: #333 !important;
      }
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

  </style>
</head>
<body>
  <div class="container">
    
    <div class="header">
        <a href="index.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
      <div class="header-content">
        <div class="avatar">
          <?= strtoupper(substr($usuario['usuario'], 0, 1)) ?>
        </div>
        <div class="header-info">
          <h1>Información de Compras</h1>
          <p class="header-subtitle">Panel de Compras</p>
        </div>
      </div>
    </div>


    <div class="main-content">
      <div class="content-grid">
         
       
     
        
          <h2>
            <i class="fas fa-chart-bar"></i>
            Estadísticas
          </h2>
          <div class="stats-grid">
          <div class="stat-card">
  <span class="stat-number"><?= $total_confirmadas_pendientes ?></span>
  <span class="stat-label">Compras Realizadas</span>
</div>


        
          </div>
          
        </div>
      
      

      
      <div class="history-section">
        <div class="section-header">
          <div class="section-title">
            <div class="section-icon">
              <i class="fas fa-history"></i>
            </div>
            Historial de Compras Recientes
          </div>
        </div>

        <div class="table-container">
          <?php if (count($ventas) > 0): ?>
          <div class="purchase-history">
            <table>
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Acciones</th>
                  <th></th>
                  <th>Total</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
              <tbody>
  <?php foreach ($ventas as $venta): ?>
    <!-- Fila principal de la venta -->
    <tr>
      <td><?= $venta['fecha'] ?></td>
      <td colspan="2">
        <button class="ver-pedidos-btn" onclick="togglePedidos(<?= $venta['id_venta'] ?>)">
          <i class="fas fa-eye"></i> Ver pedidos
        </button>
      </td>
      <td class="price">$<?= number_format($venta['total'], 0, ',', '.') ?> COP</td>
      <td>
        <span class="status-badge status-<?= strtolower($venta['estado']) ?>">
          <?= ucfirst($venta['estado']) ?>
        </span>
      </td>
    </tr>

    <!-- Fila oculta con los pedidos -->
    <tr id="pedidos-<?= $venta['id_venta'] ?>" style="display:none; background:#f9fafb;">
      <td colspan="5">
        <table style="width:100%; font-size:0.85rem; border-collapse:collapse; margin-top:10px;">
          <thead>
            <tr style="background:#f1f5f9;">
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Tipo</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($venta['pedidos'] as $pedido): ?>
              <tr>
                <td><?= htmlspecialchars($pedido['nombre_item']) ?></td>
                <td><?= $pedido['cantidad'] ?></td>
                <td><?= ucfirst($pedido['tipo']) ?></td>
                <td class="price">$<?= number_format($pedido['subtotal'], 0, ',', '.') ?> COP</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>

              </tbody>
            </table>
          </div>
          <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No hay compras registradas</h3>
            <p>Cuando realices tu primera compra, aparecerá aquí tu historial</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
function togglePedidos(id) {
  const fila = document.getElementById('pedidos-' + id);
  fila.style.display = fila.style.display === 'none' ? '' : 'none';
}
</script>

</body>
</html>