<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de usuarios</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .contenido{max-width:1000px;margin:24px auto;padding:8px}
  .badge{padding:2px 6px;border-radius:6px;background:#eee}
  .btn{padding:8px 12px;border:0;border-radius:8px;background:#4a7c2c;color:#fff;text-decoration:none}
  table{width:100%;border-collapse:collapse}
  th,td{border-bottom:1px solid #eee;padding:8px}
  .top{display:flex;gap:8px;align-items:center;margin-bottom:12px}
  .btn-grey{background:#777}
  .btn-danger{background:#a33}
  .muted{color:#777}
</style>
</head>
<body>
<div class="contenido">
  <div class="top">
    <a class="btn btn-grey" href="index.php?controller=Menu&action=home">← Menú</a>
  </div>

  <h2>Gestión de usuarios</h2>

  <?php if (isset($_GET['upd'])): ?>
    <p class="<?= $_GET['upd']?'':'muted' ?>" style="color:<?= $_GET['upd']?'#2e7d32':'#a33' ?>;">
      <?= $_GET['upd']?'Cambios guardados correctamente.':'No fue posible guardar los cambios.' ?>
    </p>
  <?php endif; ?>

  <?php if (isset($_GET['deact'])): ?>
    <p style="color:<?= $_GET['deact']?'#2e7d32':'#a33' ?>;">
      <?= $_GET['deact']?'Usuario desactivado.':'No se pudo desactivar el usuario.' ?>
    </p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Apellido</th><th>Tipo</th><th>Estado</th><th>Registro</th><th style="width:220px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $u): ?>
        <?php $activo = ((int)$u['ID_Estado']===1); ?>
        <tr>
          <td><?= (int)$u['ID_Usuario'] ?></td>
          <td><?= htmlspecialchars($u['Nombre']) ?></td>
          <td><?= htmlspecialchars($u['Apellido']) ?></td>
          <td><?= ((int)$u['ID_Tipo_Usuario']===1?'Admin':'Usuario') ?></td>
          <td><span class="badge"><?= $activo?'Activo':'Inactivo' ?></span></td>
          <td><?= htmlspecialchars($u['Fecha_Registro']) ?></td>
          <td style="display:flex;gap:6px;">
            <a class="btn <?= $activo?'':'btn-grey' ?>" 
               href="<?= $activo ? 'index.php?controller=Usuario&action=editar&id='.(int)$u['ID_Usuario'] : 'javascript:void(0);' ?>"
               <?= $activo ? '' : 'style="pointer-events:none;opacity:.5;"' ?>>Editar</a>

            <?php if ($activo): ?>
              <a class="btn btn-danger"
                 href="index.php?controller=Usuario&action=eliminar&id=<?= (int)$u['ID_Usuario'] ?>"
                 onclick="return confirm('¿Desactivar al usuario #<?= (int)$u['ID_Usuario'] ?>?');">
                Desactivar
              </a>
            <?php else: ?>
              <span class="muted">—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
