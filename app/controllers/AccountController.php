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
        // Verificar si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Verifica si los campos están vacíos
            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = "Por favor, completa todos los campos.";
                Response::redirect('#PATH#/account/login'); // Redirige a la página de inicio de sesión
                return; // Asegúrate de salir después de redirigir
            }

            // Verifica que el email tenga un formato válido
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['login_error'] = "El email proporcionado no es válido.";
                Response::redirect('#PATH#/account/login'); // Redirige a la página de inicio de sesión
                return;
            }

            // Busca al usuario por email
            $user = UserModel::findEmail($email);

            // Verifica que el usuario exista y la contraseña sea correcta
            if ($user && password_verify($password, $user->contrasena)) {
                // Si todo es correcto, guarda la sesión
                SessionController::login($user->id, $user->rol);
                Response::redirect('#PATH#/home'); // Redirige a la página principal o cualquier otra
            } else {
                $_SESSION['login_error'] = "Email o contraseña incorrectos.";
                Response::redirect('#PATH#/account/login'); // Redirige a la página de inicio de sesión
            }
        } else {
            // Si la solicitud no es POST, simplemente muestra la vista de inicio de sesión
            $head = SiteController::head();
            $header = SiteController::header();
            Response::render($this->viewDir(__NAMESPACE__), "login", [
                "title" => 'Iniciar Sesión',
                "head" => $head,
                "header" => $header,
                "error" => $_SESSION['login_error'] ?? null, // Muestra el mensaje de error si existe
            ]);
            unset($_SESSION['login_error']); // Limpia el mensaje de error después de mostrarlo
        }
    }

    public function actionRegister()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rol = 'usuario'; // Puedes establecer un rol predeterminado o recibirlo desde el formulario.

        // Verifica que los campos no estén vacíos
        if (empty($email) || empty($password)) {
            $head = SiteController::head();
            $header = SiteController::header();
            $error = "Por favor, completa todos los campos.";
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "error" => $error,
            ]);
            return;
        }

        // Verifica que el email tenga un formato válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $head = SiteController::head();
            $header = SiteController::header();
            $error = "El email proporcionado no es válido.";
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "error" => $error,
            ]);
            return;
        }

        // Verifica si el usuario ya existe
        $existingUser = UserModel::findEmail($email);
        $head = SiteController::head();
        $header = SiteController::header();
        if ($existingUser) {
            $error = "El email ya está registrado.";
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "error" => $error,
            ]);
            return;
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Guarda el nuevo usuario en la base de datos
        $result = UserModel::createUser($email, $hashedPassword, $rol);

        if ($result) {
            // Redirigir o mostrar mensaje de éxito
            Response::redirect('/account/login');
        } else {
            $error = "Error al registrar el usuario. Inténtalo de nuevo.";
            Response::render($this->viewDir(__NAMESPACE__), "register", [
                "title" => 'Registro',
                "head" => $head,
                "header" => $header,
                "error" => $error,
            ]);
        }
    } else {
        // Mostrar la vista de registro si la solicitud no es POST
        $head = SiteController::head();
        $header = SiteController::header();
        Response::render($this->viewDir(__NAMESPACE__), "register", [
            "title" => 'Registro',
            "head" => $head,
            "header" => $header,
        ]);
    }
}


    // Método para manejar el cierre de sesión
    public function actionLogout()
    {
        // Lógica para cerrar sesión
        SessionController::logout(); // Llama a un método que maneje el cierre de sesión

        // Redirigir a la página de inicio
        Response::redirect('/home/index'); // Redirige a la página de inicio
    }

}