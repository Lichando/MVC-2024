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
        // Si el usuario ya está autenticado, redirigir al dashboard
        if (SessionController::isLoggedIn()) {
            Response::redirect('dashboard');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validación de entrada
            if (empty($email) || empty($password)) {
                $error = "Por favor completa todos los campos.";
                $this->renderLoginView($error);
                return;
            }

            // Autenticación del usuario
            $user = UserModel::authenticate($email, $password);

            if ($user) {
                // Almacenar en sesión
                SessionController::login($user->id, $user->rol);
                // Redireccionar al dashboard
                Response::redirect('dashboard');
            } else {
                $error = "Email o contraseña incorrectos.";
                $this->renderLoginView($error);
            }
        } else {
            $this->renderLoginView();
        }
    }

    // Método para renderizar la vista de login
    private function renderLoginView($error = null)
    {
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

    // Método para mostrar el dashboard
    public function actionDashboard()
    {
        // Comprobar si el usuario está autenticado
        if (!SessionController::isLoggedIn()) {
            Response::redirect('login'); // Redirigir al login si no está autenticado
        }

        // Renderizar la vista del Dashboard
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "dashboard", [
            "title" => 'Dashboard',
            "head" => $head,
            "header" => $header,
        ]);
    }




    public function actionRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = 4; // Rol predeterminado (Visitante)

            // Verifica que los campos no estén vacíos
            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
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

            // Verifica que el email tenga un formato válido
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "El email proporcionado no es válido.";
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

            // Verifica si el usuario ya existe
            $existingUser = UserModel::findEmail($email);
            if ($existingUser) {
                $error = "El email ya está registrado.";
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

            // Hashear la contraseña
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Guarda el nuevo usuario en la base de datos
            $result = UserModel::createUser($nombre, $email, $hashedPassword, $rol);

            if ($result) {
                Response::redirect('login');
            } else {
                $error = "Error al registrar el usuario. Inténtalo de nuevo.";
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
            }
        } else {
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