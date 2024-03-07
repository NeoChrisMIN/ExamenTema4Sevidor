<?php

require_once 'modelos/modelo.php';

class controlador {

    // El el atributo $modelo es de la 'clase modelo' y será a través del que podremos 
    // acceder a los datos y las operaciones de la base de datos desde el controlador
    private $modelo;
    //$mensajes se utiliza para almacenar los mensajes generados en las tareas, 
    //que serán posteriormente transmitidos a la vista para su visualización
    private $mensajes;

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío
     */
    public function __construct() {
        $this->modelo = new modelo();
        $this->mensajes = [];
    }


    /**
    * Método que envía al usuario a la página de inicio del sitio y le asigna 
    * el título de manera dinámica
    */
    public function index() {
        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO"
        ];
        //Mostramos la página de inicio 
        include_once 'vistas/inicio.php';
    }


    // ------------ login ------------
    public function login(){
        header("Location: ../vistas/form_login.php");
    }

    public function procesar_login(){
        $this->modelo->procesar_login();
    }

    public function datos_usuario(){
        $datos = $this->modelo->datos_usuario();

        if ($datos["correcto"]){
            echo "<p>Usuario: " . $datos["usuario"] . "</p>";
            echo "<p>Rol: " . $datos["rol"] . "</p>";
        } else {
            $this -> login();
        }
    }

    // ------------ listado ------------
    public function listado() {
        $parametros = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        // Establece la cantidad de resultados por página
        $resultadosPorPagina = 5;

        // Obtiene el número total de registros
        $totalRegistros = $this->modelo->obtenerTotalRegistros();

        // Calcula el número total de páginas
        $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

        // Obtiene la página actual o la establece en 1 si no se proporciona
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

        // Calcula el número del primer resultado en la página actual
        $inicio = ($paginaActual - 1) * $resultadosPorPagina;

        $resultModelo = $this->modelo->obtenerTodasTareas($inicio, $resultadosPorPagina);

        if ($resultModelo["correcto"]) {
            $parametros["correcto"] = TRUE;
            $parametros["datos"] = $resultModelo["datos"];
        } else {
            $parametros["error"] = $resultModelo["error"];
        }

        include_once 'vistas/listado.php';
    }


    // ------------ agregar entrada ------------
    
    public function anadirTarea() {
        // Verifica si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Filtra y sanitiza los datos del formulario
            $datos = [
                "fecha" => filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING),
                "hora" => filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING),
                "titulo" => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING),
                "descripcion" => filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING),
                "prioridad" => filter_input(INPUT_POST, 'prioridad', FILTER_SANITIZE_NUMBER_INT),
                "lugar" => filter_input(INPUT_POST, 'lugar', FILTER_SANITIZE_STRING),
                "car_id" => filter_input(INPUT_POST, 'car_id', FILTER_SANITIZE_NUMBER_INT),
                "imagen" => ""
            ];
    
            // Verifica si se proporcionó una imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $imagenNombre = $_FILES['imagen']['name'];
                $imagenTemp = $_FILES['imagen']['tmp_name'];
                $datos["imagen"] = $imagenNombre;
                // Mueve la imagen a la carpeta de imágenes
                move_uploaded_file($imagenTemp, 'imagenes/' . $imagenNombre);
            } else {
                $imagenNombre = '';
            }
    
            $this->modelo->agregarTarea($datos);
        }
    
        include_once 'vistas/anadir_tarea.php';
    }

    // ------------ eliminar entrada ------------

    public function eliminarTarea() {
        // Obtener el ID desde la URL
        $idTarea = isset($_GET['id']) ? $_GET['id'] : null;
    
        // Validar que el ID está presente y es válido 
        if ($idTarea !== null && is_numeric($idTarea)) {
            // Mostrar la confirmación
            echo "<script>
                if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
                    window.location.href = 'index.php?accion=confirmarEliminacionTarea&id={$idTarea}';
                } else {
                    window.location.href = 'vistas/inicio.php';
                }
            </script>";
            exit;
        } else {
            // Manejar el caso donde el ID no es válido
            echo "Error: ID de tarea no válido";
            header("Location: vistas/inicio.php");
        }
    }
    public function confirmarEliminacionTarea() {
        // Obtener el ID desde la URL
        $idTarea = isset($_GET['id']) ? $_GET['id'] : null;

        // Validar que el ID está presente y es válido 
        if ($idTarea !== null && is_numeric($idTarea)) {
            $this->modelo->eliminarTarea($idTarea);
        } else {
            // Manejar el caso donde el ID no es válido
            echo "Error: ID de tarea no válido";
            header("Location: vistas/inicio.php");
        }
    }

    // ------------ detalle entrada ------------

    public function detalle_tarea(){
        // Obtener el ID desde la URL
        $idTarea = isset($_GET['id']) ? $_GET['id'] : null;

        // Validar que el ID está presente y es válido
        if ($idTarea !== null && is_numeric($idTarea)) {
            $this->modelo->detalle_tarea($idTarea);
        } else {
            // Manejar el caso donde el ID no es válido
            echo "Error: ID de tarea no válido";
            header("Location: vistas/inicio.php");
        }
    }


    // ------------ editar entrada ------------
    public function editarTareaCargaDatos() {
        // Obtener el ID desde la URL
        $idTarea = isset($_GET['id']) ? $_GET['id'] : null;
    
        // Validar que el ID está presente y es válido
        if ($idTarea !== null && is_numeric($idTarea)) {
            $this->modelo->editarTareaCargaDatos($idTarea);
        } else {
            // Manejar el caso donde el ID no es válido
            echo "Error: ID de tarea no válido";
            header("Location: vistas/inicio.php");
        }
    }


    public function editarTareaActualizarDatos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica si se han proporcionado todos los campos necesarios
            if (
                isset($_POST['idTarea'], $_POST['titulo'], $_FILES['imagen'], $_POST['descripcion'], $_POST['fecha'], $_POST['lugar'], $_POST['prioridad'], $_POST['hora'], $_POST['idCategoria'])
            ) {
                // Filtra y sanitiza los datos recibidos
                $datos = [
                    "idTarea" => filter_input(INPUT_POST, 'idTarea', FILTER_SANITIZE_NUMBER_INT),
                    "titulo" => filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING),
                    "descripcion" => filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING),
                    "imagen" => $_FILES['imagen']['name'],
                    "imagen_tmp" => $_FILES['imagen']['tmp_name'],
                    "fecha" => filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING),
                    "lugar" => filter_input(INPUT_POST, 'lugar', FILTER_SANITIZE_STRING),
                    "prioridad" => filter_input(INPUT_POST, 'prioridad', FILTER_SANITIZE_NUMBER_INT),
                    "hora" => filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING),
                    "idCategoria" => filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT)
                ];
    
                $this->modelo->editarTareaActualizarDatos($datos);
    
            } else {
                echo '<p class="alert alert-warning">Todos los campos son requeridos.</p>';
            }
        } else {
            header('Location: vistas/inicio.php');
            exit;
        }
    }
    
}


