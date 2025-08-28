<?php
session_start();

include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrito - NISSI Cerrajería</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #333;
      line-height: 1.5;
      padding: 2rem 1rem;
      min-height: 100vh;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
    }

    /* Header */
    .header {
      text-align: center;
      margin-bottom: 3rem;
      color: white;
    }

    .header h1 {
      font-size: 2.5rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* Sections */
    .section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      margin-bottom: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .section-title {
      padding: 1.5rem 2rem;
      border-bottom: 1px solid rgba(42, 82, 152, 0.1);
      font-size: 1.2rem;
      font-weight: 600;
      color: #2a5298;
      background: rgba(42, 82, 152, 0.05);
    }

    /* Table */
    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      padding: 1.2rem 2rem;
      text-align: left;
      font-weight: 500;
      color: #2a5298;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background: rgba(42, 82, 152, 0.05);
      border-bottom: 2px solid rgba(42, 82, 152, 0.1);
    }

    th:last-child {
      text-align: center;
      width: 60px;
    }

    td {
      padding: 1.5rem 2rem;
      border-bottom: 1px solid rgba(42, 82, 152, 0.08);
      color: #444;
    }

    tbody tr {
      transition: all 0.2s ease;
    }

    tbody tr:hover {
      background: rgba(42, 82, 152, 0.03);
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    /* Input */
    .quantity-input {
      width: 70px;
      padding: 0.6rem;
      border: 2px solid #e1e8ed;
      border-radius: 8px;
      text-align: center;
      font-size: 0.9rem;
      background: white;
      color: #2a5298;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .quantity-input:focus {
      outline: none;
      border-color: #2a5298;
      box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
    }

    /* Remove button */
    .remove-btn {
      width: 32px;
      height: 32px;
      border: none;
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .remove-btn:hover {
      background: #ef4444;
      color: white;
      transform: scale(1.05);
    }

    /* Price */
    .price {
      font-weight: 500;
      color: #2a5298;
    }

    .subtotal {
      font-weight: 600;
      color: #10b981;
      font-size: 1.05rem;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem 2rem;
      color: #9ca3af;
    }

    .empty-state h3 {
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: #6b7280;
    }

    .empty-state p {
      font-size: 0.95rem;
    }

    /* Total section */
    .total-section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 2.5rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
    }

    .total-info {
      display: flex;
      align-items: baseline;
      gap: 1rem;
    }

    .total-label {
      font-size: 1.3rem;
      font-weight: 500;
      color: #2a5298;
    }

    .total-amount {
      font-size: 2.5rem;
      font-weight: 600;
      color: #10b981;
      text-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
    }

    /* Button */
    .confirm-btn {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border: none;
      padding: 1.2rem 2.5rem;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .confirm-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .confirm-btn:active {
      transform: translateY(0);
    }

    .confirm-btn:disabled {
      background: #9ca3af;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Loading */
    .loading {
      opacity: 0.7;
    }

    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 0.5rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      body {
        padding: 1rem;
      }

      .header h1 {
        font-size: 2rem;
      }

      .header p {
        font-size: 1rem;
      }

      .section-title {
        padding: 1.2rem 1.5rem;
        font-size: 1.1rem;
      }

      th, td {
        padding: 1rem 1.5rem;
        font-size: 0.9rem;
      }

      .total-section {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
        padding: 2rem;
      }

      .total-amount {
        font-size: 2rem;
      }

      .confirm-btn {
        width: 100%;
        padding: 1rem 2rem;
      }
    }

    @media (max-width: 480px) {
      .section-title {
        padding: 1rem;
        font-size: 1rem;
      }

      th, td {
        padding: 0.8rem 1rem;
        font-size: 0.85rem;
      }

      .quantity-input {
        width: 60px;
        padding: 0.5rem;
      }

      .remove-btn {
        width: 28px;
        height: 28px;
        font-size: 0.9rem;
      }

      .total-section {
        padding: 1.5rem;
      }

      .total-amount {
        font-size: 1.8rem;
      }
    }

    /* Subtle animations */
    .section {
      animation: fadeInUp 0.6s ease-out;
      animation-fill-mode: both;
    }

    .section:nth-child(2) { animation-delay: 0.1s; }
    .section:nth-child(3) { animation-delay: 0.2s; }
    .total-section { animation-delay: 0.3s; }

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
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Carrito de Compras</h1>
      <p>Revisa tu pedido antes de confirmar</p>
    </div>

    <!-- Services -->
    <div class="section">
      <div class="section-title">Servicios</div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="datosServicios"></tbody>
        </table>
        <div id="emptyServices" class="empty-state" style="display: none;">
          <h3>No hay servicios</h3>
          <p>Los servicios aparecerán aquí</p>
        </div>
      </div>
    </div>

    <!-- Products -->
    <div class="section">
      <div class="section-title">Productos</div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="datosProductos"></tbody>
        </table>
        <div id="emptyProducts" class="empty-state" style="display: none;">
          <h3>No hay productos</h3>
          <p>Los productos aparecerán aquí</p>
        </div>
      </div>
    </div>

    <!-- Total -->
    <div class="total-section">
      <div class="total-info">
        <span class="total-label">Total:</span>
        <span class="total-amount">$<span id="total">0</span></span>
      </div>
      <button class="confirm-btn" onclick="enviarCompra()" id="confirmBtn">
        Confirmar Compra
      </button>
    </div>
  </div>

  <script>
  function enviarCompra() {
  if (!confirm("¿Confirmar compra?")) return;

  const confirmBtn = document.getElementById('confirmBtn');
  confirmBtn.innerHTML = '<span class="spinner"></span>Procesando...';
  confirmBtn.disabled = true;

  const productos = JSON.parse(sessionStorage.getItem("productos")) || [];
  const servicios = JSON.parse(sessionStorage.getItem("servicios")) || [];

  // Verificar si hay algo en el carrito
  if (productos.length === 0 && servicios.length === 0) {
    alert("No hay productos ni servicios en el carrito.");
    confirmBtn.innerHTML = 'Confirmar Compra';
    confirmBtn.disabled = false;
    return;
  }

  let total = 0;
  productos.forEach(p => total += Number(p.subtotal));
  servicios.forEach(s => total += Number(s.subtotal));

  const data = {
    total: total,
    productos: productos,
    servicios: servicios
  };

  fetch("controlador/carrito_c.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then(res => res.json())
    .then(respuesta => {
      console.log("Respuesta del servidor:", respuesta);

      if (respuesta.status === "ok") {
        alert("Compra registrada con éxito");

        // Construir mensaje de WhatsApp
        let mensaje = "Nuevo pedido - NISSI Cerrajería%0A";
        mensaje += "--------------------------------%0A";

        if (productos.length > 0) {
          mensaje += "*Productos:*%0A";
          productos.forEach(p => {
            mensaje += `- ${p.nombre} x${p.cantidad} = $${Number(p.subtotal).toLocaleString()}%0A`;
          });
        }

        if (servicios.length > 0) {
          mensaje += "%0A*Servicios:*%0A";
          servicios.forEach(s => {
            mensaje += `- ${s.nombre} x${s.cantidad} = $${Number(s.subtotal).toLocaleString()}%0A`;
          });
        }

        mensaje += "%0A--------------------------------%0A";
        mensaje += `*Total:* $${total.toLocaleString()}%0A`;
        mensaje += "Fecha: " + new Date().toLocaleDateString("es-CO");

        // Número de WhatsApp (con código de país, sin + ni espacios)
        let numero = "573001234567";
        let url = `https://api.whatsapp.com/send?phone=${numero}&text=${mensaje}`;

        window.open(url, "_blank");

        // Limpiar carrito y actualizar
        sessionStorage.removeItem("servicios");
        sessionStorage.removeItem("productos");
        mostrarDatos();

        setTimeout(() => {
          window.location.reload();
        }, 500);
      } else {
        alert("Necesitas iniciar sesión para realizar la compra.");
         window.location.href = "vistas/login.php"; 
      }
    })
    .catch(error => {
      alert("Error al enviar la compra.");
      console.error(error);
    })
    .finally(() => {
      confirmBtn.innerHTML = 'Confirmar Compra';
      confirmBtn.disabled = false;
    });
}


    function mostrarDatos() {
      let total = 0;
      let servicios = sessionStorage.getItem("servicios");
      servicios = JSON.parse(servicios);
      let dataS = "";
      
      const serviciosTable = document.getElementById("datosServicios");
      const emptyServices = document.getElementById("emptyServices");
      
      if (servicios != null && servicios.length > 0) {
        servicios.forEach((val) => {
          total += Number(val.subtotal);
          dataS += `
            <tr>
              <td>${val.nombre}</td>
              <td class="price">$${Number(val.precio).toLocaleString()}</td>
              <td>
                <input type="number" class="quantity-input" onChange="cambiarSubtotalS(event, '${val.id}')" value="${val.cantidad}" min="1">
              </td>
              <td class="subtotal">$${Number(val.subtotal).toLocaleString()}</td>
              <td style="text-align: center;">
                <button class="remove-btn" onClick="quitarS(event, '${val.id}')">×</button>
              </td>
            </tr>
          `;
        });
        serviciosTable.innerHTML = dataS;
        emptyServices.style.display = 'none';
      } else {
        serviciosTable.innerHTML = '';
        emptyServices.style.display = 'block';
      }

      let productos = sessionStorage.getItem("productos");
      productos = JSON.parse(productos);
      let dataP = "";
      
      const productosTable = document.getElementById("datosProductos");
      const emptyProducts = document.getElementById("emptyProducts");
      
      if (productos != null && productos.length > 0) {
        productos.forEach((val) => {
          total += Number(val.subtotal);
          dataP += `
            <tr>
              <td>${val.nombre}</td>
              <td class="price">$${Number(val.precio).toLocaleString()}</td>
              <td>
                <input type="number" class="quantity-input" onChange="cambiarSubtotalP(event, '${val.id}')" value="${val.cantidad}" min="1">
              </td>
              <td class="subtotal">$${Number(val.subtotal).toLocaleString()}</td>
              <td style="text-align: center;">
                <button class="remove-btn" onClick="quitarP(event, '${val.id}')">×</button>
              </td>
            </tr>
          `;
        });
        productosTable.innerHTML = dataP;
        emptyProducts.style.display = 'none';
      } else {
        productosTable.innerHTML = '';
        emptyProducts.style.display = 'block';
      }
      
      document.getElementById("total").innerHTML = total.toLocaleString();
      
      const confirmBtn = document.getElementById('confirmBtn');
      confirmBtn.disabled = total === 0;
    }

    function cambiarSubtotalP(event, id) {
      event.preventDefault();
      let cant = event.target.value;
      if (cant < 1) {
        cant = 1;
        event.target.value = 1;
      }
      
      let prods = sessionStorage.getItem("productos");
      prods = JSON.parse(prods);
      let prodsActualizados = prods.map((prod) => {
        if (prod.id === id) {
          let subt = Number(cant) * Number(prod.precio);
          return { ...prod, cantidad: Number(cant), subtotal: subt };
        }
        return prod;
      });
      prodsActualizados = JSON.stringify(prodsActualizados);
      sessionStorage.setItem("productos", prodsActualizados);
      mostrarDatos();
    }

    function quitarP(event, id) {
      event.preventDefault();
      let prods = JSON.parse(sessionStorage.getItem("productos"));
      const index = prods.findIndex((prod) => prod.id == id);
      if (index != -1) {
        prods.splice(index, 1);
      }
      sessionStorage.setItem("productos", JSON.stringify(prods));
      mostrarDatos();
    }

    function quitarS(event, id) {
      event.preventDefault();
      let servs = JSON.parse(sessionStorage.getItem("servicios"));
      const index = servs.findIndex((serv) => serv.id == id);
      if (index != -1) {
        servs.splice(index, 1);
      }
      sessionStorage.setItem("servicios", JSON.stringify(servs));
      mostrarDatos();
    }

function cambiarSubtotalP(event, id) {
  let cant = parseInt(event.target.value);
  if (isNaN(cant) || cant < 1) {
    cant = 1;
    event.target.value = 1;
  }

  let prods = JSON.parse(sessionStorage.getItem("productos")) || [];
  let prodsActualizados = prods.map((prod) => {
    if (prod.id == id) {   // usa == para evitar problemas entre string y number
      let subt = Number(cant) * Number(prod.precio);
      return { ...prod, cantidad: cant, subtotal: subt };
    }
    return prod;
  });

  sessionStorage.setItem("productos", JSON.stringify(prodsActualizados));
  mostrarDatos();
}


    mostrarDatos();

    function cambiarSubtotalS(event, id) {
  let cant = parseInt(event.target.value);
  if (isNaN(cant) || cant < 1) {
    cant = 1;
    event.target.value = 1;
  }

  let servs = JSON.parse(sessionStorage.getItem("servicios")) || [];
  let servsActualizados = servs.map((serv) => {
    if (serv.id == id) {  // usa == para evitar problemas entre string y number
      let subt = Number(cant) * Number(serv.precio);
      return { ...serv, cantidad: cant, subtotal: subt };
    }
    return serv;
  });

  sessionStorage.setItem("servicios", JSON.stringify(servsActualizados));
  mostrarDatos();
}

  </script>
</body>
</html>