<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel principal</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  
  body{background:#f2e0c9;}
  .container{max-width:1100px;margin:40px auto;padding:0 16px;}
  .header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
  .header h1{margin:0;font-size:28px;font-weight:700;color:#222;}
  .actions{display:flex;gap:8px;align-items:center;}
  .btn{padding:9px 14px;border:0;border-radius:10px;background:#2f6b2f;color:#fff;text-decoration:none;cursor:pointer}
  .btn-outline{background:#fff;color:#333;border:1px solid #ddd;}
  .muted{color:#666;font-size:14px}

  .summary{display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:16px;}
  .card{background:#fff;border:1px solid #e9e9e9;border-radius:14px;padding:18px;box-shadow:0 4px 16px rgba(0,0,0,.05)}
  .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
  .stat .label{font-size:12px;color:#666;margin-bottom:4px;text-transform:uppercase;letter-spacing:.4px}
  .stat .value{font-size:18px;font-weight:600;color:#222}
  .role-badge{display:inline-block;padding:3px 8px;border-radius:999px;background:#eef5ee;color:#2f6b2f;font-size:12px;font-weight:600}

  .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
  .card.link{display:flex;flex-direction:column;gap:10px;transition:.2s ease box-shadow,.2s ease transform;text-decoration:none;color:inherit}
  .card.link:hover{box-shadow:0 8px 24px rgba(0,0,0,.08);transform:translateY(-2px)}
  .card h3{margin:0 0 6px 0;font-size:18px;color:#1f1f1f}
  .card p{margin:0 0 10px 0;color:#555;line-height:1.4}
  .card .cta{margin-top:auto;align-self:flex-start}
</style>
</head>
<body>
<main class="container">
  <?php
    $user = $_SESSION['user'] ?? [];
    $nombre = trim(($user['Nombre'] ?? '').' '.($user['Apellido'] ?? ''));
    $esAdmin = !empty($user) && (int)$user['ID_Tipo_Usuario']===1;
  ?>

  <header class="header">
    <div>
      <h1>Panel principal</h1>
      <div class="muted">Bienvenido<?= $nombre ? ', '.htmlspecialchars($nombre) : '' ?>.</div>
    </div>
    <div class="actions">
      <a class="btn btn-outline" href="index.php?controller=Auth&action=logout">Cerrar sesión</a>
    </div>
  </header>

  <section class="summary">
    <div class="card">
      <div class="stats">
        <div class="stat">
          <div class="label">Usuario</div>
          <div class="value"><?= htmlspecialchars($nombre ?: '—') ?></div>
        </div>
        <div class="stat">
          <div class="label">Rol</div>
          <div class="value">
            <span class="role-badge"><?= $esAdmin ? 'Administrador' : 'Usuario' ?></span>
          </div>
        </div>
        <div class="stat">
          <div class="label">Puntos disponibles</div>
          <div class="value"><?= isset($saldo) ? (int)$saldo : 0 ?></div>
        </div>
      </div>
    </div>
  </section>

  <section class="grid">
    <a class="card link" href="index.php?controller=MaterialReciclado&action=index">
      <h3>Materiales reciclados</h3>
      <p>Registra nuevas entregas y consulta tu historial.</p>
      <span class="btn cta">Abrir</span>
    </a>

    <?php if (!$esAdmin): ?>
  <a class="card link" href="index.php?controller=Recompensa&action=index">
    <h3>Recompensas</h3>
    <p>Explora las recompensas disponibles y canjea tus puntos.</p>
    <span class="btn cta">Abrir</span>
  </a>

  <a class="card link" href="index.php?controller=Recompensa&action=misCanjes">
    <h3>Mis canjes</h3>
    <p>Consulta el historial de canjes realizados.</p>
    <span class="btn cta">Abrir</span>
  </a>
<?php endif; ?>


    <?php if ($esAdmin): ?>
      <a class="card link" href="index.php?controller=Usuario&action=index">
        <h3>Gestión de usuarios</h3>
        <p>Administra perfiles, roles y estados de las cuentas.</p>
        <span class="btn cta">Abrir</span>
      </a>

      <?php if ($esAdmin): ?>
  <a class="card link" href="index.php?controller=Recompensa&action=adminIndex">
    <h3>Gestión de recompensas</h3>
    <p>Crear, editar y desactivar recompensas del catálogo.</p>
    <span class="btn cta">Abrir</span>
  </a>
<?php endif; ?>

    <?php endif; ?>
  </section>
</main>
</body>
</html>
