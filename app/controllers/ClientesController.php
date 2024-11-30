<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\InmobiliariaModel;
use app\controllers\SessionController;

class ClientesController extends Controller
{
    // Verificar si el usuario está logueado
    private function verificarAccesoFormulario()
    {
        // Verificar que el usuario esté logueado usando SessionController
        if (!SessionController::EstaLogeado()) {
            // Si no está logueado, redirige al login
            Response::redirect('login');
            return false;
        }

        return true;
    }

    // Mostrar el formulario de inscripción
    public function actionInscripcion()
    {
        if (!SessionController::EstaLogeado()) {
            Response::redirect('login');
            return;
        }

        $head = SiteController::head();
        $header = SiteController::header();
        $footer = SiteController::footer();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $matricula = $_POST['matricula'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';

            $duenioInmobiliaria = SessionController::getUserId(); // ID del usuario logueado

            if (empty($nombre) || empty($matricula) || empty($direccion)) {
                $error = "Por favor completa todos los campos obligatorios.";
            } else {
                try {
                    // Crear la inmobiliaria
                    $resultado = InmobiliariaModel::crearInmobiliaria(
                        $duenioInmobiliaria, $nombre, $matricula, $direccion, $telefono, $email
                    );

                    // Actualizar el rol del usuario a 'Administrador Inmobiliaria' (rol 3)
                    InmobiliariaModel::asignarRolInmobiliaria($duenioInmobiliaria);

                    $_SESSION['successMessage'] = "Inmobiliaria registrada correctamente.";
                    Response::redirect('../account/dashboard');
                    return;

                } catch (\Exception $e) {
                    $error = $e->getMessage(); // Captura el mensaje de error
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "inscripcion", [
            "title" => 'Formulario de Inscripción',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "error" => $error ?? ''
        ]);
    }
}

