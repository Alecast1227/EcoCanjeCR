<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar usuario</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .wrap{max-width:720px;margin:24px auto;padding:16px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.07)}
  label{display:block;margin:6px 0;font-weight:600}
  input,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:8px}
  .grid{display:grid;gap:12px;grid-template-columns:1fr 1fr}
  .full{grid-column:1/-1}
  .btn{padding:10px 14px;border:0;border-radius:8px;background:#4a7c2c;color:#fff;text-decoration:none}
  .btn-grey{background:#777}
  .top{max-width:720px;margin:24px auto 0;display:flex;gap:8px}
</style>
</head>
<body>
<div class="top">
  <a class="btn btn-grey" href="index.php?controller=Usuario&action=index">← Volver</a>
</div>

<div class="wrap">
  <h2>Editar usuario #<?= (int)$u['ID_Usuario'] ?></h2>
  <form method="post" action="index.php?controller=Usuario&action=actualizar">
    <input type="hidden" name="id" value="<?= (int)$u['ID_Usuario'] ?>">

    <div class="grid">
      <div>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" value="<?= htmlspecialchars($u['Nombre']) ?>" required>
      </div>
      <div>
        <label for="apellido">Apellido</label>
        <input id="apellido" name="apellido" type="text" value="<?= htmlspecialchars($u['Apellido']) ?>" required>
      </div>

      <div>
        <label for="fecha">Fecha de registro</label>
        <input id="fecha" name="fecha" type="date" value="<?= htmlspecialchars($u['Fecha_Registro']) ?>" required>
      </div>
      <div>
        <label for="tipo">Tipo de usuario</label>
        <select id="tipo" name="tipo" required>
          <option value="1" <?= ((int)$u['ID_Tipo_Usuario']===1?'selected':'') ?>>Administrador</option>
          <option value="2" <?= ((int)$u['ID_Tipo_Usuario']===2?'selected':'') ?>>Usuario</option>
        </select>
      </div>

      <div class="full">
        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
          <option value="1" <?= ((int)$u['ID_Estado']===1?'selected':'') ?>>Activo</option>
          <option value="2" <?= ((int)$u['ID_Estado']===2?'selected':'') ?>>Inactivo</option>
        </select>
      </div>
    </div>

    <div style="display:flex;gap:8px;margin-top:12px;">
      <button class="btn" type="submit">Guardar cambios</button>
      <a class="btn btn-grey" href="index.php?controller=Usuario&action=index">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
