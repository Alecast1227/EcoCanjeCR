<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  body{background:#f6f7f9;}
  .auth-wrap{max-width:420px;margin:60px auto;padding:24px;background:#fff;border:1px solid #e9e9e9;border-radius:14px;box-shadow:0 6px 22px rgba(0,0,0,.06)}
  h1{margin:0 0 12px 0;font-size:22px;color:#222}
  p.muted{color:#666;margin:0 0 14px 0}
  label{display:block;margin:10px 0 6px 0;font-weight:600}
  input{width:100%;padding:10px;border:1px solid #ddd;border-radius:10px}
  .row{display:flex;gap:10px;margin-top:14px}
  .btn{flex:1;padding:10px 14px;border:0;border-radius:10px;background:#2f6b2f;color:#fff;text-decoration:none;text-align:center;cursor:pointer}
  .btn-outline{background:#fff;color:#333;border:1px solid #ddd}
  .error{color:#a33;margin:10px 0 0}
</style>
</head>
<body>
<div class="auth-wrap">
  <h1>Iniciar sesión</h1>
  <p class="muted">Ingresa tu correo registrado para continuar.</p>

  <?php if (!empty($error)): ?>
    <div class="error">Correo no encontrado o inactivo.</div>
  <?php endif; ?>

  <form method="post" action="index.php?controller=Auth&action=doLogin">
    <label for="email">Correo electrónico</label>
    <input id="email" name="email" type="email" required placeholder="tu@correo.com">
    <div class="row">
      <button class="btn" type="submit">Ingresar</button>
      <a class="btn btn-outline" href="index.php?controller=Auth&action=registro">Crear cuenta</a>
    </div>
  </form>
</div>
</body>
</html>
