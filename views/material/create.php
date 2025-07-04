<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Material</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>
<center>
<h2>Nuevo material</h2>

<form method="post" action="index.php?controller=MaterialReciclado&action=guardar">
    <input name="tipo" placeholder="Tipo de material" required><br>
    <input name="peso" placeholder="Peso" type="number" step="0.01" required><br>
    <input name="fecha" type="date" required><br>
    <input name="puntos" placeholder="Puntos" type="number" required><br>
    <input name="usuario" placeholder="ID Usuario" type="number" required><br>
    <input name="centro" placeholder="ID Centro Acopio" type="number" required><br>
    <input name="estado" placeholder="ID Estado" type="number" required><br>
    <button type="submit">Guardar</button>
</form>
</center>
</body>
</html>

