<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear cuenta</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  body{background:#f2e0c9;}
  .auth-wrap{max-width:520px;margin:60px auto;padding:24px;background:#fff;border:1px solid #e9e9e9;border-radius:14px;box-shadow:0 6px 22px rgba(0,0,0,.06)}
  h1{margin:0 0 12px 0;font-size:22px;color:#222}
  p.muted{color:#666;margin:0 0 14px 0}
  label{display:block;margin:10px 0 6px 0;font-weight:600}
  input{width:100%;padding:10px;border:1px solid #ddd;border-radius:10px}
  .row{display:flex;gap:10px;margin-top:14px}
  .btn{padding:10px 14px;border:0;border-radius:10px;background:#2f6b2f;color:#fff;text-decoration:none;text-align:center;cursor:pointer}
  .btn.grey{background:#777}
  .error{color:#a33;margin:10px 0 0}
  .ok{color:#2e7d32;margin:10px 0 0}
</style>
</head>
<body>
<div class="auth-wrap">
  <h1>Crear cuenta</h1>
  <p class="muted">Completa tus datos para registrarte. Tu rol será <strong>Usuario</strong>.</p>

  <?php if (!empty($err)): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if (!empty($msg)): ?><div class="ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <form method="post" action="index.php?controller=Auth&action=doRegistro">
    <label for="nombre">Nombre</label>
    <input id="nombre" name="nombre" type="text" required>

    <label for="apellido">Apellido</label>
    <input id="apellido" name="apellido" type="text" required>

    <label for="email">Correo electrónico</label>
    <input id="email" name="email" type="email" required placeholder="tu@correo.com">

    <div class="row" style="justify-content:flex-end">
      <a class="btn grey" href="index.php?controller=Auth&action=login" style="text-decoration:none">Cancelar</a>
      <button class="btn" type="submit">Crear cuenta</button>
    </div>
  </form>
</div>
</body>
</html>
