<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles de Tarea</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body class="container mt-4">

    <div>
        <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Detalles de Tarea</h2>
        <h3>Información de Tarea:</h3>
        <p><strong>ID:</strong> <?php echo $tarea['id']; ?></p>
        <p><strong>Título:</strong> <?php echo $tarea['titulo']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $tarea['descripcion']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $tarea['fecha']; ?></p>
        <p><strong>Hora:</strong> <?php echo $tarea['hora']; ?></p>
        <p><strong>Lugar:</strong> <?php echo $tarea['lugar']; ?></p>
        <div>
            <strong>Imagen:</strong>
            <img src="imagenes/<?php echo $tarea['imagen']; ?>" alt="Imagen" style="max-width: 200px; height: auto;">
        </div>

        <h3>Información de la Categoría:</h3>
        <p><strong>ID Categoría:</strong> <?php echo $categoria['id']; ?></p>
        <p><strong>Nombre de Categoría:</strong> <?php echo $categoria['nombre']; ?></p>
    </div>

</body>

</html>