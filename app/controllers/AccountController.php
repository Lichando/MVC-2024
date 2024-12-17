<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;

class AccountController extends Controller
{
    // Función para redirigir según el rol del usuario
    private function redirectByRole($rol)
    {
        if ($rol == 1 || $rol == 2) {
            Response::redirect('admindashboard'); // Redirigir al dashboard administrativo
        } else {
            Response::redirect('dashboard'); // Redirigir al dashboard de usuarios inmobiliarios
        }
    }

    // Función para verificar si el usuario está logueado y si tiene un rol válido
    private function verificarAutenticacionYRol($rolesPermitidos)
    {
        if (!SessionController::EstaLogeado()) {
            Response::redirect('login');
            return false;
        }

        $rol = SessionController::getRol();
        if (!in_array($rol, $rolesPermitidos)) {
            Response::redirect('login');
            return false;
        }

        return $rol; // Si la autenticación es correcta, retorna el rol
    }

    // Acción para el login
    public function actionLogin()
    {
        // Si el usuario ya está autenticado, redirigir según el rol
        if (SessionController::EstaLogeado()) {
            $rol = SessionController::getRol();
            $this->redirectByRole($rol); // Redirige según el rol
            return;
        }

        // Procesar el formulario solo si es una solicitud POST
        $error = null; // Inicializamos la variable de error

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validación de entrada
            if (empty($email) || empty($password)) {
                $error = "Por favor completa todos los campos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El email proporcionado no es válido.";
            } else {
                // Autenticación del usuario
                $user = UserModel::authenticate($email, $password);

                if ($user) {
                    // Almacenar en sesión
                    SessionController::login($user->id, $user->rol, $user->nombre,$user->inmobiliaria_id);

                    // Redirigir según el rol
                    $this->redirectByRole($user->rol);
                    return;
                } else {
                    $error = "Email o contraseña incorrectos.";
                }
            }
        }

        // Renderizar la vista de login (con o sin error)
        $head = SiteController::head();
        $header = SiteController::header();
        $footer = SiteController::footer();
        $scripts = SiteController::scripts();
        Response::render($this->viewDir(__NAMESPACE__), "login", [
            "title" => 'Iniciar Sesión',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "error" => $error,
            "scripts"=>$scripts
        ]);
    }

    // Acción para el dashboard del usuario
    public function actionDashboard()
    {
        // Verificar la autenticación y rol del usuario
        $rolUsuario = $this->verificarAutenticacionYRol([1, 2, 3, 4, 5, 6]);
        if (!$rolUsuario)
            return; // Si no está autenticado o tiene un rol inválido, se redirige
    
        // get el nombre de usuario desde la sesión
        $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Invitado';
        $inmobiliariaNombre = isset($_SESSION['inmobiliaria_nombre']) ? $_SESSION['inmobiliaria_nombre']: 'No tiene';
        $inmobiliariaId = isset($_SESSION['inmobiliaria_id']) ? $_SESSION['inmobiliaria_id']: 'No tiene';

        $propiedadesCountAc = InmobiliariaController::ContadorPropiedadesActivasInmo($inmobiliariaId);
        $propiedadesCountInac = InmobiliariaController::ContadorPropiedadesInactivasInmo($inmobiliariaId);
        $propiedadesCountAll = InmobiliariaController::ContadorPropiedadesTotalesInmo($inmobiliariaId);
            
        // Redirigir a diferentes dashboards según el rol
        switch ($rolUsuario) {
            case 6: // Cliente
                // Redirigir al dashboard específico para clientes
                Response::render($this->viewDir(__NAMESPACE__), "clientedashboard", [
                    "title" => 'Dashboard Cliente',
                    "head" => SiteController::head(),
                    "header" => SiteController::header(),
                    "footer" => SiteController::footer(),
                    "userName" => $userName,
                    "scripts"=> SiteController::scripts(),
                ]);
                return;
    
            case 3: // Administrador Inmobiliaria
            case 4: // Corredor Inmobiliario
            case 5: // Agente Inmobiliario
                // Redirigir al dashboard inmobiliario
                Response::render($this->viewDir(__NAMESPACE__), "inmobiliariodashboard", [
                    "title" => 'Dashboard Inmobiliario',
                    "head" => SiteController::head(),
                    "header" => SiteController::header(),
                    "footer" => SiteController::footer(),
                    "scripts"=> SiteController::scripts(),
                    "userName" => $userName,
                    "inmobiliariaNombre" => $inmobiliariaNombre,
                    "propiedadesActivasCount"=>$propiedadesCountAc,
                    "propiedadesInactivasCount"=>$propiedadesCountInac,
                    "totalPropiedadesCount"=>$propiedadesCountAll,
                ]);
                return;
    
            case 1: // Administrador
            case 2: // Empleado
                // Redirigir al dashboard administrativo
                Response::render($this->viewDir(__NAMESPACE__), "admindashboard", [
                    "title" => 'Admin Dashboard',
                    "head" => SiteController::head(),
                    "header" => SiteController::header(),
                    "footer" => SiteController::footer(),
                    "scripts"=> SiteController::scripts(),
                ]);
                return;
    
            default:
                // Si el rol no es válido, redirigir a login
                Response::redirect('login');
                return;
        }
    }
    

    public function actionRegister()
    {
        // Procesar el formulario solo si es una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = 6;

            // Validación de entrada
            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El email proporcionado no es válido.";
            } else {
                // Verifica si el usuario ya existe
                $existeUser = UserModel::BuscarEmail($email);
                if ($existeUser) {
                    $error = "El email ya está registrado.";
                } else {
                    // Hashear la contraseña
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    // Guarda el nuevo usuario en la base de datos
                    $resultado = UserModel::CrearUsuario($nombre, $email, $hashedPassword, $rol);

                    if ($resultado) {
                        Response::redirect('login');
                        return;
                    } else {
                        $error = "Error al registrar el usuario. Inténtalo de nuevo.";
                    }
                }
            }

            // Renderizar la vista de registro con el error
            $head = SiteController::head();
            $header = SiteController::header();
            $footer = SiteController::footer();
            $scripts= SiteController::scripts();
            
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "footer" => $footer,
                "error" => $error,
                "scripts"=>$scripts ,
            ]);
            return;
        }

        // Si no es un POST, solo renderizar la vista de registro sin error
        $head = SiteController::head();
        $header = SiteController::header();
        $footer = SiteController::footer();
        $scripts= SiteController::scripts();
        Response::render($this->viewDir(__NAMESPACE__), "register", [
            "title" => 'Registro',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "scripts"=>$scripts ,
        ]);
    }



    // Método para manejar el cierre de sesión
    public function actionLogout()
    {
        // Lógica para cerrar sesión
        SessionController::logout(); // Llama a un método que maneje el cierre de sesión

        // Redirigir a la página de inicio
        Response::redirect('login'); // Redirige a la página de inicio
    }
}
