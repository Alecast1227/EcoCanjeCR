<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis canjes</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .contenido{max-width:1000px;margin:24px auto;padding:8px}
  .btn{padding:8px 12px;border:0;border-radius:8px;background:#4a7c2c;color:#fff;text-decoration:none}
  table{width:100%;border-collapse:collapse}
  th,td{border-bottom:1px solid #eee;padding:8px}
  .top{display:flex;gap:8px;align-items:center;margin-bottom:12px}
</style>
</head>
<body>
<div class="contenido">
  <div class="top">
    <a class="btn" style="background:#777" href="index.php?controller=Menu&action=home">← Menú</a>
    <a class="btn" href="index.php?controller=Recompensa&action=index">Recompensas</a>
  </div>

  <h2>Mis canjes</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Recompensa</th>
        <th>Cantidad</th>
        <th>Puntos (u)</th>
        <th>Total puntos</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($canjes)): foreach ($canjes as $c): ?>
        <?php $total = (int)$c['Puntos_Requeridos'] * (int)$c['Cantidad']; ?>
        <tr>
          <td><?= (int)$c['ID_Canje'] ?></td>
          <td><?= htmlspecialchars($c['Fecha_Canje']) ?></td>
          <td>#<?= (int)$c['ID_Recompensa'] ?> — <?= htmlspecialchars($c['Nombre']) ?></td>
          <td><?= (int)$c['Cantidad'] ?></td>
          <td><?= (int)$c['Puntos_Requeridos'] ?></td>
          <td><?= $total ?></td>
          <td><?= ((int)$c['ID_Estado']===1?'Activo':'Inactivo') ?></td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="7">Sin canjes realizados aún.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
