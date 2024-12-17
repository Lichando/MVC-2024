<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\InmobiliariaModel;
use app\models\PropiedadModel;
use app\models\UserModel;
use PDOException;

class InmobiliariaController extends Controller
{


    // Función para verificar si el usuario está logueado y tiene el rol correcto
    private function verificarAutenticacionYRol()
    {
        if (!SessionController::EstaLogeado()) {
            Response::redirect('login');
            return false;
        }

        $rol = SessionController::getRol();
        if (!in_array($rol, haystack: [3, 4, 5])) { // Roles de inmobiliaria: 3, 4, 5
            Response::redirect('login');
            return false;
        }

        return true;
    }

    // Acción para activar o desactivar una inmobiliaria
    public function actionCambiarEstado($id, $estado)
    {
        if (!$this->verificarAutenticacionYRol())
            return;

        // Verificar si el usuario es el dueño de la inmobiliaria
        if (!InmobiliariaModel::ValidarDuenio($id, SessionController::getUserId())) {
            Response::redirect('error'); // Permiso denegado
            return;
        }

        // Activar o desactivar según el estado
        if ($estado == 'activar') {
            InmobiliariaModel::activarInmobiliaria($id);
        } else {
            InmobiliariaModel::bajaInmobiliaria($id);
        }

        // Redirigir a la página de detalles de la inmobiliaria
        Response::redirect('inmobiliaria/details/' . $id);
    }

    public function actionAsignarRol($inmobiliariaId, $userEmail, $rol)
    {
        if (!$this->verificarAutenticacionYRol()) {
            return; // Verificar si el usuario está autenticado y tiene el rol necesario
        }

        try {
            // Asignar rol de Corredor o Agente
            InmobiliariaModel::asignarCorredorOAgente($inmobiliariaId, $userEmail, $rol);

            // Redirigir a la página de propiedades de la inmobiliaria
            Response::redirect('inmobiliaria/propiedades/' . $inmobiliariaId);
        } catch (PDOException $e) {
            // Manejo de errores y renderización de la vista de error
            Response::render("error", ['message' => $e->getMessage()]);
        }
    }


    public function actionPropiedades()
    {
        // Verificar autenticación y rol
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el ID de la inmobiliaria del usuario autenticado
        $userId = $_SESSION['user_id'] ?? null;
        $inmobiliariaId = UserModel::ObtenerInmobiliariaIdPorUsuario($userId);

        // Obtener todas las propiedades activas de la inmobiliaria
        $propiedades = PropiedadModel::ObtenerPropiedadesPorInmobiliaria($inmobiliariaId);

        // Verificar si no hay propiedades
        $mensajeCargarPropiedad = empty($propiedades) ? 'No tienes propiedades. ¡Cargar propiedad!' : '';

        // Renderizar la vista con las propiedades y el mensaje de feedback
        Response::render($this->viewDir(nameSpace: __NAMESPACE__), "lista", [
            "title" => 'Mis Propiedades Activas',
            'head' => SiteController::head(),
            'header' => SiteController::header(),
            'footer' => SiteController::footer(),
            'scripts'=>SiteController::scripts(),
            "propiedades" => $propiedades,
            "mensajeCargarPropiedad" => $mensajeCargarPropiedad,

        ]);
    }

