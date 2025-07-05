<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de materiales reciclados</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>

<h2>Lista de materiales reciclados</h2>
<a href="index.php?controller=MaterialReciclado&action=crear" class="btn">Agregar Nuevo</a>
<a href="index.php?controller=Usuario&action=index" class="btn">Ver Usuarios</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Peso</th>
        <th>Fecha</th>
        <th>Puntos</th>
        <th>Acciones</th>
    </tr>
    <?php 
    if (!isset($materiales) || !is_array($materiales)) {
        $materiales = [];
    }

    foreach ($materiales as $m): ?>
        <tr>
            <td><?= $m['ID_Material_Reciclado'] ?></td>
            <td><?= $m['Tipo_Material'] ?></td>
            <td><?= $m['Peso'] ?></td>
            <td><?= $m['Fecha_Reciclaje'] ?></td>
            <td><?= $m['Puntos_Asignados'] ?></td>
            <td>
                <a href="index.php?controller=MaterialReciclado&action=editar&id=<?= $m['ID_Material_Reciclado'] ?> "class="btn">Editar</a>
                <a href="index.php?controller=MaterialReciclado&action=eliminar&id=<?= $m['ID_Material_Reciclado'] ?>"class="btn">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
