<?php 
namespace app\controllers;

use \Controller;

class SessionController extends Controller

{
    // Iniciar la sesión
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Cerrar la sesión
    public static function logout() {
        self::start(); // Asegúrate de que la sesión esté iniciada

        // Eliminar todas las variables de sesión
        $_SESSION = array();

        // Si se desea destruir la sesión completamente
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"], 
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente, destruir la sesión
        session_destroy();
    }

    // Verificar si el usuario está autenticado
    public static function isLoggedIn() {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return isset($_SESSION['user_id']); // Cambia 'user_id' al nombre de tu variable de sesión que guarda el ID del usuario
    }

    // Guardar el ID del usuario en la sesión al iniciar sesión
    public static function login($userId) {
        self::start(); // Asegúrate de que la sesión esté iniciada
        $_SESSION['user_id'] = $userId; // Cambia 'user_id' por la variable que prefieras

        // Establecer una cookie para el ID del usuario (si es necesario)
        setcookie('user_id', $userId, time() + (86400 * 30), "/", "", true, true); // 30 días de duración
    }
}