    public function actionPropiedadesActivas()
    {
        // Verificar autenticación y rol
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el ID de la inmobiliaria del usuario autenticado
        $userId = $_SESSION['user_id'] ?? null;
        $inmobiliariaId = UserModel::ObtenerInmobiliariaIdPorUsuario($userId);

        // Obtener todas las propiedades activas de la inmobiliaria
        $propiedades = PropiedadModel::ObtenerPropiedadesActivasPorInmobiliaria($inmobiliariaId);

        // Verificar si no hay propiedades
        $mensajeCargarPropiedad = empty($propiedades) ? 'No tienes propiedades. ¡Cargar propiedad!' : '';

        // Obtener el estado desde la URL para mostrar un mensaje de feedback
        $status = $_GET['status'] ?? null;
        $mensajeFeedback = null;

        if ($status === 'success') {
            $mensajeFeedback = 'La propiedad se desactivó con éxito.';
        } elseif ($status === 'error') {
            $mensajeFeedback = 'Hubo un error al intentar desactivar la propiedad.';
        }

        // Si se realiza una solicitud AJAX para desactivar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idpropiedad']) && isset($_POST['idinmobiliaria']) && isset($_POST['accion']) && $_POST['accion'] === 'desactivar') {
            $idpropiedad = $_POST['idpropiedad'];
            $idinmobiliaria = $_POST['idinmobiliaria'];

            try {
                // Llamar a un método para desactivar la propiedad
                $resultado = PropiedadModel::bajaPropiedad($idpropiedad, estado: '0'); // Cambiar estado a 0 para desactivada

                // Devolver una respuesta JSON
                if ($resultado) {
                    echo json_encode(['success' => true, 'mensaje' => 'La propiedad se desactivó con éxito.']);
                } else {
                    echo json_encode(['success' => false, 'mensaje' => 'Hubo un error al intentar desactivar la propiedad.']);
                }
            } catch (PDOException $e) {
                // Manejo de errores
                echo json_encode(['success' => false, 'mensaje' => 'Hubo un error al procesar la solicitud.']);
            }

            // Finalizar el script, ya que estamos respondiendo con JSON
            exit;
        }

