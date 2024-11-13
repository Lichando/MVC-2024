<?php

namespace app\models;

use \DataBase;
use \Model;
use PDOException;
use Exception;

class InmobiliariaModel extends Model
{
    // Este modelo ahora usará la conexión gestionada por la clase base Model
    protected $table = "inmobiliarias";
    protected $primaryKey = "id";

    // Método para obtener todas las inmobiliarias
    public static function getAllInmobiliarias()
    {
        $sql = "SELECT * FROM inmobiliarias";
        return DataBase::getRecords($sql); // Obtiene todos los registros de inmobiliarias
    }

    // Método para obtener una inmobiliaria por su ID
    public static function getInmobiliariaById($id)
    {
        $sql = "SELECT * FROM inmobiliarias WHERE id = :id";
        return DataBase::getRecords($sql, ['id' => $id]);
    }

    // Método para obtener el ID de la inmobiliaria por el ID del dueño
    public static function getInmobiliariaIdByOwner($ownerId)
    {
        $sql = "SELECT id FROM inmobiliarias WHERE dueñoInmobiliaria = :ownerId";
        return DataBase::getRecords($sql, ['ownerId' => $ownerId]);
    }

    // Método para crear una inmobiliaria
    public static function create($data)
    {
        $sql = "INSERT INTO inmobiliarias (nombre, dueñoInmobiliaria, matricula, direccion, telefono, email, fecha_creacion, activo)
                VALUES (:nombre, :dueñoInmobiliaria, :matricula, :direccion, :telefono, :email, :fecha_creacion, :activo)";
        return DataBase::execute($sql, $data); // Ejecuta la consulta de inserción
    }

    // Método para actualizar los datos de una inmobiliaria
    public static function update($id, $data)
    {
        $sql = "UPDATE inmobiliarias
                SET nombre = :nombre, 
                    dueñoInmobiliaria = :dueñoInmobiliaria, 
                    matricula = :matricula,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    fecha_creacion = :fecha_creacion,
                    activo = :activo
                WHERE id = :id";
        $data['id'] = $id;
        return DataBase::execute($sql, $data); // Ejecuta la consulta de actualización
    }

    // Método para eliminar una inmobiliaria (físicamente) - No se usa, se utiliza la baja lógica
    public static function delete($id)
    {
        $sql = "DELETE FROM inmobiliarias WHERE id = :id";
        return DataBase::execute($sql, ['id' => $id]); // Elimina la inmobiliaria de la base de datos
    }

    // Método para dar de baja (inactivar) una inmobiliaria
    public static function bajaInmobiliaria($id)
    {
        // Cambiar el estado de la inmobiliaria a "inactiva" (activo = 0)
        $sql = "UPDATE inmobiliarias SET activo = 0 WHERE id = :id";
        return DataBase::execute($sql, ['id' => $id]);
    }

    // Método para activar una inmobiliaria
    public static function activarInmobiliaria($id)
    {
        // Cambiar el estado a "activo" (activo = 1)
        $sql = "UPDATE inmobiliarias SET activo = 1 WHERE id= :id";
        return DataBase::execute($sql, ['id' => $id]);
    }

    // Método de validación de propiedad: Verificar que el usuario es el dueño de la inmobiliaria
    public static function validateOwnership($inmobiliariaId, $userId)
    {
        $sql = "SELECT 1 FROM inmobiliarias WHERE id= :id AND dueñoInmobiliaria = :userId";
        $resultado = DataBase::getRecords($sql, ['inmobiliariaId' => $inmobiliariaId, 'userId' => $userId]);

        return !empty($resultado); // Devuelve true si existe la relación, false si no
    }

    // Método para asignar un rol (de corredor o agente) a un usuario
    public static function asignarRol($inmobiliariaId, $userEmail, $rol)
    {
        // Verificar si el rol es válido
        if (!in_array($rol, [4, 5])) { // Solo puede asignar rol 4 o 5 (corredor y agente)
            throw new Exception("Rol no válido.");
        }

        // Verificar que el usuario no tiene rol de corredor o agente (roles 3 y 4)
        $sql = "SELECT id FROM usuarios WHERE email = :email AND rol NOT IN (3, 4)";
        $user = DataBase::getRecords($sql, ['email' => $userEmail]);

        if (empty($user)) {
            throw new Exception("El usuario no existe o no puede asignarse este rol.");
        }

        $userId = $user[0]->id; // Suponemos que se obtiene el primer resultado

        // Actualizar el rol del usuario
        $sql = "UPDATE usuarios SET rol = :role WHERE id = :userId";
        return DataBase::execute($sql, ['role' => $rol, 'userId' => $userId]);
    }

    // Método para asignar rol de Corredor o Agente a un usuario
    public static function asignarCorredorOAgente($inmobiliariaId, $userEmail, $rol)
    {
        // Verificar que el dueño de la inmobiliaria está realizando la acción
        if (!self::validateOwnership($inmobiliariaId, SessionController::getUserId())) {
            throw new Exception("No tienes permiso para asignar roles en esta inmobiliaria.");
        }

        // Verificar que el rol es válido (corredorInmobiliario o agenteInmobiliario)
        if (!in_array($rol, [4, 5])) {
            throw new Exception("Rol no válido.");
        }

        // Asignar el rol al usuario
        return self::asignarRol($inmobiliariaId, $userEmail, $rol);
    }

    // Método para obtener los corredores y agentes asociados a una inmobiliaria
    public static function getCorredoresYAgentes($inmobiliariaId)
    {
        $sql = "SELECT u.id, u.email, u.rol
                FROM usuarios u
                INNER JOIN inmobiliarias i ON u.id = i.dueñoInmobiliaria WHERE i.id = :inmobiliariaId AND u.rol IN (4, 5)";
        return DataBase::getRecords($sql, ['inmobiliariaId' => $inmobiliariaId]);
    }

}
