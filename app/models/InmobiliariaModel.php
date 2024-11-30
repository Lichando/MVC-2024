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
    public static function getAllInmobiliarias()
    {
        $sql = "SELECT * FROM inmobiliarias";
        return DataBase::getRecords($sql);
    }

    // Obtener una inmobiliaria por ID
    public static function getInmobiliariasId($id)
    {
        $sql = "SELECT * FROM inmobiliarias WHERE id = :id";
        return DataBase::getRecords($sql, ['id' => $id]);
    }

    // Crear inmobiliaria
    public static function crearInmobiliaria($duenioInmobiliaria, $nombre, $matricula, $direccion, $telefono, $email)
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
        $query = "INSERT INTO inmobiliarias (duenioInmobiliaria, nombre, matricula, direccion, telefono, email, fecha_creacion, activo) 
                  VALUES (:duenioInmobiliaria, :nombre, :matricula, :direccion, :telefono, :email, NOW(), 1)";
        $params = [
            ':duenioInmobiliaria' => $duenioInmobiliaria,
            ':nombre' => $nombre,
            ':matricula' => $matricula,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':email' => $email
        ];

        // Ejecutar la inserción
        DataBase::execute($query, $params);

        // Asignar el rol 3 (Administrador Inmobiliaria) al dueño
        self::asignarRolInmobiliaria($duenioInmobiliaria);

        return true; // Retornar true para indicar que la operación fue exitosa
    }

    public static function asignarRolInmobiliaria($duenioInmobiliaria)
    {
        // Actualizar el rol del usuario a '3' (Administrador Inmobiliaria)
        $query = "UPDATE usuarios SET rol = 3 WHERE id = :duenioInmobiliaria";
        $params = [':duenioInmobiliaria' => $duenioInmobiliaria];

        DataBase::execute($query, $params);

        // Actualizar la sesión del usuario con el nuevo rol
        SessionController::login($duenioInmobiliaria, 3, SessionController::getSessionValue('user_name'), $duenioInmobiliaria);

    }

    // Actualizar una inmobiliaria
    public static function ActualizarInmobiliaria($id, $data)
    {
        $sql = "UPDATE inmobiliarias
                SET nombre = :nombre, 
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

    // Obtener inmobiliarias con búsqueda y paginación
    public static function getInmobiliariasConPaginacion($pagina, $limite, $buscar = '')
    {
        // Validar parámetros
        $pagina = max(1, (int) $pagina);
        $limite = max(1, (int) $limite);
        $offset = ($pagina - 1) * $limite;

        $sql = "SELECT * FROM inmobiliarias WHERE 1";

        // Añadir condiciones de búsqueda si corresponde
        $parametros = [];
        if (!empty($buscar)) {
            $sql .= " AND (nombre LIKE :buscar OR direccion LIKE :buscar OR telefono LIKE :buscar)";
            $parametros['buscar'] = '%' . $buscar . '%';
        }

        // Añadir LIMIT y OFFSET (directamente en la consulta)
        $sql .= " LIMIT $limite OFFSET $offset";

        // Ejecutar la consulta
        return DataBase::getRecords($sql, $parametros);
    }

    // Contar total de inmobiliarias (con opción de búsqueda)
    public static function contarInmobiliarias($buscar = '')
    {
        $sql = "SELECT COUNT(*) as total FROM inmobiliarias WHERE 1";

        $parametros = [];
        if (!empty($buscar)) {
            $sql .= " AND (nombre LIKE :buscar OR direccion LIKE :buscar OR telefono LIKE :buscar)";
            $parametros['buscar'] = '%' . $buscar . '%';
        }

        $result = DataBase::getRecords($sql, $parametros);
        return $result[0]->total ?? 0;
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


    public static function getInmobiliariaInfo($userId)
    {
        $query = "
            SELECT u.inmobiliaria_id, i.nombre
            FROM usuarios u
            JOIN inmobiliarias i ON i.id = u.inmobiliaria_id
            WHERE u.id = :userId
        ";

        $stmt = DataBase::prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch los resultados como un array asociativo
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retornar la información o null si no existe
        return $result ?: null;
    }
}
