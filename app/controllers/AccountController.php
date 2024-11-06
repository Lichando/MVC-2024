<?php

namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;

class AccountController extends Controller
{
    // Constructor
    public function __construct()
    {
        // Aquí puedes inicializar variables o realizar acciones necesarias
    }

    public function actionLogin()
    {
        // Si el usuario ya está autenticado, redirigir según el rol
        if (SessionController::isLoggedIn()) {
            // Verificamos el rol para redirigir al dashboard correspondiente
            $role = SessionController::getUserRole();
            if ($role == 1 || $role == 2) { // Administrador o Empleado
                Response::redirect('admin-dashboard'); // Redirigir al dashboard administrativo
            } else {
                Response::redirect('dashboard'); // Redirigir al dashboard de usuarios inmobiliarios
            }
            return;
        }

        // Procesar el formulario solo si es una solicitud POST
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
                    SessionController::login($user->id, $user->rol);

                    // Redirigir según el rol
                    if ($user->rol == 1 || $user->rol == 2) {
                        Response::redirect('admin-dashboard'); // Redirigir al dashboard de administración
                    } else {
                        Response::redirect('dashboard'); // Redirigir al dashboard de inmobiliarios
                    }
                    return;
                } else {
                    $error = "Email o contraseña incorrectos.";
                }
            }

            // Renderizar la vista de login con el error
            $head = SiteController::head();
            $header = SiteController::header();
            $footer = SiteController::footer();
            Response::render($this->viewDir(__NAMESPACE__), "login", [
                "title" => 'Iniciar Sesión',
                "head" => $head,
                "header" => $header,
                "footer" => $footer,
                "error" => $error,
            ]);
            return;
        }

        // Si no es un POST, solo renderizar la vista de login sin error
        $head = SiteController::head();
        $header = SiteController::header();
        $footer = SiteController::footer();
        Response::render($this->viewDir(__NAMESPACE__), "login", [
            "title" => 'Iniciar Sesión',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
        ]);
    }
    public function actionDashboard()
    {
        // Comprobar si el usuario está autenticado
        if (!SessionController::isLoggedIn()) {
            Response::redirect('login'); // Redirigir al login si no está autenticado
            return;
        }
    
        // Obtener el rol del usuario
        $userRole = SessionController::getUserRole();
    
        // Variables que controlan las secciones del dashboard
        $showPropertyManagement = false;
        $showCommercialOptions = false;
        $showAdminOptions = false;
    
        // Definir permisos según el rol del usuario
        switch ($userRole) {
            case 1: // Administrador
            case 2: // Empleado
                $showAdminOptions = true; // El administrador/empleado verá opciones administrativas
                break;
            case 3: // Administrador Inmobiliaria
                $showPropertyManagement = true; // El administrador inmobiliario gestiona propiedades
                break;
            case 4: // Corredor Inmobiliario
                $showPropertyManagement = true; // El corredor inmobiliario gestiona propiedades
                break;
            case 5: // Agente Inmobiliario
                $showCommercialOptions = true; // El agente tiene acceso comercial
                break;
            case 6: // Cliente
                // El cliente solo verá opciones de búsqueda de propiedades
                break;
            default:
                Response::redirect('login'); // En caso de un rol no reconocido, redirigir a login
                return;
        }
    
        // Renderizar la vista del Dashboard
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "dashboard", [
            "title" => 'Dashboard',
            "head" => $head,
            "header" => $header,
            "showPropertyManagement" => $showPropertyManagement,
            "showCommercialOptions" => $showCommercialOptions,
            "showAdminOptions" => $showAdminOptions,
        ]);
    }
    public function actionAdminDashboard()
    {
        // Comprobar si el usuario es administrador o empleado
        if (!SessionController::isLoggedIn() || !in_array(SessionController::getUserRole(), [1, 2])) {
            Response::redirect('login'); // Redirigir al login si no está autorizado
            return;
        }
    
        // Obtener el rol del usuario
        $userRole = SessionController::getUserRole();
    
        // Renderizar la vista del Admin Dashboard
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "admin-dashboard", [
            "title" => 'Admin Dashboard',
            "head" => $head,
            "header" => $header,
            // Aquí puedes agregar más variables de contenido específicas para la administración
        ]);
    }
        

    public function actionRegister()
    {
        // Procesar el formulario solo si es una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = 4; // Rol predeterminado (Visitante)

            // Validación de entrada
            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El email proporcionado no es válido.";
            } else {
                // Verifica si el usuario ya existe
                $existingUser = UserModel::findEmail($email);
                if ($existingUser) {
                    $error = "El email ya está registrado.";
                } else {
                    // Hashear la contraseña
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    // Guarda el nuevo usuario en la base de datos
                    $result = UserModel::createUser($nombre, $email, $hashedPassword, $rol);

                    if ($result) {
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
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "footer" => $footer,
                "error" => $error,
            ]);
            return;
        }

        // Si no es un POST, solo renderizar la vista de registro sin error
        $head = SiteController::head();
        $header = SiteController::header();
        $footer = SiteController::footer();
        Response::render($this->viewDir(__NAMESPACE__), "register", [
            "title" => 'Registro',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
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
