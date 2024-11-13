<?php 
namespace app\models;

use \DataBase;
use \Model;
use PDOException;

class PropiedadModel  extends Model
{
    // Método para obtener todas las propiedades con opción de búsqueda
    public static function getAllProperties($busqueda = '') {
        // Consulta SQL para obtener todas las propiedades, filtrando por nombre si se proporciona
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img";
        
        // Añadir condición de búsqueda si se proporciona
        if (!empty($busqueda)) {
            $query .= " WHERE p.nombre LIKE :busqueda";
        }

        return Database::getRecords($query, ['busqueda' => '%' . $busqueda . '%']); // Asegúrate de que esta función devuelva los resultados correctamente
    }

    // Método para obtener una propiedad por su ID
    public static function getPropertyById($id) {
        $query = "SELECT p.*, img.url AS imagen, inm.nombre AS inmobiliaria 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  LEFT JOIN inmobiliarias inm ON inm.id = p.id_inm 
                  WHERE p.id = :id LIMIT 1"; // LIMIT 1 para asegurarse de que solo se devuelve un resultado

        $resultado = Database::getRecords($query, ['id' => $id]);

        return !empty($resultado) ? $resultado[0] : null; // Si no se encuentra la propiedad, devuelve null
    }

    // Método para crear una nueva propiedad
    public static function create($data) {
        $query = "INSERT INTO propiedades (nombre, descripcion, precio, id_inm, id_img, id_geo, id_car)
                  VALUES (:nombre, :descripcion, :precio, :id_inm, :id_img, :id_geo, :id_car)";
        
        try {
            return Database::ejecutar($query, $data); // Maneja la inserción en la base de datos
        } catch (PDOException $e) {
            throw new PDOException("Error al crear la propiedad: " . $e->getMessage());
        }
    }

    // Método para actualizar una propiedad
    public static function update($id, $data) {
        $query = "UPDATE propiedades 
                  SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                      id_inm = :id_inm, id_img = :id_img, id_geo = :id_geo, id_car = :id_car 
                  WHERE id = :id";

        $data['id'] = $id; // Agrega el ID al arreglo de datos

        try {
            return Database::execute($query, $data); // Maneja la actualización en la base de datos
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar la propiedad: " . $e->getMessage());
        }
    }

    // Método para eliminar una propiedad (baja lógica)
    public static function bajaPropiedad($id) {
        $query = "UPDATE propiedades 
                  SET activo = 0 
                  WHERE id = :id";

        try {
            return Database::execute($query, ['id' => $id]); // Cambia el estado a inactivo
        } catch (PDOException $e) {
            throw new PDOException("Error al eliminar la propiedad: " . $e->getMessage());
        }
    }

    // Método para obtener todas las propiedades activas
    public static function getAllActiveProperties() {
        // Consulta SQL para obtener todas las propiedades activas
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  WHERE p.activo = 1"; // Solo propiedades activas

        return Database::getRecords($query); // Asegúrate de que esta función devuelva los resultados correctamente
    }
    public static function getInmobiliariaIdByUserId($userId) {
        // Consulta SQL para obtener el id de la inmobiliaria asociada al usuario
        $query = "SELECT id FROM inmobiliarias WHERE user_id = :user_id LIMIT 1"; // Limitamos a un solo resultado

        try {
            // Ejecutamos la consulta con el parámetro user_id
            $resultado = Database::getRecords($query, ['user_id' => $userId]);

            // Si encontramos un resultado, retornamos el ID de la inmobiliaria
            if (!empty($resultado)) {
                return $resultado[0]['id'];  // Devuelve el id de la inmobiliaria
            } else {
                return null;  // Si no hay resultado, retorna null
            }
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el ID de la inmobiliaria: " . $e->getMessage());
        }
    }
}
