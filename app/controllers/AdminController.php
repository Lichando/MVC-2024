<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\InmobiliariaModel;
use app\models\PropiedadModel;
use app\models\UserModel;
use app\models\EstadisticaModel; // Modelo para obtener estadísticas

class AdminController extends Controller
{



    // Mostrar listado de todas las inmobiliarias
    public function actionInmobiliarias()
    {
        // Verificar que el usuario tiene permiso
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login'); // Redirigir si no tiene permisos
            return;
        }

        // Obtener todas las inmobiliarias
        $inmobiliarias = InmobiliariaModel::getAllInmobiliarias();

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias", [
            "title" => 'Inmobiliarias',
            "head" => $head,
            "header" => $header,
            "inmobiliarias" => $inmobiliarias
        ]);
    }


    // Eliminar una inmobiliaria
    public function actionEliminarInmobiliaria($id)
    {
        // Verificar que el usuario tiene permiso
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Eliminar la inmobiliaria
        InmobiliariaModel::deleteInmobiliaria($id);

        // Redirigir de vuelta a la lista de inmobiliarias
        Response::redirect('admin/inmobiliarias');
    }

    // Mostrar listado de todas las propiedades
    public function actionPropiedades()
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Obtener todas las propiedades
        $propiedades = PropiedadModel::getAllProperties();

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "propiedades", [
            "title" => 'Propiedades',
            "head" => $head,
            "header" => $header,
            "propiedades" => $propiedades
        ]);
    }

    // Eliminar una propiedad
    public function actionEliminarPropiedad($id)
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Eliminar la propiedad
        PropiedadModel::deleteProperty($id);

        // Redirigir de vuelta a la lista de propiedades
        Response::redirect('admin/propiedades');
    }

    // Mostrar listado de todos los usuarios
    public function actionUsuarios()
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Obtener todos los usuarios
        $usuarios = UserModel::getAllUsers();

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "usuarios", [
            "title" => 'Usuarios Registrados',
            "head" => $head,
            "header" => $header,
            "usuarios" => $usuarios
        ]);
    }

    // Eliminar un usuario
    public function actionEliminarUsuario($id)
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Eliminar el usuario
        UserModel::deleteUser($id);

        // Redirigir de vuelta a la lista de usuarios
        Response::redirect('admin/usuarios');
    }

    // Mostrar listado de corredores y agentes
    public function actionCorredoresAgentes()
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Obtener todos los corredores y agentes
        $corredoresAgentes = UserModel::getCorredoresAgentes();

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "corredores-agentes", [
            "title" => 'Corredores y Agentes Inmobiliarios',
            "head" => $head,
            "header" => $header,
            "corredoresAgentes" => $corredoresAgentes
        ]);
    }

    // Mostrar estadísticas de ventas por inmobiliaria
    public function actionEstadisticas()
    {
        if (!SessionController::EstaLogeado() || !in_array(SessionController::ObtenerRol(), [1, 2])) {
            Response::redirect('login');
            return;
        }

        // Obtener el ranking de inmobiliarias por propiedades vendidas
        $topInmobiliarias = EstadisticaModel::getTopInmobiliariasPorVentas();
        // Obtener el ranking de propiedades más vistas
        $topPropiedades = EstadisticaModel::getTopPropiedadesPorVistas();
        // Obtener el ranking de vendedores más consultados para una inmobiliaria específica
        $topVendedores = EstadisticaModel::getTopVendedoresPorConsultas($inmobiliariaId);  // Usa un ID de inmobiliaria
        // Obtener el ranking de inmobiliarias por puntuación
        $topInmobiliariasPorPuntuacion = EstadisticaModel::getTopInmobiliariasPorPuntuacion();

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "estadisticas", [
            "title" => 'Estadísticas y Rankings',
            "head" => $head,
            "header" => $header,
            "topInmobiliarias" => $topInmobiliarias,
            "topPropiedades" => $topPropiedades,
            "topVendedores" => $topVendedores,
            "topInmobiliariasPorPuntuacion" => $topInmobiliariasPorPuntuacion
        ]);
    }
}
