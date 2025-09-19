<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include '../../conexion.php';
include '../../modelos/indexcliente_m.php';
include '../../controlador/ventascliente_c.php';

$idUsuario = $_SESSION["id_usuario"];

// Obtener datos del usuario (incluyendo dirección)
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Obtener todas las ventas del cliente
$ventas = obtenerVentasCliente($conn, $idUsuario);

// Ventas confirmadas
$ventas_confirmadas = array_filter($ventas, function($venta) {
    return strtolower($venta['estado']) === 'confirmada';
});

// Ventas pendientes
$ventas_pendientes = array_filter($ventas, function($venta) {
    return strtolower($venta['estado']) === 'pendiente';
});

// Total confirmadas + pendientes
$total_confirmadas_pendientes = count($ventas_confirmadas) + count($ventas_pendientes);

// Total ventas confirmadas global
$sql_ventas_confirmadas = "SELECT COUNT(*) AS total FROM ventas WHERE estado = 'confirmada'";
$total_ventas = $conn->query($sql_ventas_confirmadas)->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>NISSI Cerrajería - Información del Cliente</title>
  <link rel="../../assets\img\logo2.jpeg" href="assets/img/icono.svg" type="image/svg+xml">
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
         background: linear-gradient(135deg, #b4b5bbff 0%, #b7bad8e5 100%);
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
          <h1>Información del Cliente</h1>
          <p class="header-subtitle">Panel de gestión personal</p>
        </div>
      </div>
    </div>


    <div class="main-content">
      <div class="content-grid">
         
<div class="info-panel">
  <h2>
    <i class="fas fa-user-circle"></i>
    Datos Personales
  </h2>

  <div class="info-item">
    <div class="info-icon">
      <i class="fas fa-user"></i>
    </div>
    <div class="info-content">
      <div class="info-label">Nombre completo</div>
      <div class="info-value"><?= htmlspecialchars($usuario['usuario']) ?></div>
    </div>
  </div>

  <div class="info-item">
    <div class="info-icon">
      <i class="fas fa-envelope"></i>
    </div>
    <div class="info-content">
      <div class="info-label">Correo electrónico</div>
      <div class="info-value"><?= htmlspecialchars($usuario['correo']) ?></div>
    </div>
  </div>

 <div class="info-item">
  <div class="info-icon">
    <i class="fas fa-phone"></i>
  </div>
  <div class="info-content">
    <div class="info-label">Teléfono</div>
    <div class="info-value">+57 <?= htmlspecialchars($usuario['celular']) ?></div>
    <button type="button" id="btn-editar-telefono" style="margin-top:5px; padding:0.25rem 0.5rem; font-size:0.75rem; background:var(--primary-color); color:white; border:none; border-radius:4px; cursor:pointer;">
      Editar
    </button>
  </div>
</div>


  <div class="info-item">
    <div class="info-icon">
      <i class="fas fa-map-marker-alt"></i>
    </div>
    <div class="info-content">
      <div class="info-label">Dirección</div>
      <div class="info-value">
        <?= 
          htmlspecialchars(
            trim($usuario['calle'] ?? '') . ', ' . 
            trim($usuario['ciudad'] ?? '') . ', ' . 
            trim($usuario['departamento'] ?? '') . ' - ' . 
            trim($usuario['codigo_postal'] ?? '')
          ) 
        ?>
      </div>
      <button type="button" id="btn-editar-direccion" style="margin-top:5px; padding:0.25rem 0.5rem; font-size:0.75rem; background:var(--primary-color); color:white; border:none; border-radius:4px; cursor:pointer;">
        Editar
      </button>
    </div>
  </div>
</div>

        
     
        <div class="stats-panel">
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
      </div>

      
     
    </div>
  </div>

<!-- Modal Dirección -->
<div id="modal-direccion" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
  <div style="background:white; padding:2rem; border-radius:var(--radius-lg); max-width:500px; width:90%; position:relative;">
    <h2 style="margin-bottom:1rem;">Editar Dirección</h2>
    <form id="form-direccion" method="POST" action="../../controlador/actualizar_datos_c.php">
      <label for="calle" style="font-weight:600; margin-top:0.5rem;">Calle y número</label>
      <input type="text" name="calle" id="calle" value="<?= htmlspecialchars($usuario['calle'] ?? '') ?>" required
        style="width:100%; padding:0.5rem; margin-bottom:0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">

      <label for="ciudad" style="font-weight:600; margin-top:0.5rem;">Ciudad</label>
      <input type="text" name="ciudad" id="ciudad" value="<?= htmlspecialchars($usuario['ciudad'] ?? '') ?>" required
        style="width:100%; padding:0.5rem; margin-bottom:0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">

      <label for="codigo_postal" style="font-weight:600; margin-top:0.5rem;">Código Postal</label>
      <input type="text" name="codigo_postal" id="codigo_postal" value="<?= htmlspecialchars($usuario['codigo_postal'] ?? '') ?>" required maxlength="10"
        style="width:100%; padding:0.5rem; margin-bottom:0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">

      <label for="departamento" style="font-weight:600; margin-top:0.5rem;">Departamento / Estado</label>
      <input type="text" name="departamento" id="departamento" value="<?= htmlspecialchars($usuario['departamento'] ?? '') ?>" required
        style="width:100%; padding:0.5rem; margin-bottom:0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">

      <div style="margin-top:1rem; display:flex; justify-content:flex-end; gap:0.5rem;">
        <button type="button" id="btn-cerrar-modal" style="padding:0.5rem 1rem; border:none; background:#ef4444; color:white; border-radius:5px; cursor:pointer;">Cancelar</button>
        <button type="submit" style="padding:0.5rem 1rem; border:none; background:var(--primary-color); color:white; border-radius:5px; cursor:pointer;">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Teléfono -->
<div id="modal-telefono" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
  <div style="background:white; padding:2rem; border-radius:var(--radius-lg); max-width:400px; width:90%; position:relative;">
    <h2 style="margin-bottom:1rem;">Editar Teléfono</h2>
    <form id="form-telefono" method="POST" action="../../controlador/actualizar_datos_c.php">
      <label for="celular" style="font-weight:600; margin-top:0.5rem;">Número de celular</label>
      <input type="text" name="celular" id="celular" value="<?= htmlspecialchars($usuario['celular'] ?? '') ?>" required maxlength="15"
        style="width:100%; padding:0.5rem; margin-bottom:0.5rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">

      <div style="margin-top:1rem; display:flex; justify-content:flex-end; gap:0.5rem;">
        <button type="button" id="btn-cerrar-modal-tel" style="padding:0.5rem 1rem; border:none; background:#ef4444; color:white; border-radius:5px; cursor:pointer;">Cancelar</button>
        <button type="submit" style="padding:0.5rem 1rem; border:none; background:var(--primary-color); color:white; border-radius:5px; cursor:pointer;">Guardar</button>
      </div>
    </form>
  </div>
</div>


<script>
const modal = document.getElementById('modal-direccion');
const btnEditar = document.getElementById('btn-editar-direccion');
const btnCerrar = document.getElementById('btn-cerrar-modal');

btnEditar.addEventListener('click', () => {
    modal.style.display = 'flex';
});

btnCerrar.addEventListener('click', () => {
    modal.style.display = 'none';
});

// Cerrar modal si hace clic fuera del contenido
window.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
});

const modalTel = document.getElementById('modal-telefono');
const btnEditarTel = document.getElementById('btn-editar-telefono');
const btnCerrarTel = document.getElementById('btn-cerrar-modal-tel');

btnEditarTel.addEventListener('click', () => {
    modalTel.style.display = 'flex';
});

btnCerrarTel.addEventListener('click', () => {
    modalTel.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === modalTel) modalTel.style.display = 'none';
});

</script>


</body>
</html>