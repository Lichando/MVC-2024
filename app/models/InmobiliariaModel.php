<?php

namespace app\models;

use app\controllers\SessionController;
use \DataBase;
use \Model;
use Exception;

class InmobiliariaModel extends Model
{
    protected $table = "inmobiliarias";
    protected $primaryKey = "id";

    // Obtener todas las inmobiliarias
    public static function obtenerInmobiliarias()
    {
        $sql = "SELECT * FROM inmobiliarias";
        return DataBase::getRecords($sql);
    }
    public static function obtenerInmobiliariasActivas()
    {
        try {
            $db = DataBase::getInstance(); // Suponiendo que tienes un singleton para la base de datos
            $sql = "SELECT * FROM inmobiliarias WHERE activo = 1"; // Cambia la consulta según tu estructura
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Devuelve un array con los resultados
        } catch (PDOException $e) {
            throw new Exception("Error al obtener inmobiliarias: " . $e->getMessage());
        }
    }

    public static function obtenerInmobiliariasInactivas() {
        try {
            $db = DataBase::getInstance(); // Suponiendo que tienes un singleton para la base de datos
            $sql = "SELECT * FROM inmobiliarias WHERE activo = 0"; // Cambia la consulta según tu estructura
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Devuelve un array con los resultados
        } catch (\PDOException $e) {
            throw new Exception("Error al obtener inmobiliarias: " . $e->getMessage());
        }
    }


    // Obtener una inmobiliaria por ID
    public static function getInmobiliariasId($id)
    {
        $sql = "SELECT * FROM inmobiliarias WHERE id = :id";
        return DataBase::getRecords($sql, ['id' => $id]);
    }
    public static function crearInmobiliaria($duenioInmobiliaria, $nombre,$nombreImagen, $matricula, $direccion, $telefono, $email)
    {
        // Verificar si el dueño ya tiene una inmobiliaria
        $query = "SELECT COUNT(*) FROM inmobiliarias WHERE duenioInmobiliaria = :duenioInmobiliaria";
        $params = [':duenioInmobiliaria' => $duenioInmobiliaria];
        $result = DataBase::fetchOne($query, $params);

        if ($result[0] > 0) {
            throw new Exception('Ya tenes una inmobiliaria registrada.');
        }

        // Verificar si la matrícula ya existe
        $query = "SELECT COUNT(*) FROM inmobiliarias WHERE matricula = :matricula";
        $params = [':matricula' => $matricula];
        $result = DataBase::fetchOne($query, $params);
        if ($result[0] > 0) {
            throw new Exception('La matrícula ya está registrada.');
        }

        // Insertar la nueva inmobiliaria
        $query = "INSERT INTO inmobiliarias (duenioInmobiliaria, nombre,imagen, matricula, direccion, telefono, email, fecha_creacion, activo) 
              VALUES (:duenioInmobiliaria, :nombre,:imagen, :matricula, :direccion, :telefono, :email, NOW(), 1)";
        $params = [
            ':duenioInmobiliaria' => $duenioInmobiliaria,
            ':nombre' => $nombre,
            ':imagen'=>$nombreImagen,
            ':matricula' => $matricula,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':email' => $email
        ];

        // Ejecutar la inserción
        DataBase::execute($query, $params);

        // Obtener el ID de la inmobiliaria insertada
        $inmobiliariaId = DataBase::lastInsertId();  // Asegúrate de que la base de datos lo soporte

        // Asignar el inmobiliaria_id al usuario
        $query = "UPDATE usuarios SET inmobiliaria_id = :inmobiliaria_id WHERE id = :duenioInmobiliaria";
        $params = [
            ':inmobiliaria_id' => $inmobiliariaId,
            ':duenioInmobiliaria' => $duenioInmobiliaria
        ];
        DataBase::execute($query, $params);

        return $inmobiliariaId; // Retornar el ID de la inmobiliaria recién creada
    }

    // Método para asignar el rol de administrador inmobiliaria
    public static function asignarRolInmobiliaria($duenioInmobiliaria, $inmobiliariaId)
    {
        // Actualizamos el rol del usuario a 'Administrador Inmobiliaria'
        $query = "UPDATE usuarios SET rol = 3, inmobiliaria_id = :inmobiliaria_id WHERE id = :duenioInmobiliaria";
        $params = [
            ':inmobiliaria_id' => $inmobiliariaId,
            ':duenioInmobiliaria' => $duenioInmobiliaria
        ];

        // Ejecutar la consulta
        DataBase::execute($query, $params);
    }


    // Actualizar una inmobiliaria
    public static function ActualizarInmobiliaria($id, $data)
    {
        $sql = "UPDATE inmobiliarias
                SET nombre = :nombre, 
                    imagen=:imagen,
                    duenioInmobiliaria = :duenioInmobiliaria, 
                    matricula = :matricula,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    fecha_creacion = :fecha_creacion,
                    activo = :activo
                WHERE id = :id";
        $data['id'] = $id;
        return DataBase::execute($sql, $data);
    }

