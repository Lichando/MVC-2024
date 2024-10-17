<?php 
namespace app\controllers;

use \Controller;
use \Response;
use \SessionController; // Asegúrate de incluir tu controlador de sesiones

class AccountController extends Controller
{
    // Constructor
    public function __construct() {
        // Aquí puedes inicializar variables o realizar acciones necesarias
    }

    // Método para mostrar la página de inicio de sesión
    public function actionLogin() {
        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();
        Response::render($this->viewDir(__NAMESPACE__), "login", [
                                                                    "title" => 'Iniciar Sesión',
                                                                    "head" => $head,
                                                                    "nav" => $nav,
                                                                    "footer" => $footer,	
                                                                    ]);
    }

    // Método para manejar el registro
    public function actionRegister() {
        $head = SiteController::head();
        $nav = SiteController::nav();
        Response::render($this->viewDir(__NAMESPACE__), "register", [
            "title" => 'Registrar Cuenta',
            "head" => $head,
            "nav" => $nav,
        ]);
    }

    // Método para mostrar el perfil de usuario
    public function actionProfile() {
        $head = SiteController::head();
        $nav = SiteController::nav();
        Response::render($this->viewDir(__NAMESPACE__), "profile", [
            "title" => 'Perfil de Usuario',
            "head" => $head,
            "nav" => $nav,
        ]);
    }

    // Método para manejar el cierre de sesión
    public function actionLogout() {
    // Lógica para cerrar sesión
    SessionController::logout(); // Llama a un método que maneje el cierre de sesión

    // Redirigir a la página de inicio
    Response::redirect('/home/index'); // Redirige a la página de inicio
    }

}