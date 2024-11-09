<?php
namespace app\controllers;

use \Controller;

class SessionController extends Controller
{
    // Iniciar la sesión
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia la sesión si no está iniciada
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

        // Destruir la sesión
        session_destroy();
    }

    // Verificar si el usuario está autenticado
    public static function isLoggedIn() {
        self::start(); // Asegúrate de que la sesión esté iniciada
        // Verifica que tanto el user_id como el user_role existan
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    // Verificar si el usuario está autenticado (alias de isLoggedIn)
    public static function isAuthenticated() {
        return self::isLoggedIn(); // Reutiliza el método isLoggedIn
    }

    // Verificar el rol del usuario
    public static function hasRole($rol) {
        self::start(); // Asegúrate de que la sesión esté iniciada
        // Verifica si el rol del usuario coincide con el rol requerido
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $rol;
    }

    // Verificar si el usuario tiene uno de los múltiples roles
    public static function hasAnyRole(array $roles) {
        self::start(); // Asegúrate de que la sesión esté iniciada
        // Verifica si el rol del usuario está dentro de los roles permitidos
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
    }

    // Guardar el ID del usuario en la sesión al iniciar sesión
    public static function login($userId, $rol) {
        self::start(); // Asegúrate de que la sesión esté iniciada
        $_SESSION['user_id'] = $userId; // Guarda el ID del usuario
        $_SESSION['user_role'] = $rol; // Guarda el rol del usuario
    
        // Agregar un log para verificar que el rol se guarda correctamente
        error_log("User ID: $userId, Role: $rol");  // Verificar que los datos estén correctos
    
        // Regenerar el ID de sesión para prevenir ataques de fijación de sesión
        session_regenerate_id(true);
    
        // Establecer una cookie segura para el ID del usuario (si es necesario)
        setcookie('user_id', $userId, time() + (86400 * 30), "/", "", isset($_SERVER['HTTPS']), true); // Expira en 30 días
    }
    

    // Obtener el ID del usuario autenticado
    public static function getUserId() {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return $_SESSION['user_id'] ?? null; // Devuelve el user_id o null si no existe
    }

    // Obtener el rol del usuario autenticado
    public static function getUserRole() {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return $_SESSION['user_role'] ?? null; // Devuelve el user_role o null si no existe
    }

    // Verificar si el usuario tiene acceso a alguna inmobiliaria (esto es un ejemplo)
    public static function hasInmobiliariaAccess($inmobiliariaId) {
        self::start();
        return isset($_SESSION['inmobiliaria_id']) && $_SESSION['inmobiliaria_id'] == $inmobiliariaId;
    }

    // Obtener el nombre del rol para mostrarlo de manera legible
    public static function getRoleName($rolId) {
        $roles = [
            1 => "Administrador",
            2 => "Empleado",
            3 => "Administrador Inmobiliaria",
            4 => "Corredor Inmobiliario",
            5 => "Agente Inmobiliario",
            6 => "Cliente"
        ];

        return $roles[$rolId] ?? "Desconocido"; // Retorna el nombre del rol o "Desconocido" si no se encuentra
    }
}
