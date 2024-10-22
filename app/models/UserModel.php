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
    public $id; // Añadimos esta propiedad

    public $contrasena; // Añadimos esta propiedad
    public $rol;        // Añadimos esta propiedad

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
            return $model; // Retorna el modelo lleno si se encuentra
        } else {
            return null; // Retorna null si no se encuentra
        }
    }

    public static function createUser($email, $hashedPassword, $rol) {
        $model = new static();
        $sql = "INSERT INTO " . $model->table . " (email, contrasena, rol) VALUES (:email, :contrasena, :rol)";
        
        try {
            $params = [
                'email' => $email,
                'contrasena' => $hashedPassword,
                'rol' => $rol
            ];
            $resultado = DataBase::execute($sql, $params);
            return $resultado; // Retorna true si la inserción fue exitosa
        } catch (PDOException $e) {
            return false; // Si hay un error, retorna false
        }
    }
    
    // Método para cambiar la contraseña
    public static function changePassword($newPass, $token) {
        $model = new static();
        $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT); // Hasheamos la nueva contraseña
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
            return [
                'state' => false,
                'notification' => "Error en cambiar la contraseña: " . $e->getMessage()
            ];
        }
    }

	
	public function actionResetPassword() {
		// Suponiendo que recibes el email y el nuevo password de un formulario
		$email = $_POST['email'];
		$newPassword = $_POST['new_password'];
		$token = $_POST['token']; // Token recibido por email
	
		// Primero buscamos al usuario por su email
		$user = UserModel::findEmail($email);
	
		if ($user) {
			// Si el usuario existe, intentamos cambiar la contraseña
			$result = UserModel::changePassword($newPassword, $token);
			
			if ($result['state']) {
				// Contraseña cambiada exitosamente
				echo $result['notification'];
			} else {
				// Hubo un problema al cambiar la contraseña
				echo $result['notification'];
			}
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
			return $model; // Retorna el modelo lleno si se encuentra
		} else {
			return null; // Retorna null si no se encuentra
		}
	}
	
}
