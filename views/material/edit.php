<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Material</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>
<center>
<h2>Editar material</h2>

<form method="post" action="index.php?controller=MaterialReciclado&action=actualizar">
    <input type="hidden" name="id" value="<?= $material['ID_Material_Reciclado'] ?>">
    <input name="tipo" value="<?= $material['Tipo_Material'] ?>"><br>
    <input name="peso" value="<?= $material['Peso'] ?>"><br>
    <input name="fecha" value="<?= $material['Fecha_Reciclaje'] ?>"><br>
    <input name="puntos" value="<?= $material['Puntos_Asignados'] ?>"><br>
    <input name="usuario" value="<?= $material['ID_Usuario'] ?>"><br>
    <input name="centro" value="<?= $material['ID_Centro_Acopio'] ?>"><br>
    <input name="estado" value="<?= $material['ID_Estado'] ?>"><br>
    <button type="submit">Actualizar</button>
</form>
</center>
</body>
</html>

