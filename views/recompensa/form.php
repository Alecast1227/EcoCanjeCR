<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= !empty($isEdit) ? 'Editar' : 'Nueva' ?> recompensa</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .container{max-width:720px;margin:40px auto;padding:20px;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.05)}
  .field{display:flex;flex-direction:column;margin:12px 0}
  label{font-weight:600;margin-bottom:6px}
  input[type='text'], textarea, input[type='number'], select{padding:10px;border:1px solid #ddd;border-radius:8px}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .actions{display:flex;gap:8px;margin-top:16px}
  .btn{padding:10px 14px;border-radius:10px;border:0;background:#2f6b2f;color:#fff;cursor:pointer}
  .btn.secondary{background:#455a64}
</style>
</head>
<body>
<div class="container">
  <h1><?= !empty($isEdit) ? 'Editar' : 'Nueva' ?> recompensa</h1>

  <form method="post" action="index.php?controller=Recompensa&action=<?= !empty($isEdit) ? 'update' : 'store' ?>">
    <?php if (!empty($isEdit) && !empty($recompensa['ID_Recompensa'])): ?>
      <input type="hidden" name="ID_Recompensa" value="<?= (int)$recompensa['ID_Recompensa'] ?>">
    <?php endif; ?>

    <div class="field">
      <label>Nombre</label>
      <input type="text" name="Nombre" required value="<?= htmlspecialchars($recompensa['Nombre'] ?? '') ?>">
    </div>

    <div class="field">
      <label>Descripción</label>
      <textarea name="Descripcion" rows="3"><?= htmlspecialchars($recompensa['Descripcion'] ?? '') ?></textarea>
    </div>

    <div class="row">
      <div class="field">
        <label>Puntos requeridos</label>
        <input type="number" name="Puntos_Requeridos" min="0" required value="<?= (int)($recompensa['Puntos_Requeridos'] ?? 0) ?>">
      </div>
      <div class="field">
        <label>Cantidad disponible</label>
        <input type="number" name="Cantidad_Disponible" min="0" required value="<?= (int)($recompensa['Cantidad_Disponible'] ?? 0) ?>">
      </div>
    </div>

    <div class="field">
      <label>Estado</label>
      <select name="ID_Estado">
        <?php 
          // $estados debe venir del controlador; si no existe, mostramos un fallback
          $estadoSel = (int)($recompensa['ID_Estado'] ?? 1);
          if (!empty($estados) && is_array($estados)):
            foreach ($estados as $e):
        ?>
          <option value="<?= (int)$e['ID_Estado'] ?>" <?= $estadoSel===(int)$e['ID_Estado']?'selected':'' ?>>
            <?= htmlspecialchars($e['Nombre']) ?>
          </option>
        <?php 
            endforeach; 
          else: 
        ?>
          <option value="<?= $estadoSel ?>"><?= $estadoSel ?></option>
        <?php endif; ?>
      </select>
    </div>

    <div class="actions">
      <!-- OJO: el botón SIEMPRE visible -->
      <button class="btn" type="submit" name="guardar" value="1">
        <?= !empty($isEdit) ? 'Actualizar' : 'Crear' ?>
      </button>
      <a class="btn secondary" href="index.php?controller=Recompensa&action=adminIndex">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>

