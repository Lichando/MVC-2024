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

    // Método para buscar un usuario por su email
    public static function findEmail($email) {
        $model = new static();
        $sql = "SELECT * FROM " . $model->table . " WHERE " . $model->secundaryKey . " = :email";
        $params = ["email" => $email];
        $result = DataBase::query($sql, $params);

        if ($result) {
            foreach ($result as $key => $value) {
                $model->$key = $value;
            }
            return $model; // Retorna el modelo lleno si se encuentra
        } else {
            return null; // Retorna null si no se encuentra
        }
    }

    // Método para cambiar la contraseña
    public static function changePassword($newPass, $token) {
        $model = new static();
        $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT); // Asegúrate de hashear la nueva contraseña
        $sql = "UPDATE " . $model->table . " SET password = :password WHERE token = :token";

        try {
            $params = [
                'password' => $hashedPassword,
                'token' => $token
            ];
            $resultado = DataBase::execute($sql, $params); // Usa execute para ejecutar la consulta
            return [
                'state' => $resultado, // Retorna el estado del resultado
                'notification' => $resultado ? "Contraseña actualizada exitosamente." : "Error al actualizar la contraseña."
            ];
        } catch (PDOException $e) {
            return [
                'state' => false,
                'notification' => "Error en cambiar la contraseña: " . $e->getMessage() // Mensaje de error
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
		$result = DataBase::query($sql, $params);
	
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
