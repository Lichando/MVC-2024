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
            Response::redirect('admin-dashboard'); // Redirigir al dashboard administrativo
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

        $rol = SessionController::ObtenerRol();
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
            $rol = SessionController::ObtenerRol();
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
                    SessionController::login($user->id,$user->rol, $user->nombre);

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
        Response::render($this->viewDir(__NAMESPACE__), "login", [
            "title" => 'Iniciar Sesión',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "error" => $error,
        ]);
    }

    // Acción para el dashboard del usuario
    public function actionDashboard()
    {
        // Verificar la autenticación y rol del usuario
        $rolUsuario = $this->verificarAutenticacionYRol([1, 2, 3, 4, 5, 6]);
        if (!$rolUsuario) return; // Si no está autenticado o tiene un rol inválido, se redirige
        // Obtener el nombre de usuario desde la sesión
         // Obtener el nombre de usuario desde la sesión
        $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Invitado';


        // Variables que controlan las secciones del dashboard
        $MostrarGestorPropiedades = false;
        $MostrarOpcionesComerciales = false;
        $MostrarOpcionesAdmin = false;

        // Definir permisos según el rol del usuario
        switch ($rolUsuario) {
            case 1: // Administrador
            case 2: // Empleado
                $MostrarOpcionesAdmin = true; // El administrador/empleado verá opciones administrativas
                break;
            case 3: // Administrador Inmobiliaria
                $MostrarGestorPropiedades = true; // El administrador inmobiliario gestiona propiedades
                break;
            case 4: // Corredor Inmobiliario
                $MostrarGestorPropiedades = true; // El corredor inmobiliario gestiona propiedades
                break;
            case 5: // Agente Inmobiliario
                $MostrarOpcionesComerciales = true; // El agente tiene acceso comercial
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
        $footer = SiteController::footer();
        Response::render($this->viewDir(__NAMESPACE__), "dashboard", [
            "title" => 'Dashboard',
            "head" => $head,
            "header" => $header,
            "footer" => $footer,
            "userName" => $userName,
            "MostrarGestorPropiedades" => $MostrarGestorPropiedades,
            "MostrarOpcionesComerciales" => $MostrarOpcionesComerciales,
            "MostrarOpcionesAdmin" => $MostrarOpcionesAdmin,
        ]);
    }

    // Acción para el dashboard de administración
    public function actionAdminDashboard()
    {
        // Verificar si el usuario está autenticado y tiene un rol válido (1 = Admin, 2 = Empleado)
        $rolUsuario = $this->verificarAutenticacionYRol([1, 2]);
        if (!$rolUsuario) return; // Si no está autenticado o tiene un rol inválido, se redirige

        // Renderizar la vista del Admin Dashboard
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "admindashboard", [
            "title" => 'Admin Dashboard',
            "head" => $head,
            "header" => $header,
        ]);
    }

    public function actionRegister()
    {
        // Procesar el formulario solo si es una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = 4;

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
