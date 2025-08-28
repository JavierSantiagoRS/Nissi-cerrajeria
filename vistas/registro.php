<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="assets/css/crear/crear.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  </head>
  <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 20px 0;
}

/* Animated background elements */
body::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(241, 196, 15, 0.08) 0%, transparent 50%);
    animation: rotate 25s linear infinite;
    z-index: 0;
}

body::after {
    content: '';
    position: absolute;
    top: 15%;
    right: 15%;
    width: 80px;
    height: 80px;
    background: rgba(241, 196, 15, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
    z-index: 0;
}

/* Additional floating elements */
body::before {
    content: '';
    position: absolute;
    bottom: 20%;
    left: 10%;
    width: 60px;
    height: 60px;
    background: rgba(241, 196, 15, 0.08);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite reverse;
    z-index: 0;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) translateX(0px); }
    33% { transform: translateY(-15px) translateX(10px); }
    66% { transform: translateY(10px) translateX(-5px); }
}

.form-box {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 25px;
    padding: 45px 40px;
    width: 100%;
    max-width: 480px;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 1;
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Enhanced logo/icon effect for registration */
.form-box::before {
    content: '\f234';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: #f1c40f;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 10px 25px rgba(42, 82, 152, 0.3);
    animation: userPulse 2s ease-in-out infinite;
}

@keyframes userPulse {
    0%, 100% { 
        transform: translateX(-50%) scale(1);
        box-shadow: 0 10px 25px rgba(42, 82, 152, 0.3);
    }
    50% { 
        transform: translateX(-50%) scale(1.05);
        box-shadow: 0 15px 35px rgba(42, 82, 152, 0.4);
    }
}

.title {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #2a5298;
    text-align: center;
    margin-bottom: 8px;
    margin-top: 20px;
    position: relative;
}

.title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #f1c40f 0%, #f39c12 100%);
    border-radius: 2px;
}

.subtitle {
    display: block;
    font-size: 0.95rem;
    color: #e67e22;
    text-align: center;
    margin-bottom: 35px;
    font-weight: 500;
    padding: 12px 20px;
    background: rgba(230, 126, 34, 0.1);
    border-radius: 10px;
    border-left: 4px solid #e67e22;
    position: relative;
}

.subtitle::before {
    content: '\f071';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    margin-right: 8px;
    color: #e67e22;
}

.form-container {
    margin-bottom: 25px;
}

.input {
    width: 100%;
    padding: 16px 20px;
    margin-bottom: 18px;
    border: 2px solid #e1e8ed;
    border-radius: 15px;
    font-size: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    position: relative;
    outline: none;
}

.input:focus {
    border-color: #2a5298;
    background: white;
    box-shadow: 
        0 0 0 4px rgba(42, 82, 152, 0.1),
        0 5px 15px rgba(42, 82, 152, 0.1);
    transform: translateY(-2px);
}

.input:valid {
    border-color: #27ae60;
}

.input::placeholder {
    color: #999;
    font-weight: 400;
}

/* Enhanced input icons for different field types */
.input[name="nombre"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

.input[name="usuario"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

.input[name="celular"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

.input[name="clave"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23666' viewBox='0 0 24 24'%3E%3Cpath d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 20px;
    padding-right: 50px;
}

/* Input validation styles */
.input[type="email"]:invalid:not(:placeholder-shown) {
    border-color: #e74c3c;
    background-color: rgba(231, 76, 60, 0.05);
}

.input[type="number"]:invalid:not(:placeholder-shown) {
    border-color: #e74c3c;
    background-color: rgba(231, 76, 60, 0.05);
}

.btn {
    width: 100%;
    padding: 18px;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.btn-succes {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(39, 174, 96, 0.3);
}

.btn-succes:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(39, 174, 96, 0.4);
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
}

.btn-succes:active {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
}

/* Enhanced button ripple effect */
.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn:active::before {
    width: 300px;
    height: 300px;
}

.form-section {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e1e8ed;
}

.form-section p {
    color: #666;
    font-size: 15px;
    margin: 0;
}

.form-section a {
    color: #2a5298;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.form-section a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #f1c40f 0%, #f39c12 100%);
    transition: width 0.3s ease;
}

.form-section a:hover {
    color: #1e3c72;
}

.form-section a:hover::after {
    width: 100%;
}

/* Progress indicator for form completion */
.form-box::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #f1c40f 0%, #f39c12 100%);
    border-radius: 0 0 25px 25px;
    width: 0%;
    transition: width 0.3s ease;
}

.form-box:has(.input:valid) .form-box::after {
    width: 25%;
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding: 15px 0;
    }
    
    .form-box {
        margin: 15px;
        padding: 35px 25px;
        max-width: none;
    }
    
    .title {
        font-size: 1.8rem;
    }
    
    .subtitle {
        font-size: 0.9rem;
        padding: 10px 15px;
        margin-bottom: 30px;
    }
    
    .input {
        padding: 15px 18px;
        font-size: 15px;
        margin-bottom: 16px;
    }
    
    .btn {
        padding: 16px;
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .form-box {
        margin: 10px;
        padding: 30px 20px;
        border-radius: 20px;
    }
    
    .title {
        font-size: 1.6rem;
    }
    
    .subtitle {
        font-size: 0.85rem;
        padding: 8px 12px;
    }
    
    .input {
        padding: 14px 16px;
        margin-bottom: 15px;
        border-radius: 12px;
    }
    
    .btn {
        padding: 15px;
        border-radius: 12px;
    }
    
    .input[name="nombre"],
    .input[name="usuario"],
    .input[name="celular"],
    .input[name="clave"] {
        background-size: 18px;
        padding-right: 45px;
    }
}

/* Loading animation for button */
.btn.loading {
    pointer-events: none;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Enhanced focus indicators for accessibility */
.input:focus,
.btn:focus {
    outline: 2px solid #f1c40f;
    outline-offset: 2px;
}

/* Form validation feedback */
.input:valid:not(:placeholder-shown) {
    border-color: #27ae60;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%2327ae60' viewBox='0 0 24 24'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E");
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .form-box {
        border: 2px solid #000;
        background: #fff;
    }
    
    .input {
        border: 2px solid #000;
    }
    
    .btn-succes {
        background: #000;
        color: #fff;
    }
    
    .subtitle {
        background: #fff;
        border: 1px solid #000;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Custom number input styling */
.input[type="number"]::-webkit-outer-spin-button,
.input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.input[type="number"] {
    -moz-appearance: textfield;
}

/* Password strength indicator (visual only) */
.input[name="clave"]:focus {
    background-color: rgba(241, 196, 15, 0.05);
}

.input[name="clave"]:valid {
    background-color: rgba(39, 174, 96, 0.05);
}
  </style>
  <body>
    <div class="form-box">
        <a href="../index.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
    <form action="../controlador/usuarios_c.php?accion=registro" method="POST">
        <span class="title">Crear cuenta</span>
        <div class="form-container">
          <input type="email" name="nombre" class="input" placeholder="Email" required/>
          <input type="text" name="usuario" class="input" placeholder="Nombre Usuario" required/>
          <input type="number" name="celular" class="input" minlength="10" maxlength="10" placeholder="Celular" required/>
          <input type="password" name="clave" class="input" placeholder="Contraseña" required/>
          <input type="hidden" value="cliente">
        </div>
        <button type="submit" class="btn btn-succes">Crear</button>
      </form>
      <div class="form-section">
        <p>¿ya tienes cuenta? <a href="login.php">Acceder</a></p>
      </div>
    </div>
  </body>
</html>