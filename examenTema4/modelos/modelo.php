<?php

class modelo{

    // ------------ Contrustor ------------
    public function __construct() {
        $this->conectar();
      }

    // ------------ Conexión ------------
    private $conexion;
    // Parámetros de conexión a la base de datos 
    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPass = "";
    private $dbName = "bdToDoList";

    public function conectar() {
        try {
            $this->conexion = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo '<div class="alert ">' .
            "Se conecto correctamente!! :) <br/>" . '</div>';
            return TRUE;
        } catch (PDOException $ex) {
            echo '<div class="alert alert-danger">' .
            "No se pudo conectar a la BD de usuarios!! :( <br/>" . $ex->getMessage() . '</div>';
            return $ex->getMessage();
        }
    }

    /**
     * Función que nos permite conocer si estamos conectados o no a la base de datos.
     * Devuelve TRUE si se realizó correctamente y FALSE en caso contrario.
     * @return boolean
     */
    public function estaConectado() {
        if ($this->conexion) :
        return TRUE;
        else :
        return FALSE;
        endif;
    }

    
    // ------------ login ------------
    public function procesar_login(){
        require_once "includes/procesar_login.php";
    }
    
    public function datos_usuario(){
        $return = [
            "correcto" => FALSE,
            "id" => NULL,
            "usuario" => NULL,
            "rol" => NULL,
            "error" => NULL
        ];
        // Comprueba si se ha iniciado sesión, si no, inicia la sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica si el usuario está logueado
        if (isset($_SESSION['usuario_id'])) {
            $return["correcto"] = TRUE;
            // Accede a la información del usuario
            $return["id"] = $_SESSION['usuario_id'];
            $return["usuario"] = $_SESSION['usuario_nick'];
            $return["rol"] = $_SESSION['usuario_rol'];
            
        }
        return $return;
    }
    
    // ------------ listado entradas ------------
    public function obtenerTotalRegistros() {
        try {
            $totalRegistros = $this->conexion->query("SELECT COUNT(*) FROM tareas")->fetchColumn();
            return $totalRegistros;
        } catch (PDOException $ex) {
            return FALSE;
        }
    }

