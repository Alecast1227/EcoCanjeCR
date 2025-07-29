

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de materiales reciclados</title>
    <link rel="stylesheet" href="public/css/estilo.css">
    <script>
        function confirmarEliminacion(id) {
            if (confirm("¿Estás seguro de eliminar el material #" + id + "?")) {
                window.location.href = "index.php?controller=MaterialReciclado&action=eliminar&id=" + id;
            }
        }
    </script>
</head>
<body>

<div class="contenido">
    <h2>Lista de materiales reciclados</h2>
    <a href="index.php?controller=MaterialReciclado&action=crear" class="btn">Agregar Nuevo</a>

    <table class="tabla-estilo">
        <thead>
            <tr>
                <th>ID</th><th>Tipo</th><th>Peso</th><th>Fecha</th><th>Puntos</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!isset($materiales) || !is_array($materiales)) $materiales = [];
            foreach ($materiales as $m): ?>
                <tr>
                    <td><?= $m['ID_Material_Reciclado'] ?></td>
                    <td><?= $m['Tipo_Material'] ?></td>
                    <td><?= $m['Peso'] ?></td>
                    <td><?= $m['Fecha_Reciclaje'] ?></td>
                    <td><?= $m['Puntos_Asignados'] ?></td>
                    <td>
                        <a href="index.php?controller=MaterialReciclado&action=editar&id=<?= $m['ID_Material_Reciclado'] ?>" class="btn">Editar</a>
                        <button class="btn" onclick="confirmarEliminacion(<?= $m['ID_Material_Reciclado'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn" style="background-color: #718c49; margin-top: 20px;">⬅ Volver al inicio</a>
</div>

</body>
</html>
