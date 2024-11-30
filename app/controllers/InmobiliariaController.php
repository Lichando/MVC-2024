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
        if (!in_array($rol, [3, 4, 5])) { // Roles de inmobiliaria: 3, 4, 5
            Response::redirect('login');
            return false;
        }

        return true;
    }

    // Acción para el dashboard de la inmobiliaria
    public function actionDashboard()
    {
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el id de la inmobiliaria desde la sesión
        $inmobiliariaId = $_SESSION['user_id'];
        $inmobiliaria = InmobiliariaModel::getInmobiliariasId($inmobiliariaId);
        $propiedades = PropiedadModel::getPropiedadPorId($inmobiliariaId);


        // Renderizar el dashboard
        Response::render($this->viewDir(__NAMESPACE__), "dashboard", [
            "title" => 'Dashboard Inmobiliaria',
            "inmobiliaria" => $inmobiliaria,
            "propiedades" => $propiedades,
            "head" => SiteController::head(),
            "header" => SiteController::header(),
            "footer" => SiteController::footer(),
        ]);
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


    // Acción para cargar una nueva propiedad
    public function actionCargar()
    {
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el ID de la inmobiliaria desde el usuario autenticado
        $userId = $_SESSION['user_id'] ?? null; // O el método que uses para obtener el usuario autenticado
        $inmobiliariaId = UserModel::getInmobiliariaIdPorUsuario($userId);


        // Si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos de la propiedad desde el formulario
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $direccion = $_POST['direccion'];
            $tipo = $_POST['tipo'];
            

            try {
                // Llamar al modelo para crear la propiedad
                PropiedadModel::crearPropiedad($nombre, $descripcion, $precio, $direccion, $tipo, $inmobiliariaId);

                // Redirigir a la lista de propiedades
                Response::redirect('inmobiliaria/lista' . $inmobiliariaId);
            } catch (PDOException $e) {
                // Manejo de errores
                Response::render("error", ['message' => $e->getMessage()]);
            }
        }

        // Renderizar la vista de cargar propiedad
        Response::render($this->viewDir(__NAMESPACE__), "cargar", [
            "title" => 'Cargar Propiedad',
            "inmobiliariaId" =>$inmobiliariaId,
            "userId"=>$userId,
            "head" => SiteController::head(),
            "header" => SiteController::header(),
            "footer" => SiteController::footer(),
        ]);
    }

    // Acción para listar las propiedades de la inmobiliaria
    public function actionPropiedades()
    {
        if (!$this->verificarAutenticacionYRol())
            return;

        // Obtener el ID de la inmobiliaria desde el usuario autenticado
        $userId = $_SESSION['user_id'] ?? null; // O el método que uses para obtener el usuario autenticado
        $inmobiliariaId = UserModel::getInmobiliariaIdPorUsuario($userId);

        // Obtener todas las propiedades de la inmobiliaria
        $propiedades = PropiedadModel::getPropiedadesPorInmobiliaria($inmobiliariaId);

        // Verificar si no hay propiedades cargadas
        $mensajeCargarPropiedad = empty($propiedades) ? 'No tienes propiedades. ¡Cargar propiedad!' : '';


        // Renderizar la vista de listado de propiedades
        Response::render($this->viewDir(nameSpace: __NAMESPACE__), "lista", [
            "title" => 'Mis Propiedades',
            "propiedades" => $propiedades,
            "mensajeCargarPropiedad" => $mensajeCargarPropiedad,
            "head" => SiteController::head(),
            "header" => SiteController::header(),
            "footer" => SiteController::footer(),
        ]);
    }



    // Acción para eliminar una propiedad
    public function actionEliminarPropiedad($id)
    {
        if (!$this->verificarAutenticacionYRol())
            return;

        // Verificar si el usuario es el dueño de la propiedad
        $inmobiliariaId = SessionController::getUserId();
        $propiedad = PropiedadModel::getPropiedadPorId($id);

        if ($propiedad['inmobiliaria_id'] !== $inmobiliariaId) {
            Response::redirect('error'); // Permiso denegado
            return;
        }

        // Eliminar la propiedad
        try {
            PropiedadModel::BajaPropiedad($id);
            Response::redirect('inmobiliaria/lista' . $inmobiliariaId);
        } catch (PDOException $e) {
            Response::render("error", ['message' => $e->getMessage()]);
        }
    }
}