    public function obtenerTodasTareas($inicio, $resultadosPorPagina) {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
    
        try {
            $query = $this->conexion->prepare("SELECT * FROM tareas ORDER BY fecha DESC, hora DESC LIMIT :inicio, :resultadosPorPagina");
            $query->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $query->bindParam(':resultadosPorPagina', $resultadosPorPagina, PDO::PARAM_INT);
            $query->execute();
    
            if ($query->rowCount() > 0) {
                $return["correcto"] = TRUE;
                $return["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $ex) {
            $return["error"] = $ex->getMessage();
        }
    
        return $return;
    }

    
    // ------------ agregar entrada ------------

    public function agregarTarea($datos) {
        try {
            // Consulta para insertar la nueva tarea
            $conexion = $this->conexion;
            $query = $conexion->prepare("INSERT INTO tareas (fecha, hora, titulo, imagen, descripcion, prioridad, lugar, car_id) VALUES (:fecha, :hora, :titulo, :imagen, :descripcion, :prioridad, :lugar, :car_id)");
            $query->bindParam(':fecha', $datos["fecha"], PDO::PARAM_STR);
            $query->bindParam(':hora', $datos["hora"], PDO::PARAM_STR);
            $query->bindParam(':titulo', $datos["titulo"], PDO::PARAM_STR);
            $query->bindParam(':imagen', $datos["imagen"], PDO::PARAM_STR);
            $query->bindParam(':descripcion', $datos["descripcion"], PDO::PARAM_STR);
            $query->bindParam(':prioridad', $datos["prioridad"], PDO::PARAM_INT);
            $query->bindParam(':lugar', $datos["lugar"], PDO::PARAM_STR);
            $query->bindParam(':car_id', $datos["car_id"], PDO::PARAM_INT);
            $query->execute();
    
            // Redirige al usuario después de agregar la tarea
            header('Location: vistas/inicio.php');
            exit;
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    }

    // ------------ eliminar entrada ------------

    public function eliminarTarea($idTarea) {
        try {
            // Consulta para obtener la información de la tarea antes de la eliminación
            $queryTarea = $this->conexion->prepare("SELECT * FROM tareas WHERE id = :id");
            $queryTarea->bindParam(':id', $idTarea, PDO::PARAM_INT);
            $queryTarea->execute();
            $tarea = $queryTarea->fetch(PDO::FETCH_ASSOC);
    
            // Elimina la tarea con el ID proporcionado
            $queryEliminar = $this->conexion->prepare("DELETE FROM tareas WHERE id = :id");
            $queryEliminar->bindParam(':id', $idTarea, PDO::PARAM_INT);
            $queryEliminar->execute();
    
            // Elimina la imagen asociada si existe
            if (!empty($tarea['imagen'])) {
                $rutaImagen = 'imagenes/' . $tarea['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
    
            // Redirige al usuario después de la eliminación
            header('Location: ../index.php?accion=listado');
            exit;
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    }
    public function detalle_tarea($idTarea){
        try {
            // Consulta para obtener los detalles de la tarea con el ID proporcionado
            $queryTarea = $this->conexion->prepare("SELECT * FROM tareas WHERE id = :id");
            $queryTarea->bindParam(':id', $idTarea, PDO::PARAM_INT);
            $queryTarea->execute();

            // Verifica si se encontraron resultados
            if ($queryTarea->rowCount() > 0) {
                $tarea = $queryTarea->fetch(PDO::FETCH_ASSOC);

                // Consulta para obtener los detalles de la categoría asociada a la tarea
                $queryCategoria = $this->conexion->prepare("SELECT * FROM categorias WHERE id = :idCategoria");
                $queryCategoria->bindParam(':idCategoria', $tarea['car_id'], PDO::PARAM_INT);
                $queryCategoria->execute();
                $categoria = $queryCategoria->fetch(PDO::FETCH_ASSOC);

                require_once 'vistas/detalle_tarea.php';
            } else {
                echo '<p class="alert alert-info">No se encontró la tarea solicitada.</p>';
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    }


    public function editarTareaCargaDatos($idTarea) {
        try {
            // Verifica si se ha proporcionado un ID válido en la URL
            if ($idTarea !== null && is_numeric($idTarea)) {
                // Filtra y sanitiza el ID de la tarea
                $idTarea = filter_var($idTarea, FILTER_SANITIZE_NUMBER_INT);
    
                // Consulta para obtener los datos de la tarea con el ID proporcionado
                $query = $this->conexion->prepare("SELECT * FROM tareas WHERE id = :idTarea");
                $query->bindParam(':idTarea', $idTarea, PDO::PARAM_INT);
                $query->execute();
                $tarea = $query->fetch(PDO::FETCH_ASSOC);
    
                // Verifica si se encontraron resultados
                if ($query->rowCount() > 0) {
                    // Continúa con el código del formulario
                    require_once 'vistas/editar_tarea.php';
                } else {
                    // Si no se encontraron resultados, redirige a la página de inicio
                    echo '<p class="alert alert-info">No se encontró la tarea.</p>';
                    header("Location: vistas/inicio.php");
                    exit;
                }
            } else {
                // Si no se proporcionó un ID válido, redirige a inicio
                echo '<p class="alert alert-warning">ID de tarea no válido.</p>';
                header('Location: vistas/inicio.php');
                exit;
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    }


    public function editarTareaActualizarDatos($datos) {
        try {
            // Prepara la consulta para actualizar la tarea
            $query = $this->conexion->prepare("UPDATE tareas SET titulo = :titulo, imagen = :imagen, descripcion = :descripcion, fecha = :fecha, lugar = :lugar, prioridad = :prioridad, hora = :hora, car_id = :idCategoria WHERE id = :idTarea");
    
            // Asigna los valores a los parámetros de la consulta
            $query->bindParam(':titulo', $datos['titulo'], PDO::PARAM_STR);
            $query->bindParam(':imagen', $datos['imagen'], PDO::PARAM_STR);
            $query->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $query->bindParam(':fecha', $datos['fecha'], PDO::PARAM_STR);
            $query->bindParam(':lugar', $datos['lugar'], PDO::PARAM_STR);
            $query->bindParam(':prioridad', $datos['prioridad'], PDO::PARAM_INT);  // Asegúrate de tener un campo "prioridad" en tu tabla tareas
            $query->bindParam(':hora', $datos['hora'], PDO::PARAM_STR);  // Asegúrate de tener un campo "hora" en tu tabla tareas
            $query->bindParam(':idCategoria', $datos['idCategoria'], PDO::PARAM_INT);
            $query->bindParam(':idTarea', $datos['idTarea'], PDO::PARAM_INT);
    
            // Mueve el archivo a la carpeta
            move_uploaded_file($datos['imagen_tmp'], 'imagenes/' . $datos['imagen']);
    
            // Ejecuta la consulta
            $query->execute();
    
            // Redirige después de la actualización
            header('Location: ../index.php?accion=listado');
            exit;
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    }

}