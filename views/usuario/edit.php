<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>
<center>
<h2>Editar Usuario</h2>

<form method="post" action="index.php?controller=Usuario&action=actualizar">
    <input type="hidden" name="id" value="<?= $usuario['IdUsuario'] ?>">
    <input name="nombre" value="<?= $usuario['Nombre'] ?>" required><br>
    <input name="apellido" value="<?= $usuario['Apellido'] ?>" required><br>
    <input name="correo" value="<?= $usuario['Correo'] ?>" type="email" required><br>
    <input name="telefono" value="<?= $usuario['Telefono'] ?>" required><br>
    <input name="direccion" value="<?= $usuario['Direccion'] ?>" required><br>
    <input name="contrasena" value="<?= $usuario['Contrasena'] ?>" type="password" required><br>
    <button type="submit">Actualizar</button>
</form>
</center>
</body>
</html>