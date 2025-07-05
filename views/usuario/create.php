<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>
<center>
<h2>Nuevo Usuario</h2>

<form method="post" action="index.php?controller=Usuario&action=guardar">
    <input name="nombre" placeholder="Nombre" required><br>
    <input name="apellido" placeholder="Apellido" required><br>
    <input name="correo" placeholder="Correo Electrónico" type="email" required><br>
    <input name="telefono" placeholder="Teléfono" required><br>
    <input name="direccion" placeholder="Dirección" required><br>
    <input name="contrasena" placeholder="Contraseña" type="password" required><br>
    <button type="submit">Guardar</button>
</form>
</center>
</body>
</html>