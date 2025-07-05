<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recompensas Disponibles</title>
    <link rel="stylesheet" href="public/css/estilo.css">
</head>
<body>

<h2>Recompensas Disponibles</h2>
<a href="index.php?controller=MaterialReciclado&action=index" class="btn">Volver a Materiales</a>

<table>
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Puntos</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($recompensas as $r): ?>
        <tr>
            <td><?= $r['Nombre'] ?></td>
            <td><?= $r['Descripcion'] ?></td>
            <td><?= $r['Puntos_Necesarios'] ?></td>
            <td>
                <form method="post" action="index.php?controller=Recompensa&action=canjear">
                    <input type="hidden" name="recompensa" value="<?= $r['ID_Recompensa'] ?>">
                    <input type="number" name="usuario" placeholder="ID Usuario" required>
                    <button type="submit">Canjear</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>