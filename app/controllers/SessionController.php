<?php

namespace app\controllers;

use \Controller;

class SessionController extends Controller
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia la sesión si no está iniciada
        }
    }

    public static function logout()
    {
        self::start(); // Asegúrate de que la sesión esté iniciada
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy(); // Destruir la sesión
    }

    public static function EstaLogeado()
    {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    public static function hasAnyRole(array $roles)
    {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $roles);
    }
    public static function login($userId, $rol, $userName)
    {
        self::start(); // Asegúrate de que la sesión esté iniciada
    
        // Validar los parámetros
        if (empty($userId) || !is_numeric($userId) || !in_array($rol, [1, 2, 3, 4, 5, 6]) || empty($userName)) {
            throw new \Exception('Datos de usuario, rol o nombre inválidos');
        }
    
        // Guardar la información en la sesión
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $rol;
        $_SESSION['user_name'] = $userName;  // Aquí guardamos el nombre del usuario
    
        session_regenerate_id(true);
    
        // Establecer cookie con duración más corta (por ejemplo, 1 mes)
        setcookie('user_id', $userId, time() + (86400 * 30), "/", "", isset($_SERVER['HTTPS']), true);
    }
    

    public static function getSessionValue($key)
    {
        self::start(); // Asegúrate de que la sesión esté iniciada
        return $_SESSION[$key] ?? null;
    }

    public static function getUserId()
    {
        return self::getSessionValue('user_id');
    }

    public static function ObtenerRol()
    {
        return self::getSessionValue('user_role');
    }

    public static function getRoleName($rolId)
    {
        $roles = [
            1 => "Administrador",
            2 => "Empleado",
            3 => "Administrador Inmobiliaria",
            4 => "Corredor Inmobiliario",
            5 => "Agente Inmobiliario",
            6 => "Cliente"
        ];

        return $roles[$rolId] ?? "Desconocido";
    }
}
