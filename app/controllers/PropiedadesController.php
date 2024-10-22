<?php 
namespace app\controllers;

use \Controller;
use \Response;
use app\controllers\SessionController; // Para gestionar sesiones
use app\models\PropiedadModel; // Asegúrate de tener el modelo correcto
class PropiedadesController extends Controller
{
    // Método para listar todas las propiedades
    public function actionList() {
        $propiedades = PropiedadModel::getAllProperties(); // Obtiene todas las propiedades
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
    public function actionDetail($id) {
        $propiedad = PropiedadModel::getPropertyById($id); // Obtiene la propiedad por ID
        if (!$propiedad) {
            Response::redirect('/propiedad/lista'); // Redirige si la propiedad no existe
        }
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "detail", [
            "title" => 'Detalles de la Propiedad',
            "head" => $head,
            "header" => $header,
            "propiedad" => $propiedad,
        ]);
    }

    // Método para agregar una nueva propiedad
    public function actionAdd() {
        if (!SessionController::isAuthenticated() || !SessionController::hasRole('corredor')) {
            Response::redirect('/account/login'); // Asegúrate de que el usuario tenga el rol adecuado
        }
        
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "add", [
            "title" => 'Agregar Nueva Propiedad',
            "head" => $head,
            "header" => $header,
        ]);
    }

    // Método para manejar el guardado de una nueva propiedad
    public function actionHandleAdd() {
        // Aquí manejarías la lógica para guardar la propiedad en la base de datos
        $data = $_POST; // Sanitiza y valida
        PropiedadModel::create($data);
        Response::redirect('/propiedad/lista'); // Redirige al listado de propiedades
    }
}
