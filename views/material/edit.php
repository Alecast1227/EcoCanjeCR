
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Material</title>
    <link rel="stylesheet" href="public/css/estilo.css">
    <script>
        function validarEdicion() {
            const campos = ['tipo', 'peso', 'fecha', 'puntos', 'usuario', 'centro', 'estado'];
            for (let campo of campos) {
                let valor = document.forms["editForm"][campo].value;
                if (valor === "") {
                    alert("Por favor complete el campo: " + campo);
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>
<center>
    <h2>Editar material</h2>

    <form name="editForm" method="post" action="index.php?controller=MaterialReciclado&action=actualizar" onsubmit="return validarEdicion()">
        <input type="hidden" name="id" value="<?= $material['ID_Material_Reciclado'] ?>">
        <input name="tipo" value="<?= $material['Tipo_Material'] ?>" required><br>
        <input name="peso" value="<?= $material['Peso'] ?>" type="number" step="0.01" required><br>
        <input name="fecha" value="<?= $material['Fecha_Reciclaje'] ?>" type="date" required><br>
        <input name="puntos" value="<?= $material['Puntos_Asignados'] ?>" type="number" required><br>
        <input name="usuario" value="<?= $material['ID_Usuario'] ?>" type="number" required><br>
        <input name="centro" value="<?= $material['ID_Centro_Acopio'] ?>" type="number" required><br>
        <input name="estado" value="<?= $material['ID_Estado'] ?>" type="number" required><br>
        <button type="submit">Actualizar</button>
    </form>
</center>
</body>
</html>
