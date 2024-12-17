<?php

namespace app\models;

use \DataBase;
use \Model;
use PDOException;

class UserModel extends Model
{
    protected $table = "usuarios";
    protected $primaryKey = "id";
    protected $secundaryKey = "email";  
    public $id;
    public $nombre;
    public $email;
    public $contrasena;
    public $fecha_registro;
    public $activo;
    public $rol;

    // Método para autenticar al usuario por email y contraseña
    public static function authenticate($email, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $users = DataBase::getRecords($sql, ['email' => $email]);

        if (!empty($users)) {
            $user = $users[0];
            if (isset($user->contrasena)) {
                if (password_verify($password, $user->contrasena)) {
                    return $user;
                }
            }
        }
        return null;
    }

    public static function ObtenerInmobiliariaNombre($inmobiliariaId) {
        $sql = "SELECT nombre FROM inmobiliarias WHERE id = :inmobiliariaId";
        $params = ['inmobiliariaId' => $inmobiliariaId];
        
        // Obtener los registros
        $result = DataBase::getRecords($sql, $params);
        
        // Si la consulta devuelve algún registro, retornamos solo el nombre
        if (count($result) > 0) {
            return $result[0]->nombre;  // Devolver solo el nombre
        }
        
        // Si no se encuentra ninguna inmobiliaria, puedes retornar un valor predeterminado o null
        return null;
    }

    public static function ObtenerInmobiliariaIdPorUsuario($userId) {
        $sql = "SELECT inmobiliaria_id FROM usuarios WHERE id = :usuarioId";
        $params = [':usuarioId' => $userId];
        
        // Obtener los registros
        $resultado = DataBase::getRecords($sql, $params);
        
        // Verificar si el resultado contiene registros
        if (count($resultado) > 0) {
            return $resultado[0]->inmobiliaria_id;  // Acceder al 'inmobiliaria_id' del primer registro
        } else {
            return null;  // Si no se encuentra ningún registro, retorna null
        }
    }
    


    // Método para buscar un usuario por su email
    public static function BuscarEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $params = ['email' => $email];
        return DataBase::getRecords($sql, $params);
    }

    // Método para crear un nuevo usuario
    public static function CrearUsuario($nombre, $email, $hashedPassword, $rol = 6)
    {
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, rol, fecha_registro, activo,inmobiliaria_id) 
                VALUES (:nombre, :email, :contrasena, :rol, CURRENT_TIMESTAMP, 1,0)";
        
        $params = [
            'nombre' => $nombre,
            'email' => $email,
            'contrasena' => $hashedPassword,
            'rol' => $rol
        ];

        return DataBase::execute($sql, $params);
    }

    // Método para cambiar la contraseña
    public static function changePassword($newPass, $token)
    {
        $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE token = :token";

        $params = [
            'contrasena' => $hashedPassword,
            'token' => $token
        ];

        return DataBase::execute($sql, $params);
    }

    // Método para get un usuario por su token
    public static function getUserByToken($token)
    {
        $sql = "SELECT * FROM usuarios WHERE token = :token";
        return DataBase::getRecords($sql, ['token' => $token]);
    }

    // Método para actualizar la información del usuario
    public static function ActualizarUser($id, $data)
    {
        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    email = :email,
                    contrasena = :contrasena,
                    rol = :rol,
                    activo = :activo
                WHERE id = :id";

        $data['id'] = $id; // Añadimos el ID a los parámetros
        return DataBase::execute($sql, $data);
    }

    // Método para get la lista de usuarios
    public static function getAllUsers()
    {
        $sql = "SELECT * FROM usuarios";
        return DataBase::getRecords($sql);
    }

public static function contarClientes()
{
    try {
        $db = DataBase::getInstance();
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result->total;
    } catch (PDOException $e) {
        throw new Exception("Error al contar los clientes: " . $e->getMessage());
    }
}






























}