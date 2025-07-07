<?php
$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EcoCanjeCR - Inicio</title>
    <style>
        /* Estilos embebidos */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');

        body {
            background-color: #f2e0c9;
            color: #262625;
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .contenedor {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 90%;
        }

        h1 {
            font-size: 3em;
            color: #718c49;
            margin-bottom: 10px;
        }

        .descripcion {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #8c8851;
        }

        .botones {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            background-color: #718c49;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 1em;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .btn:hover {
            background-color: #8c8851;
        }

        .contenido {
            margin: 40px auto;
            padding: 20px;
            max-width: 900px;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php if (!$controller): ?>
    <!-- Mostrar solo la portada -->
    <div class="contenedor">
        <h1>EcoCanjeCR</h1>
        <p class="descripcion">Sistema para la gestión de materiales reciclables y recompensas.</p>
        <div class="botones">
            <a href="index.php?controller=Usuario&action=index" class="btn">Gestión de Usuarios</a>
            <a href="index.php?controller=MaterialReciclado&action=index" class="btn">Materiales Reciclados</a>
            <a href="index.php?controller=Recompensa&action=index" class="btn">Canjes y Recompensas</a>
        </div>
    </div>
<?php else: ?>
    <!-- Ejecutar controlador normalmente -->
    <div class="contenido">
        <?php
        $archivo = "controllers/{$controller}Controller.php";
        if (file_exists($archivo)) {
            require_once $archivo;
            $nombreClase = $controller . "Controller";
            if (class_exists($nombreClase)) {
                $obj = new $nombreClase();
                $accion = $action ?? 'index';
                if (method_exists($obj, $accion)) {
                    $obj->$accion();
                } else {
                    echo "<p>Acción <strong>$accion</strong> no encontrada en $nombreClase.</p>";
                }
            } else {
                echo "<p>Controlador <strong>$nombreClase</strong> no válido.</p>";
            }
        } else {
            echo "<p>Archivo <strong>$archivo</strong> no encontrado.</p>";
        }
        ?>
    </div>
<?php endif; ?>

</body>
</html>

