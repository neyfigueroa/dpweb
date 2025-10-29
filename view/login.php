<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
  <title>Iniciar Sesión</title>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const base_url = '<?= BASE_URL; ?>';
  </script>

  <style>
     * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      overflow: hidden;
    }

    .box {
      position: relative;
      width: min(90%, 400px);
      height: 500px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      overflow: hidden;
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .box::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .box:hover::before {
      left: 100%;
    }

    .box form {
      position: absolute;
      inset: 2px;
      background: rgba(255, 255, 255, 0.95);
      padding: 50px 40px;
      border-radius: 18px;
      z-index: 2;
      display: flex;
      flex-direction: column;
      gap: 25px;
      box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .box form h2 {
      color: #333;
      font-weight: 700;
      text-align: center;
      letter-spacing: 0.1em;
      margin-bottom: 15px;
      font-size: 1.8em;
    }

    .inputBox {
      position: relative;
      width: 100%;
    }

    .inputBox input {
      width: 100%;
      padding: 15px 10px 10px;
      background: transparent;
      border: none;
      outline: none;
      color: #333;
      font-size: 1em;
      letter-spacing: 0.05em;
      transition: 0.3s;
      border-bottom: 2px solid #ddd;
    }

    .inputBox span {
      position: absolute;
      left: 10px;
      top: 15px;
      color: #999;
      font-size: 1em;
      pointer-events: none;
      transition: 0.3s;
    }

    .inputBox input:focus ~ span,
    .inputBox input:valid ~ span {
      transform: translateY(-25px);
      font-size: 0.8em;
      color: #667eea;
    }

    .inputBox i {
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      transition: 0.3s;
      pointer-events: none;
    }

    .inputBox input:focus ~ i,
    .inputBox input:valid ~ i {
      width: 100%;
    }

    .links {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }

    .links a {
      color: #666;
      text-decoration: none;
      font-size: 0.9em;
      transition: 0.3s;
    }

    .links a:hover {
      color: #667eea;
      text-decoration: underline;
    }

    button {
      border: none;
      outline: none;
      background: linear-gradient(135deg, #667eea, #764ba2);
      padding: 15px 0;
      color: #fff;
      font-weight: 600;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s;
      font-size: 1em;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    @media (max-width: 360px) {
      .box {
        width: 95%;
        height: auto;
        padding-bottom: 20px;
      }
      .box form {
        padding: 40px 30px;
      }
    }
  </style>
</head>

<body>

  <div class="box">
    <span class="borderLine"></span>
    <form id="frm_login" novalidate>
      <h2>Iniciar Sesión</h2>
      <div class="inputBox">
        <input type="text" id="username" name="username" autocomplete="username" required aria-required="true" />
        <span>Usuario</span>
        <i></i>
      </div>
      <div class="inputBox">
        <input type="password" id="password" name="password" autocomplete="current-password" required aria-required="true" />
        <span>Contraseña</span>
        <i></i>
      </div>
      <div class="links">
        <a href="#">¿Olvidaste tu contraseña?</a>
        <a href="#">Crear cuenta</a>
      </div>
      <button type="button" onclick="iniciar_sesion()">Ingresar</button>
    </form>
  </div>



   <script src="<?=BASE_URL; ?>view/function/user.js"> </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>