    // Activar inmobiliaria
    public static function activarInmobiliaria($id)
    {
        $consulta = "UPDATE inmobiliarias SET activo = 1 WHERE id = :id";
        $params = ['id' => $id];
        Database::execute($consulta, $params);
    }

    // Dar de baja (inactivar) una inmobiliaria
    public static function bajaInmobiliaria($id)
    {
        $sql = "UPDATE inmobiliarias SET activo = 0 WHERE id = :id";
        return DataBase::execute($sql, ['id' => $id]);
    }

    public static function contarInmobiliarias()
    {
        try {
            $db = DataBase::getInstance();
            $stmt = $db->query("SELECT COUNT(*) as total FROM inmobiliarias");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->total;
        } catch (\PDOException $e) {
            throw new Exception("Error al contar las inmobiliarias: " . $e->getMessage());
        }
    }


    // Obtener corredores y agentes asociados a una inmobiliaria
    public static function getCorredoresYAgentes($inmobiliariaId)
    {
        $sql = "SELECT u.id, u.email, u.rol
                FROM usuarios u
                INNER JOIN inmobiliarias i ON u.id = i.duenioInmobiliaria 
                WHERE i.id = :inmobiliariaId AND u.rol IN (4, 5)";
        return DataBase::getRecords($sql, ['inmobiliariaId' => $inmobiliariaId]);
    }

    // Verificar si el usuario es el dueño de la inmobiliaria
    public static function ValidarDuenio($id, $userId)
    {
        $sql = "SELECT COUNT(*) as total FROM inmobiliarias WHERE id = :id AND duenioInmobiliaria = :userId";
        $result = DataBase::getRecords($sql, ['id' => $id, 'userId' => $userId]);
        return $result[0]->total > 0;
    }



    // Verificar si el usuario tiene una inmobiliaria
    public static function usuarioTieneInmobiliaria($userId)
    {
        $sql = "SELECT COUNT(*) as total FROM inmobiliarias WHERE duenioInmobiliaria = :userId";
        $result = DataBase::getRecords($sql, ['userId' => $userId]);
        return $result[0]->total > 0;  // Devuelve true si el usuario tiene inmobiliaria
    }

    public static function asignarCorredorOAgente($inmobiliariaId, $userEmail, $rol)
    {
        // Validar que el rol sea uno de los permitidos: Corredor (4) o Agente (5)
        if (!in_array($rol, [4, 5])) {
            throw new Exception('Rol no válido. Debe ser Corredor (4) o Agente (5).');
        }

        // Verificar que el usuario existe y obtener su información
        $usuario = self::getUsuarioPorEmail($userEmail);
        if (!$usuario) {
            throw new Exception('Usuario no encontrado.');
        }

        // Verificar si el usuario ya está asignado a la inmobiliaria
        if ($usuario['inmobiliaria'] != null && $usuario['inmobiliaria'] != $inmobiliariaId) {
            throw new Exception('Este usuario ya está asignado a otra inmobiliaria.');
        }

        // Asignar o actualizar la inmobiliaria y el rol del usuario
        $query = "UPDATE usuarios SET inmobiliaria = :inmobiliariaId, rol = :rol WHERE email = :email";
        $params = [
            'inmobiliariaId' => $inmobiliariaId,
            'rol' => $rol,
            'email' => $userEmail
        ];
        DataBase::execute($query, $params);

        return true;
    }

    public static function getUsuarioPorEmail($email)
    {
        // Obtener usuario por su email
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $params = ['email' => $email];
        return DataBase::fetchOne($query, $params);
    }



    public static function ObtenerPropiedadesTotalesInmo($inmobiliariaId)
    {
        try {
            $db = DataBase::getInstance();
            $stmt = $db->query("SELECT COUNT(*) as total FROM propiedades WHERE id_inm=$inmobiliariaId");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->total;
        } catch (PDOException $e) {
            throw new Exception("Error al contar las propiedades: " . $e->getMessage());
        }
    }


    public static function ObtenerPropiedadesActivasInmo($inmobiliariaId)
    {
        try {
            $db = DataBase::getInstance();
            $stmt = $db->query("SELECT COUNT(*) as total FROM propiedades WHERE id_inm=$inmobiliariaId AND activo=1");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->total;
        } catch (PDOException $e) {
            throw new Exception("Error al contar las propiedades: " . $e->getMessage());
        }
    }

    public static function ObtenerPropiedadesInactivasInmo($inmobiliariaId)
    {
        try {
            $db = DataBase::getInstance();
            $stmt = $db->query("SELECT COUNT(*) as total FROM propiedades WHERE id_inm=$inmobiliariaId AND activo=0");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->total;
        } catch (PDOException $e) {
            throw new Exception("Error al contar las propiedades: " . $e->getMessage());
        }
    }

}
