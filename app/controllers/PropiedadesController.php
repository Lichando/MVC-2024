<?php
namespace app\controllers;
use app\models\PropiedadModel;
use app\models\UserModel;
use \Controller;
use \Response;


class PropiedadesController extends Controller
{

    // Constructor
    public function __construct()
    {

    }
    public function actionLista()
    {
        // Obtener los parámetros del POST, si existen
        $tipoBusqueda = $_POST['tipo-busqueda'] ?? '';
        $cantidadBanos = $_POST['cantidad-banos'] ?? '';
        $cantidadDormitorios = $_POST['cantidad-dormitorios'] ?? '';
        $direccion = $_POST['direccion'] ?? '';

        // Sanitizar los valores (opcional, dependiendo de tu caso de uso)
        $tipoBusqueda = (in_array($tipoBusqueda, [1, 2, 3])) ? $tipoBusqueda : ''; // Asegurarse que sea 1, 2 o 3
        $cantidadBanos = is_numeric($cantidadBanos) ? $cantidadBanos : '';
        $cantidadDormitorios = is_numeric($cantidadDormitorios) ? $cantidadDormitorios : '';
        $direccion = trim($direccion);  // Eliminar espacios al inicio y final

        // Si no se reciben parámetros de búsqueda, obtener todas las propiedades
        if (empty($tipoBusqueda) && empty($cantidadBanos) && empty($cantidadDormitorios) && empty($direccion)) {
            // Mostrar todas las propiedades
            $nuevasPropiedades = PropiedadModel::ObtenerPropiedadesActivas();
        } else {
            // Mostrar las propiedades filtradas según los parámetros recibidos
            $nuevasPropiedades = PropiedadModel::ObtenerPropiedadesPorBusqueda(
                $direccion,
                $tipoBusqueda,
                $cantidadBanos,
                $cantidadDormitorios
            );
        }

        // Contar el total de propiedades
        $contPropiedades = PropiedadModel::contarPropiedades();

        // Otros datos de la página
        $head = SiteController::head();
        $header = SiteController::header();
        $scripts = SiteController::scripts();
        $footer = SiteController::footer();

        // Renderizar la vista con las propiedades obtenidas
        Response::render($this->viewDir(__NAMESPACE__), "list", [
            "title" => 'Inicio MVC',
            "head" => $head,
            "header" => $header,
            "contadorPropiedades" => $contPropiedades,
            "propiedades" => $nuevasPropiedades, // Las propiedades a mostrar
            "scripts" => $scripts,
            "footer" => $footer,
        ]);
    }
    public function actionDetalles($id)
    {
        // Decodificar la ID de la ruta
        $idpropiedad = base64_decode($id, true);

        if ($idpropiedad === false) {
            echo "ID de propiedad inválida.";
            return;
        }

        // Obtener los detalles de la propiedad usando la ID decodificada
        $propiedad = PropiedadModel::ObtenerPropiedadPorId($idpropiedad);
        $inmobiliariaId = $propiedad->id_inm;
        $inmobiliariaNombre = UserModel::ObtenerInmobiliariaNombre($inmobiliariaId);


        if (!$propiedad) {
            echo "No se encontró la propiedad.";
            return;
        }

        // Otros datos de la página
        $head = SiteController::head();
        $header = SiteController::header();
        $scripts = SiteController::scripts();
        $footer = SiteController::footer();

        // Renderizar la vista con los detalles de la propiedad
        Response::render($this->viewDir(__NAMESPACE__), "detalles", [
            "title" => 'Detalles de la propiedad',
            "head" => $head,
            "header" => $header,
            "scripts" => $scripts,
            "footer" => $footer,
            "propiedad" => $propiedad,
            "inmobiliaria" => $inmobiliariaNombre
        ]);
    }




}