<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo ingreso</title>
<link rel="stylesheet" href="public/css/estilo.css">
<style>
  .wrap{max-width:720px;margin:24px auto;padding:16px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.07)}
  label{display:block;margin:6px 0;font-weight:600}
  input,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:8px}
  .grid{display:grid;gap:12px;grid-template-columns:1fr 1fr}
  .full{grid-column:1/-1}
  .btn{padding:10px 14px;border:0;border-radius:8px;background:#4a7c2c;color:#fff}
  .top{max-width:720px;margin:24px auto 0;display:flex;gap:8px}
</style>
</head>
<body>
<div class="top">
  <a class="btn" style="background:#777" href="index.php?controller=Menu&action=home">← Menú</a>
  <a class="btn" href="index.php?controller=MaterialReciclado&action=index">Lista</a>
</div>

<div class="wrap">
  <h2>Nueva entrega</h2>
  <form method="post" action="index.php?controller=MaterialReciclado&action=guardar">
    <div class="grid">
      <div>
        <label for="tipo">Tipo</label>
        <select id="tipo" name="tipo" required>
          <option value="Plástico">Plástico</option>
          <option value="Cartón">Cartón</option>
          <option value="Vidrio">Vidrio</option>
        </select>
      </div>
      <div>
        <label for="tamano">Tamaño</label>
        <select id="tamano" name="tamano" required>
          <option>Pequeño</option>
          <option>Mediano</option>
          <option>Grande</option>
        </select>
      </div>
      <div>
        <label for="fecha">Fecha</label>
        <input id="fecha" name="fecha" type="date" required>
      </div>
      <div>
        <label for="centro">ID Centro de Acopio</label>
        <input id="centro" name="centro" type="number" min="1" required>
      </div>
      <div class="full">
        <label for="estado">Estado</label>
        <select id="estado" name="estado">
          <option value="1">Activo</option>
          <option value="2">Inactivo</option>
        </select>
      </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:12px;">
      <button class="btn" type="submit">Guardar</button>
      <a class="btn" style="background:#777" href="index.php?controller=MaterialReciclado&action=index">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
