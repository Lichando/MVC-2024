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
    public $email;
    public $id;
    public $contrasena;
    public $rol;


    public static function authenticate($email, $password)
    {
        // Prepara la consulta para buscar el usuario
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $users = DataBase::getRecords($sql, ['email' => $email]);
    
        // Verifica si el usuario existe
        if (!empty($users)) {
            $user = $users[0]; // Obtiene el primer usuario
            // Verifica que la propiedad password exista
            if (isset($user->contrasena)) {
                // Verifica la contraseña
                if (password_verify($password, $user->contrasena)) {
                    return $user; // Retorna el usuario si la autenticación es exitosa
                }
            } else {
                throw new PDOException("La propiedad 'password' no existe en el usuario.");
            }
        }
        return null; // Retorna null si el usuario no fue encontrado o la contraseña es incorrecta
    }
    

    // Método para buscar un usuario por su email
    public static function findEmail($email) {
        $model = new static();
        $sql = "SELECT * FROM " . $model->table . " WHERE " . $model->secundaryKey . " = :email";
        $params = ["email" => $email];
        $result = DataBase::getRecords($sql, $params);

        if ($result) {
            foreach ($result as $key => $value) {
                $model->$key = $value;
            }
            return $model;
        }
        return null;
    }

    public static function createUser($nombre, $email, $hashedPassword, $rol = 4) {
        $model = new static();
        $sql = "INSERT INTO " . $model->table . " (nombre, email, contrasena, rol, fecha_registro) 
                VALUES (:nombre, :email, :contrasena, :rol, CURDATE())";
        
        try {
            $params = [
                'nombre' => $nombre,
                'email' => $email,
                'contrasena' => $hashedPassword,
                'rol' => $rol
            ];
            return DataBase::execute($sql, $params);
        } catch (PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage()); // Log de error
            return false;
        }
    }

    

    // Método para cambiar la contraseña
    public static function changePassword($newPass, $token) {
        $model = new static();
        $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
        $sql = "UPDATE " . $model->table . " SET contrasena = :contrasena WHERE token = :token";

        try {
            $params = [
                'contrasena' => $hashedPassword,
                'token' => $token
            ];
            $resultado = DataBase::execute($sql, $params);
            return [
                'state' => $resultado,
                'notification' => $resultado ? "Contraseña actualizada exitosamente." : "Error al actualizar la contraseña."
            ];
        } catch (PDOException $e) {
            error_log("Error al cambiar la contraseña: " . $e->getMessage()); // Log de error
            return [
                'state' => false,
                'notification' => "Error en cambiar la contraseña: " . $e->getMessage()
            ];
        }
    }

    public function actionResetPassword() {
        $email = $_POST['email'];
        $newPassword = $_POST['new_password'];
        $token = $_POST['token'];

        $user = UserModel::findEmail($email);

        if ($user) {
            $result = UserModel::changePassword($newPassword, $token);
            echo $result['notification'];
        } else {
            echo "El usuario no existe.";
        }
    }

    public static function getUserByToken($token) {
        $model = new static();
        $sql = "SELECT * FROM " . $model->table . " WHERE token = :token";
        $params = ["token" => $token];
        $result = DataBase::getRecords($sql, $params);

        if ($result) {
            foreach ($result as $key => $value) {
                $model->$key = $value;
            }
            return $model;
        }
        return null;
    }
}


