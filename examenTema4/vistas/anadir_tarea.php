<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Tarea</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
        crossorigin="anonymous">
</head>

<body class="container mt-4">

    <h2 class="mb-4">Añadir Nueva Tarea</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control" name="fecha" required>
        </div>

        <div class="form-group">
            <label for="hora">Hora:</label>
            <input type="time" class="form-control" name="hora" required>
        </div>

        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" class="form-control-file" name="imagen">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="prioridad">Prioridad:</label>
            <input type="number" class="form-control" name="prioridad" required>
        </div>

        <div class="form-group">
            <label for="lugar">Lugar:</label>
            <input type="text" class="form-control" name="lugar" required>
        </div>

        <div class="form-group">
            <label for="car_id">ID Categoría:</label>
            <input type="number" class="form-control" name="car_id" required>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Tarea</button>

        <div class="mt-3">
            <a href="vistas/inicio.php" class="btn btn-secondary">Volver al Menú de Inicio</a>
        </div>
    </form>

</body>

</html>