<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\InmobiliariaModel;
use app\controllers\SessionController; // Asegúrate de incluir el controlador de sesiones

class AdminController extends Controller
{
    // Mostrar listado de todas las inmobiliarias
    public function actionInmobiliarias()
    {
        // Verificar que el usuario tiene permiso
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [1, 2])) {
            Response::redirect('login'); // Redirigir si no tiene permisos
            return;
        }

        // Obtener el término de búsqueda y la página actual
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : ''; // Usar trim para evitar espacios en blanco
        $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

        // Definir el límite de registros por página
        $limite = 10;

        // Si no hay término de búsqueda, obtener todas las inmobiliarias
        if ($buscar === '') {
            $inmobiliarias = InmobiliariaModel::getInmobiliariasConPaginacion($pagina, $limite);
            $totalInmobiliarias = InmobiliariaModel::contarInmobiliarias();
        } else {
            // Ejecutar búsqueda con el término proporcionado
            $inmobiliarias = InmobiliariaModel::getInmobiliariasConPaginacion($pagina, $limite, $buscar);
            $totalInmobiliarias = InmobiliariaModel::contarInmobiliarias($buscar);
        }

        // Calcular el total de páginas
        $totalPaginas = ceil($totalInmobiliarias / $limite);

        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias", [
            "title" => 'Inmobiliarias',
            "head" => $head,
            "header" => $header,
            "inmobiliarias" => $inmobiliarias,
            "buscar" => $buscar,
            "pagina" => $pagina,
            "totalPaginas" => $totalPaginas
        ]);
    }
   
    public function crearInmobiliaria()
    {
        // Verificar si se ha enviado el formulario (verifica si los campos POST existen)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recoge los datos del formulario
            $nombre = $_POST['nombre'];
            $duenioInmobiliaria = $_POST['duenioInmobiliaria'];
            $matricula = $_POST['matricula'];
            $direccion = $_POST['direccion'];
            $telefono = $_POST['telefono'];
            $email = $_POST['email'];
    
            // Aquí va la lógica para obtener la fecha actual
            $fechaCreacion = date('Y-m-d'); // Obtén la fecha actual en formato 'YYYY-MM-DD'
            
            // Por defecto, vamos a asumir que la inmobiliaria está activa
            $activo = 1; // 1 es para activo, puedes cambiarlo según la lógica que necesites
    
            // Aquí puedes hacer validaciones, como verificar si los campos están vacíos, etc.
            if (empty($nombre) || empty($duenioInmobiliaria) || empty($matricula) || empty($direccion)) {
                // Si hay algún error, puedes mostrar un mensaje en la misma vista
                $error = "Por favor, complete todos los campos obligatorios.";
                Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias", [
                    'error' => $error,
                    'nombre' => $nombre,
                    'duenioInmobiliaria' => $duenioInmobiliaria,
                    'matricula' => $matricula,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'email' => $email
                ]);
                return;
            }
    
            // Aquí va la lógica para registrar la inmobiliaria en la base de datos
            $inmobiliariaId = InmobiliariaModel::crearInmobiliaria(
                $nombre, 
                $duenioInmobiliaria, 
                $matricula, 
                $direccion, 
                $telefono, 
                $email, 
                $fechaCreacion, 
                $activo
            );
            
            if ($inmobiliariaId) {
                // Si el registro es exitoso, puedes mostrar un mensaje de éxito
                $successMessage = "Inmobiliaria registrada con éxito!";
                Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias", [
                    'successMessage' => $successMessage
                ]);
                return;
            } else {
                // En caso de algún error al guardar, mostrar un mensaje de error
                $error = "Hubo un problema al registrar la inmobiliaria. Intenta de nuevo.";
                Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias", [
                    'error' => $error
                ]);
                return;
            }
        }
    
        // Si no se ha enviado el formulario, solo renderizas la vista normalmente
        Response::render($this->viewDir(__NAMESPACE__), "inmobiliarias");
    }
    

    // Eliminar una inmobiliaria
    public function actionEliminarInmobiliaria($id)
    {
        // Verificar que el usuario tiene permiso
        if (!SessionController::EstaLogeado() || !in_array(SessionController::getRol(), [1, 2])) {
            Response::redirect('login'); // Redirigir si no tiene permisos
            return;
        }

        // Verificar si la inmobiliaria existe
        $inmobiliaria = InmobiliariaModel::getInmobiliariaPorId($id);
        if (!$inmobiliaria) {
            Response::redirect('admin/inmobiliarias'); // Redirigir si no se encuentra la inmobiliaria
            return;
        }

        // Eliminar la inmobiliaria
        InmobiliariaModel::bajaInmobiliaria($id);

        // Redirigir de vuelta a la lista de inmobiliarias
        Response::redirect('admin/inmobiliarias');
    }
}