        // Renderizar la vista con las propiedades y el mensaje de feedback
        Response::render($this->viewDir(nameSpace: __NAMESPACE__), "listaActivas", [
            "title" => 'Mis Propiedades Activas',
            'head' => SiteController::head(),
            'header' => SiteController::header(),
            'footer' => SiteController::footer(),
            'scripts'=>SiteController::scripts(),
            "propiedades" => $propiedades,
            "mensajeCargarPropiedad" => $mensajeCargarPropiedad,
            "mensajeFeedback" => $mensajeFeedback,
        ]);
    }

    public function actionPropiedadesInactivas()
    {
        // Verificar autenticación y rol
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el ID de la inmobiliaria del usuario autenticado
        $userId = $_SESSION['user_id'] ?? null;
        $inmobiliariaId = UserModel::ObtenerInmobiliariaIdPorUsuario($userId);

        // Obtener todas las propiedades activas de la inmobiliaria
        $propiedades = PropiedadModel::ObtenerPropiedadesInactivasPorInmobiliaria($inmobiliariaId);

        // Verificar si no hay propiedades
        $mensajeCargarPropiedad = empty($propiedades) ? 'No tienes propiedades. ¡Cargar propiedad!' : '';

        // Obtener el estado desde la URL para mostrar un mensaje de feedback
        $status = $_GET['status'] ?? null;
        $mensajeFeedback = null;

        if ($status === 'success') {
            $mensajeFeedback = 'La propiedad se activó con éxito.';
        } elseif ($status === 'error') {
            $mensajeFeedback = 'Hubo un error al intentar activar la propiedad.';
        }

        // Si se realiza una solicitud AJAX para desactivar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idpropiedad']) && isset($_POST['idinmobiliaria']) && isset($_POST['accion']) && $_POST['accion'] === 'activar') {
            $idpropiedad = $_POST['idpropiedad'];
            $idinmobiliaria = $_POST['idinmobiliaria'];

            try {
                // Llamar a un método para desactivar la propiedad
                $resultado = PropiedadModel::AltaPropiedad($idpropiedad, estado: '1'); // Cambiar estado a 0 para desactivada

                // Devolver una respuesta JSON
                if ($resultado) {
                    echo json_encode(['success' => true, 'mensaje' => 'La propiedad se activó con éxito.']);
                } else {
                    echo json_encode(['success' => false, 'mensaje' => 'Hubo un error al intentar desactivar la propiedad.']);
                }
            } catch (PDOException $e) {
                // Manejo de errores
                echo json_encode(['success' => false, 'mensaje' => 'Hubo un error al procesar la solicitud.']);
            }

            // Finalizar el script, ya que estamos respondiendo con JSON
            exit;
        }

        // Renderizar la vista con las propiedades y el mensaje de feedback
        Response::render($this->viewDir(nameSpace: __NAMESPACE__), "listaInactivas", [
            "title" => 'Mis Propiedades Activas',
            'head' => SiteController::head(),
            'header' => SiteController::header(),
            'footer' => SiteController::footer(),
            'scripts'=>SiteController::scripts(),
            "propiedades" => $propiedades,
            "mensajeCargarPropiedad" => $mensajeCargarPropiedad,
            "mensajeFeedback" => $mensajeFeedback,
        ]);
    }
    // Acción para cargar una nueva propiedad
    public function actionCargar()
    {
        if (!$this->verificarAutenticacionYRol()) {
            return;
        }

        // Obtener el ID de la inmobiliaria desde el usuario autenticado
        $userId = $_SESSION['user_id'] ?? null;
        $inmobiliariaId = UserModel::ObtenerInmobiliariaIdPorUsuario($userId);

        // Obtener el nombre de la inmobiliaria desde la base de datos
        $inmobiliariaNombre = UserModel::ObtenerInmobiliariaNombre($inmobiliariaId);

        // Guardar el nombre de la inmobiliaria en la sesión
        $_SESSION['inmobiliaria_nombre'] = $inmobiliariaNombre;

        // Si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del formulario
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $direccionFicticia = $_POST['direccionFake'] ?? '';
            $direccionReal = $_POST['direccionTrue'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $banos = $_POST['banos'] ?? '';
            $ambientes = $_POST['ambientes'] ?? '';
            $dormitorios = $_POST['dormitorios'] ?? '';
            $metros = $_POST['metros'] ?? '';
            $moneda = $_POST['moneda'] ?? '';

            // Verificar que todos los campos estén completos
            $camposVacios = [];
            $erroresValidacion = [];

            if (empty($descripcion)) {
                $camposVacios[] = 'Descripción';
            } elseif (strlen($descripcion) > 255) {
                $erroresValidacion[] = 'La descripción no puede tener más de 255 caracteres.';
            }

            if (empty($precio)) {
                $camposVacios[] = 'Precio';
            } elseif (!is_numeric($precio)) {
                $erroresValidacion[] = 'El precio debe ser un número válido.';
            }

            if (empty($direccionFicticia)) {
                $camposVacios[] = 'Dirección Ficticia';
            } elseif (strlen($direccionFicticia) > 100) {
                $erroresValidacion[] = 'La dirección ficticia no puede tener más de 100 caracteres.';
            }

            if (empty($direccionReal)) {
                $camposVacios[] = 'Dirección Real';
            } elseif (strlen($direccionReal) > 100) {
                $erroresValidacion[] = 'La dirección real no puede tener más de 100 caracteres.';
            } elseif (!preg_match('/[A-Za-z]/', $direccionReal) || !preg_match('/\d/', $direccionReal)) {
                $erroresValidacion[] = 'La dirección real debe incluir al menos una letra y un número.';
            }

            if (empty($estado)) {
                $camposVacios[] = 'Estado';
            }

            if (empty($banos)) {
                $camposVacios[] = 'Baños';
            }

            if (empty($ambientes)) {
                $camposVacios[] = 'Ambientes';
            }

            if (empty($dormitorios)) {
                $camposVacios[] = 'Dormitorios';
            }

            if (empty($metros)) {
                $camposVacios[] = 'Metros';
            } elseif (!is_numeric($metros)) {
                $erroresValidacion[] = 'Los metros deben ser un número válido.';
            }

            if (empty($moneda)) {
                $camposVacios[] = 'Moneda';
            }

            // Si hay campos vacíos, devolver un error
            if (!empty($camposVacios)) {
                $msgError = 'Los siguientes campos son obligatorios: ' . implode(', ', $camposVacios) . '.';
            }

            // Si hay errores de validación, devolver un error
            if (!empty($erroresValidacion)) {
                $msgError = isset($msgError) ? $msgError . ' ' : '';
                $msgError .= implode(' ', $erroresValidacion);
            }

            if (!empty($msgError)) {
                return Response::render($this->viewDir(__NAMESPACE__), "cargar", [
                    'error' => $msgError, // Pasa el mensaje de error a la vista
                    'title' => 'Cargar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    'inmobiliariaNombre' => $inmobiliariaNombre,
                    'userId' => $userId,
                    'head' => SiteController::head(),
                    'scripts'=>SiteController::scripts(),
                    'header' => SiteController::header(),
                    'footer' => SiteController::footer(),
                ]);
            }
            // Validar si las imágenes han sido subidas correctamente
            $imagenes = [];
            if (isset($_FILES['imagen1']) && $_FILES['imagen1']['error'] == 0) {
                $imagenes[] = $_FILES['imagen1'];
            }
            if (isset($_FILES['imagen2']) && $_FILES['imagen2']['error'] == 0) {
                $imagenes[] = $_FILES['imagen2'];
            }
            if (isset($_FILES['imagen3']) && $_FILES['imagen3']['error'] == 0) {
                $imagenes[] = $_FILES['imagen3'];
            }

            // Si no se subieron imágenes, mostrar error
            if (count($imagenes) == 0) {
                $msgError = 'Debe cargar al menos una imagen.';
                return Response::render($this->viewDir(__NAMESPACE__), "cargar", [
                    'error' => $msgError, // Pasa el mensaje de error a la vista
                    'title' => 'Cargar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    'inmobiliariaNombre' => $inmobiliariaNombre,
                    'userId' => $userId,
                    'head' => SiteController::head(),
                    'header' => SiteController::header(),
                    'scripts'=>SiteController::scripts(),
                    'footer' => SiteController::footer(),
                ]);
            }

            // Procesar las imágenes y guardar sus rutas
            $imagenesRutas = $this->procesarImagenes($inmobiliariaNombre, $inmobiliariaId, $direccionFicticia, $imagenes);

            try {
                // Llamar al modelo para crear la propiedad
                PropiedadModel::crearPropiedad(
                    $inmobiliariaId,
                    $direccionFicticia,  // Corregir el nombre de la variable
                    $direccionReal,
                    $moneda,
                    $precio,
                    $descripcion,
                    $imagenesRutas[0], // Imagen 1
                    $imagenesRutas[1] ?? null, // Imagen 2
                    $imagenesRutas[2] ?? null, // Imagen 3
                    $estado,
                    1, // Estado activo, se puede ajustar
                    $banos,
                    $ambientes,
                    $dormitorios,
                    $metros
                );

                // Redirigir a la lista de propiedades
                Response::redirect('../inmobiliaria/propiedades');
            } catch (PDOException $e) {
                // Manejo de errores y mostrar mensaje
                $msgError = 'Error al crear la propiedad: ' . $e->getMessage();
                return Response::render($this->viewDir(__NAMESPACE__), "cargar", [
                    'error' => $msgError, // Pasa el mensaje de error a la vista
                    'title' => 'Cargar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    'inmobiliariaNombre' => $inmobiliariaNombre,
                    'userId' => $userId,
                    'head' => SiteController::head(),
                    'header' => SiteController::header(),
                    'scripts'=>SiteController::scripts(),
                    'footer' => SiteController::footer(),
                ]);
            }
        }

        // Renderizar la vista de cargar propiedad si no se envió el formulario
        Response::render($this->viewDir(__NAMESPACE__), "cargar", [
            'title' => 'Cargar Propiedad',
            'inmobiliariaId' => $inmobiliariaId,
            'inmobiliariaNombre' => $inmobiliariaNombre,
            'userId' => $userId,
            'head' => SiteController::head(),
            'header' => SiteController::header(),
            'scripts'=>SiteController::scripts(),
            'footer' => SiteController::footer(),
        ]);
    }


    // Función para procesar y guardar las imágenes
    private function procesarImagenes($inmobiliariaNombre, $inmobiliariaId, $direccionFicticia, $imagenes)
    {
        $imagenesRutas = [];
        $fecha = date('Ymd_His');  // Obtener la fecha actual para usarla en los nombres de las imágenes

        // Directorio donde se guardarán las imágenes
        $directorio = "uploads/$inmobiliariaNombre";


        // Crear el directorio si no existe
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);  // Crear el directorio con permisos
        }

        // Procesar cada imagen
        foreach ($imagenes as $index => $file) {
            // Verificar el tipo de archivo
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                // Mostrar mensaje de error si el formato no es válido
                Response::render($this->viewDir(__NAMESPACE__), "cargar", [
                    "title" => 'Cargar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    "inmobiliariaNombre" => $inmobiliariaNombre,
                    "head" => SiteController::head(),
                    "header" => SiteController::header(),
                    'scripts'=>SiteController::scripts(),
                    "footer" => SiteController::footer(),
                    "error" => 'El formato de las imágenes debe ser PNG, JPG o JPEG.'
                ]);
                return;
            }

            // Renombrar la imagen
            $nuevoNombre = $direccionFicticia . "_" . $fecha . "_img" . ($index + 1) . "." . $ext;
            $rutaDestino = $directorio . "/" . $nuevoNombre;

            // Mover la imagen desde su ubicación temporal a la carpeta de destino
            if (!move_uploaded_file($file['tmp_name'], $rutaDestino)) {
                Response::render($this->viewDir(__NAMESPACE__), "cargar", [
                    "title" => 'Cargar Propiedad',
                    "inmobiliariaNombre" => $inmobiliariaNombre,
                    "head" => SiteController::head(),
                    "header" => SiteController::header(),
                    'scripts'=>SiteController::scripts(),
                    "footer" => SiteController::footer(),
                    "error" => 'Error al guardar la imagen: ' . $file['name']
                ]);
                return;
            }

            // Guardar la ruta de la imagen
            $imagenesRutas[] = $rutaDestino;
        }

        return $imagenesRutas;
    }

    public function actionEditar($idpropiedad = null)
    {
        if (!$this->verificarAutenticacionYRol()) {
            return;
        }
    
        // Obtener el ID de la inmobiliaria desde el usuario autenticado
        $userId = $_SESSION['user_id'] ?? null;
        $inmobiliariaId = UserModel::ObtenerInmobiliariaIdPorUsuario($userId);
    
        // Asegurarse de que el parámetro 'idpropiedad' está presente en la URL
        if ($idpropiedad === null) {
            $msgError = 'El ID de la propiedad es obligatorio.';
            return Response::render($this->viewDir(__NAMESPACE__), "editar", [
                'error' => $msgError,
                'title' => 'Editar Propiedad',
                'inmobiliariaId' => $inmobiliariaId,
                'inmobiliariaNombre' => UserModel::ObtenerInmobiliariaNombre($inmobiliariaId),
                'userId' => $userId,
                'head' => SiteController::head(),
                'header' => SiteController::header(),
                'scripts'=>SiteController::scripts(),
                'footer' => SiteController::footer(),
            ]);
        }
    
        // Obtener la propiedad desde el modelo
        $propiedad = PropiedadModel::ObtenerPropiedadPorId($idpropiedad);
        if (!$propiedad) {
            $msgError = 'La propiedad no fue encontrada.';
            return Response::render($this->viewDir(__NAMESPACE__), "editar", [
                'error' => $msgError,
                'title' => 'Editar Propiedad',
                'inmobiliariaId' => $inmobiliariaId,
                'inmobiliariaNombre' => UserModel::ObtenerInmobiliariaNombre($inmobiliariaId),
                'userId' => $userId,
                'head' => SiteController::head(),
                'header' => SiteController::header(),
                'scripts'=>SiteController::scripts(),
                'footer' => SiteController::footer(),
            ]);
        }
    
        // Verificar si la propiedad pertenece a la inmobiliaria del usuario
        if ($propiedad->id_inm != $inmobiliariaId) {
            $msgError = 'No tienes permisos para editar esta propiedad.';
            return Response::render($this->viewDir(__NAMESPACE__), "editar", [
                'error' => $msgError,
                'title' => 'Editar Propiedad',
                'inmobiliariaId' => $inmobiliariaId,
                'inmobiliariaNombre' => UserModel::ObtenerInmobiliariaNombre($inmobiliariaId),
                'userId' => $userId,
                'head' => SiteController::head(),
                'header' => SiteController::header(),
                'footer' => SiteController::footer(),
                'scripts'=>SiteController::scripts(),
            ]);
        }
    
        // Obtener el nombre de la inmobiliaria
        $inmobiliariaNombre = UserModel::ObtenerInmobiliariaNombre($inmobiliariaId);
        $_SESSION['inmobiliaria_nombre'] = $inmobiliariaNombre;
    
        // Si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del formulario
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $direccionFicticia = $_POST['direccionFake'] ?? '';
            $direccionReal = $_POST['direccionTrue'] ?? '';
            $estado = $_POST['estado'] ?? '';
            $banos = $_POST['banos'] ?? '';
            $ambientes = $_POST['ambientes'] ?? '';
            $dormitorios = $_POST['dormitorios'] ?? '';
            $metros = $_POST['metros'] ?? '';
            $moneda = $_POST['moneda'] ?? '';
    
            // Validar campos
            $camposVacios = [];
            $erroresValidacion = [];
    
            if (empty($descripcion)) {
                $camposVacios[] = 'Descripción';
            }
    
            if (empty($precio) || !is_numeric($precio)) {
                $erroresValidacion[] = 'El precio debe ser un número válido.';
            }
    
            if (empty($direccionFicticia)) {
                $camposVacios[] = 'Dirección Ficticia';
            }
    
            if (empty($direccionReal) || !preg_match('/[A-Za-z]/', $direccionReal) || !preg_match('/\d/', $direccionReal)) {
                $erroresValidacion[] = 'La dirección real debe incluir al menos una letra y un número.';
            }
    
            if (empty($estado) || empty($banos) || empty($ambientes) || empty($dormitorios) || empty($metros)) {
                $camposVacios[] = 'Campos obligatorios incompletos';
            }
    
            if (!empty($camposVacios) || !empty($erroresValidacion)) {
                $msgError = 'Los siguientes campos son obligatorios: ' . implode(', ', $camposVacios);
                $msgError .= implode(' ', $erroresValidacion);
                return Response::render($this->viewDir(__NAMESPACE__), "editar", [
                    'error' => $msgError,
                    'title' => 'Editar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    'inmobiliariaNombre' => $inmobiliariaNombre,
                    'userId' => $userId,
                    'head' => SiteController::head(),
                    'header' => SiteController::header(),
                    'footer' => SiteController::footer(),
                    'scripts'=>SiteController::scripts(),
                ]);
            }
    
            // Datos a actualizar
            $data = [
                'direccionFake' => $direccionFicticia,
                'direccionTrue' => $direccionReal,
                'moneda' => $moneda,
                'precio' => $precio,
                'descripcion' => $descripcion,
                'id_estado' => $estado,
                'banos' => $banos,
                'ambientes' => $ambientes,
                'dormitorios' => $dormitorios,
                'metros' => $metros
            ];
    
            try {
                // Actualizar la propiedad en la base de datos
                PropiedadModel::actualizarPropiedad($idpropiedad, $data);
    
                // Redirigir después de la actualización
                Response::redirect('../../inmobiliaria/propiedades');
            } catch (PDOException $e) {
                $msgError = 'Error al actualizar la propiedad: ' . $e->getMessage();
                return Response::render($this->viewDir(__NAMESPACE__), "editar", [
                    'error' => $msgError,
                    'title' => 'Editar Propiedad',
                    'inmobiliariaId' => $inmobiliariaId,
                    'inmobiliariaNombre' => $inmobiliariaNombre,
                    'userId' => $userId,
                    'head' => SiteController::head(),
                    'header' => SiteController::header(),
                    'footer' => SiteController::footer(),
                    'scripts'=>SiteController::scripts(),
                ]);
            }
        }
    
        // Renderizar la vista de editar propiedad
        Response::render($this->viewDir(__NAMESPACE__), "editar", [
            'title' => 'Editar Propiedad',
            'idpropiedad' => $idpropiedad,
            'inmobiliariaId' => $inmobiliariaId,
            'inmobiliariaNombre' => $inmobiliariaNombre,
            'propiedad' => $propiedad,
            'userId' => $userId,
            'head' => SiteController::head(),
            'header' => SiteController::header(),
            'footer' => SiteController::footer(),
            'scripts'=>SiteController::scripts(),
        ]);
    }
    

    public static function ContadorInmobiliarias()
    {
        return InmobiliariaModel::contarInmobiliarias();
    }

    public static function inmobiliariasActivas()
    {
        return InmobiliariaModel::ObtenerInmobiliariasActivas();
    }

    public static function ContadorPropiedadesActivasInmo($inmobiliariaId)
    {
        return InmobiliariaModel::ObtenerPropiedadesActivasInmo($inmobiliariaId);
    }
    public static function ContadorPropiedadesInactivasInmo($inmobiliariaId)
    {
        return InmobiliariaModel::ObtenerPropiedadesInactivasInmo($inmobiliariaId);
    }

    public static function ContadorPropiedadesTotalesInmo($inmobiliariaId)
    {
        return InmobiliariaModel::ObtenerPropiedadesTotalesInmo($inmobiliariaId);
    }

    


}
