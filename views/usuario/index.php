<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>

<h2>Lista de Usuarios</h2>
<a href="index.php?controller=Usuario&action=crear" class="btn">Agregar Nuevo</a>


<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Fecha Creación</th>
        <th>Acciones</th>
    </tr>
    <?php 

    if (!isset($usuarios) || !is_array($usuarios)) {
        $usuarios = [];
    }

    // The users are now loaded from the model, so we don't need to define them here
    foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['IdUsuario'] ?></td>
            <td><?= $u['Nombre'] ?></td>
            <td><?= $u['Apellido'] ?></td>
            <td><?= $u['Correo'] ?></td>
            <td><?= $u['Telefono'] ?></td>
            <td><?= $u['Direccion'] ?></td>
            <td><?= $u['FechaCreacion'] ?></td>
            <td>
                <a href="index.php?controller=Usuario&action=editar&id=<?= $u['IdUsuario'] ?> "class="btn">Editar</a>
                <a href="index.php?controller=Usuario&action=eliminar&id=<?= $u['IdUsuario'] ?>"class="btn">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="index.php" style="
    display: inline-block;
    margin-top: 20px;
    background-color: #718c49;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-family: 'Roboto', sans-serif;
">⬅ Volver al inicio</a>
</body>
</html>
