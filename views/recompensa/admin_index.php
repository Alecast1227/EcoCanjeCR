<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recompensas (Admin)</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .container{max-width:1000px;margin:40px auto}
  table{width:100%;border-collapse:collapse;background:#fff}
  th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
  .actions{display:flex;gap:8px}
  .btn{padding:8px 12px;border-radius:8px;border:0;background:#2f6b2f;color:#fff;text-decoration:none;cursor:pointer}
  .btn.secondary{background:#455a64}
  .btn.warn{background:#b23b3b}
</style>
</head>
<body>
<div class="container">
  <h1>Recompensas (Admin)</h1>
  <a class="btn" href="index.php?controller=Recompensa&action=create">Nueva recompensa</a>
  <?php if (!empty($_GET['ok'])): ?><p style="color:green">Acción realizada con éxito.</p><?php endif; ?>
  <table>
    <thead>
      <tr><th>ID</th><th>Nombre</th><th>Puntos</th><th>Cantidad</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php foreach ($recompensas as $r): ?>
      <tr>
        <td><?= (int)$r['ID_Recompensa'] ?></td>
        <td><?= htmlspecialchars($r['Nombre']) ?></td>
        <td><?= (int)$r['Puntos_Requeridos'] ?></td>
        <td><?= (int)$r['Cantidad_Disponible'] ?></td>
        <td><?= ((int)$r['ID_Estado']===1?'Activa':'Inactiva') ?></td>
        <td class="actions">
          <a class="btn secondary" href="index.php?controller=Recompensa&action=edit&id=<?= (int)$r['ID_Recompensa'] ?>">Editar</a>
          <form method="post" action="index.php?controller=Recompensa&action=delete" onsubmit="return confirm('¿Desactivar recompensa?');" style="display:inline">
            <input type="hidden" name="id" value="<?= (int)$r['ID_Recompensa'] ?>">
            <button class="btn warn" type="submit">Desactivar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<a class="btn" style="background:#777" href="index.php?controller=Menu&action=home">← Menú</a>
</body>
</html>
