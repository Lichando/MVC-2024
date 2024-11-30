<?php 
namespace app\controllers;

use \Controller;
use \Response;
use app\controllers\SessionController; 
use app\models\PropiedadModel; 
use app\models\InmobiliariaModel;

class PropiedadesController extends Controller
{
    // Método para listar todas las propiedades (todos pueden verlas)
    public function actionListar() {
        // Todos los usuarios pueden ver el listado de propiedades
        $propiedades = PropiedadModel::getAllProperties();  // Ver todas las propiedades

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "lista", [
            "title" => 'Listado de Propiedades',
            "head" => $head,
            "header" => $header,
            "propiedades" => $propiedades,
        ]);
    }

    // Método para mostrar detalles de una propiedad específica
    public function actionDetalle($id) {
        $propiedad = PropiedadModel::getPropertyById($id); 
        if (!$propiedad) {
            Response::redirect('/propiedad/listar'); 
        }

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "detalle", [
            "title" => 'Detalles de la Propiedad',
            "head" => $head,
            "header" => $header,
            "propiedad" => $propiedad,
        ]);
    }

    // Método para agregar una nueva propiedad (solo permitido para corredores y dueños)
    public function actionAgregar() {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [3, 5])) {
            Response::redirect('/account/login'); 
        }

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "agregar", [
            "title" => 'Agregar Nueva Propiedad',
            "head" => $head,
            "header" => $header,
        ]);
    }

    // Método para manejar el guardado de una nueva propiedad
    public function actionManejarAgregar() {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [3, 5])) {
            Response::redirect('/account/login');
        }

        $data = $_POST; // Sanitiza y valida
        if ($this->validarDatosPropiedad($data)) {
            // Agregar la propiedad a la base de datos
            PropiedadModel::Crear($data);
            Response::redirect('/propiedad/listar'); 
        } else {
            Response::render($this->viewDir(__NAMESPACE__), "agregar", [
                "title" => 'Agregar Nueva Propiedad',
                "head" => SiteController::head(),
                "header" => SiteController::header(),
                "error" => 'Los datos proporcionados son inválidos.',
            ]);
        }
    }

    // Método para editar una propiedad existente (solo permitido para corredores, agentes y dueños)
    public function actionEditar($id) {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [3, 4, 5])) {
            Response::redirect('/account/login'); 
        }

        $propiedad = PropiedadModel::getPropertyById($id);
        if (!$propiedad) {
            Response::redirect('/propiedad/listar'); 
        }

        // Verificar si el usuario tiene permiso para editar esta propiedad
        $userId = SessionController::getUserId();
        $inmobiliariaId = InmobiliariaModel::getInmobiliariaIdByUserId($userId);

        // Si el usuario es dueño, verificar que la propiedad pertenezca a su inmobiliaria
        if (SessionController::getRol() === 5 && $propiedad->duenioInmobiliariaÍndice != $inmobiliariaId) {
            Response::redirect('/propiedad/listar');
        }

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "editar", [
            "title" => 'Editar Propiedad',
            "head" => $head,
            "header" => $header,
            "propiedad" => $propiedad,
        ]);
    }

    // Método para manejar la actualización de una propiedad
    public function actionManejarEditar($id) {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [3, 4, 5])) {
            Response::redirect('/account/login'); 
        }

        $data = $_POST; // Sanitiza y valida
        if ($this->validarDatosPropiedad($data)) {
            // Actualizar la propiedad en la base de datos
            PropiedadModel::Actualizar($id, $data);
            Response::redirect('/propiedad/listar'); 
        } else {
            Response::render($this->viewDir(__NAMESPACE__), "editar", [
                "title" => 'Editar Propiedad',
                "head" => SiteController::head(),
                "header" => SiteController::header(),
                "error" => 'Los datos proporcionados son inválidos.',
                "propiedad" => $data,
            ]);
        }
    }

    // Método para dar de baja (inactivar) una propiedad (solo permitido para administradores, empleados y dueños)
    public function actionBaja($id) {
        // Verificar que el usuario tiene permiso
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [1, 2, 5])) {
            Response::redirect('/account/login');
        }

        // get la propiedad
        $propiedad = PropiedadModel::getPropertyById($id);
        if (!$propiedad) {
            Response::redirect('/propiedad/listar');
        }

        // Si es dueño, asegurarse de que la propiedad pertenece a su inmobiliaria
        if (SessionController::getRol() === 5 && $propiedad->duenioInmobiliariaÍndice != InmobiliariaModel::getInmobiliariaIdByUserId(SessionController::getUserId())) {
            Response::redirect('/propiedad/listar');
        }

        // Marcar la propiedad como inactiva
        PropiedadModel::bajaPropiedad($id);
        Response::redirect('/propiedad/listar');
    }

    // Método para validar los datos de la propiedad
    private function validarDatosPropiedad($data) {
        return !empty($data['nombre']) && !empty($data['precio']) && isset($data['tipo']);
    }
}
