<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recompensas</title>
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
    <a class="btn" href="index.php?controller=Recompensa&action=misCanjes"> Mis canjes</a>
  </div>

  <h2>Recompensas</h2>
  <p><strong>Mis puntos:</strong> <?= (int)$saldo ?></p>
  <?php if (isset($_GET['ok'])): ?><p style="color:#2e7d32"> Canje realizado.</p><?php endif; ?>
  <table>
    <thead>
      <tr><th>Recompensa</th><th>Descripción</th><th>Stock</th><th>Puntos</th><th>Canjear</th></tr>
    </thead>
    <tbody>
      <?php foreach ($recompensas as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['Nombre']) ?></td>
          <td><?= htmlspecialchars($r['Descripcion']) ?></td>
          <td><?= (int)$r['Cantidad_Disponible'] ?></td>
          <td><?= (int)$r['Puntos_Requeridos'] ?></td>
          <td>
            <?php if ($saldo >= (int)$r['Puntos_Requeridos']): ?>
              <form method="post" action="index.php?controller=Recompensa&action=canjear" style="display:flex;gap:6px;align-items:center">
                <input type="hidden" name="id_recompensa" value="<?= (int)$r['ID_Recompensa'] ?>">
                <input type="number" name="cantidad" value="1" min="1" max="<?= (int)$r['Cantidad_Disponible'] ?>" style="width:70px">
                <button class="btn" type="submit">Canjear</button>
              </form>
            <?php else: ?>
              <span style="opacity:.6">Insuficiente</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
