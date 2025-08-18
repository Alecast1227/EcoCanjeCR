<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Materiales</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .contenido{max-width:1100px;margin:24px auto;padding:8px}
  .btn{padding:8px 12px;border:0;border-radius:8px;background:#4a7c2c;color:#fff;text-decoration:none}
  table{width:100%;border-collapse:collapse}
  th,td{border-bottom:1px solid #eee;padding:8px}
  .top{display:flex;gap:8px;align-items:center;margin-bottom:12px}
</style>
</head>
<body>
<?php
  // DEFINIR $esAdmin ANTES DE USARLO
  $user = $_SESSION['user'] ?? [];
  $esAdmin = !empty($user) && (int)$user['ID_Tipo_Usuario'] === 1;
?>
<div class="contenido">
  <div class="top">
    <a class="btn" style="background:#777" href="index.php?controller=Menu&action=home">← Menú</a>

    <?php if (!$esAdmin): ?>
      <a class="btn" href="index.php?controller=MaterialReciclado&action=crear">Ingresar registro</a>
    <?php endif; ?>
  </div>

  <h2>Material Reciclado</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Tipo</th><th>Peso (kg)</th><th>Fecha</th>
        <th>Puntos</th><th>Usuario</th><th>Centro</th>
        <?php if ($esAdmin): ?><th>Acciones</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($materiales)): foreach ($materiales as $m): ?>
        <tr>
          <td><?= (int)$m['ID_Material_Reciclado'] ?></td>
          <td><?= htmlspecialchars($m['Tipo_Material']) ?></td>
          <td><?= htmlspecialchars($m['Peso']) ?></td>
          <td><?= htmlspecialchars($m['Fecha_Registro']) ?></td>
          <td><?= (int)$m['Puntos_Asignados'] ?></td>
          <td><?= htmlspecialchars($m['Usuario_Nombre']) ?></td>
          <td><?= (int)$m['ID_Centro_Acopio'] ?></td>

          <?php if ($esAdmin): ?>
          <td style="display:flex;gap:6px;">
            <a class="btn" href="index.php?controller=MaterialReciclado&action=editar&id=<?= (int)$m['ID_Material_Reciclado'] ?>">Editar</a>
            <a class="btn" style="background:#a33" href="index.php?controller=MaterialReciclado&action=eliminar&id=<?= (int)$m['ID_Material_Reciclado'] ?>" onclick="return confirm('¿Eliminar #<?= (int)$m['ID_Material_Reciclado'] ?>?')">Eliminar</a>
          </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="<?= $esAdmin ? 8 : 7 ?>">Sin registros aún.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
