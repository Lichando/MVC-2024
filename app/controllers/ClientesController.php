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
        if (!SessionController::EstaLogeado() | !in_array(SessionController::getRol(), [6])) {
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
        $scripts = SiteController::scripts();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $matricula = $_POST['matricula'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $imagen = $_FILES['imagen'] ?? null; // Suponiendo que la imagen se envía a través de un campo de formulario 'imagen'

            $duenioInmobiliaria = SessionController::getUserId(); // ID del usuario logueado

            if (empty($nombre) || empty($matricula) || empty($direccion)) {
                $error = "Por favor completa todos los campos obligatorios.";
            } else {
                try {
                    // Verificar si la carpeta de imágenes existe, si no, crearla
                    $carpetaImagenes = 'public/logosinmb/';
                    if (!is_dir($carpetaImagenes)) {
                        mkdir($carpetaImagenes, 0777, true); // Crear la carpeta si no existe
                    }

                    $nombreImagen = ''; // Variable para almacenar el nombre de la imagen

                    // Verificar si se ha subido una imagen y validarla
                    if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
                        $tipoImagen = mime_content_type($imagen['tmp_name']);
                        $extensionesPermitidas = ['image/jpeg', 'image/png'];

                        if (in_array($tipoImagen, $extensionesPermitidas)) {
                            // Generar el nombre de la imagen
                            $nombreImagen = $nombre . '.' . pathinfo($imagen['name'], PATHINFO_EXTENSION);
                            $rutaDestino = $carpetaImagenes . $nombreImagen;

                            // Mover la imagen a la carpeta correspondiente
                            move_uploaded_file($imagen['tmp_name'], $rutaDestino);
                        } else {
                            $error = "Solo se permiten imágenes en formato JPG, JPEG o PNG.";
                        }
                    } else {
                        $error = "Debe subir una imagen válida.";
                    }

                    // Crear la inmobiliaria, ahora con la ruta completa de la imagen
                    $inmobiliariaId = InmobiliariaModel::crearInmobiliaria(
                        $duenioInmobiliaria,
                        $nombre,
                        $nombreImagen, // Asignar la imagen (nombre de archivo) para guardar en la base de datos
                        $matricula,
                        $direccion,
                        $telefono,
                        $email
                    );

                    // Asignar el rol de 'Administrador Inmobiliaria' (rol 3) al usuario
                    InmobiliariaModel::asignarRolInmobiliaria($duenioInmobiliaria, $inmobiliariaId);
                    // Actualizar la sesión del usuario con el nuevo rol y la nueva inmobiliaria
                    $_SESSION['user_role'] = 3;  // 'Administrador Inmobiliaria'
                    $_SESSION['inmobiliaria_id'] = $inmobiliariaId;  // ID de la inmobiliaria creada
                    $_SESSION['inmobiliaria_nombre'] = $nombre;  // Nombre de la inmobiliaria creada

                    // Mostrar mensaje de éxito
                    $_SESSION['successMessage'] = "Inmobiliaria registrada correctamente.";

                    Response::redirect('../account/dashboard');
                    return;

                } catch (\Exception $e) {
                    $error = $e->getMessage(); // Captura el mensaje de error
                }
            }
        }

        // Renderizar el formulario de inscripción
        Response::render($this->viewDir(__NAMESPACE__), "inscripcion", [
            "title" => 'Formulario de Inscripción',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "scripts" => $scripts,
            "error" => $error ?? ''
        ]);
    }




    public static function ContadorClientes()
    {
        return UserModel::contarClientes();
    }

}
