<?php 
namespace app\models;

use \DataBase;
use \Model;
use PDOException;


class PropiedadModel
{
    // Método para obtener todas las propiedades
    public static function getAllProperties() {
        // Consulta SQL para obtener todas las propiedades
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img"; // Obtiene la imagen relacionada

        return Database::getRecords($query); // Asegúrate de que esta función devuelva los resultados correctamente
    }

    // Método para obtener una propiedad por su ID
    public static function getPropertyById($id) {
        $query = "SELECT p.*, img.url AS imagen, inm.nombre AS inmobiliaria 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  LEFT JOIN inmobiliarias inm ON inm.id = p.id_inm 
                  WHERE p.id = :id";
        
        return Database::getRecords($query, ['id' => $id]); // Aquí deberías manejar la consulta con parámetros
    }

    // Método para crear una nueva propiedad
    public static function create($data) {
        $query = "INSERT INTO propiedades (nombre, descripcion, precio, id_inm, id_img, id_geo, id_car)
                  VALUES (:nombre, :descripcion, :precio, :id_inm, :id_img, :id_geo, :id_car)";
        
        try {
            return Database::ejecutar($query, $data); // Maneja la inserción en la base de datos
        } catch (PDOException $e) {
            // Manejo de errores, puedes registrarlo o lanzar una excepción
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
     public static function delete($id) {
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
}
