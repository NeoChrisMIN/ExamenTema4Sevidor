<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Editar Tarea</h2>

        <!-- Formulario para editar la tarea -->
        <form action="index.php?accion=editarTareaActualizarDatos" method="post" enctype="multipart/form-data">

            <!-- Campo oculto para el ID de la tarea -->
            <input type="hidden" name="idTarea" value="<?php echo isset($tarea['id']) ? $tarea['id'] : ''; ?>">

            <!-- Campos del formulario -->
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($tarea['titulo']) ? $tarea['titulo'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="idCategoria">ID Categoría:</label>
                <input type="text" class="form-control" id="idCategoria" name="idCategoria" value="<?php echo $tarea['car_id']; ?>">
            </div>

            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
                <p class="mt-2">Imagen actual: <?php echo isset($tarea['imagen']) ? $tarea['imagen'] : ''; ?></p>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion"><?php echo isset($tarea['descripcion']) ? $tarea['descripcion'] : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo isset($tarea['fecha']) ? date('Y-m-d', strtotime($tarea['fecha'])) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="hora">Hora:</label>
                <input type="time" class="form-control" id="hora" name="hora" value="<?php echo isset($tarea['hora']) ? date('H:i', strtotime($tarea['hora'])) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="lugar">Lugar:</label>
                <input type="text" class="form-control" id="lugar" name="lugar" value="<?php echo isset($tarea['lugar']) ? $tarea['lugar'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="prioridad">Prioridad:</label>
                <input type="text" class="form-control" id="prioridad" name="prioridad" value="<?php echo isset($tarea['prioridad']) ? $tarea['prioridad'] : ''; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>