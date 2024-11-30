<?php
namespace app\models;

use \DataBase;
use \Model;
use PDOException;

class PropiedadModel extends Model
{
    // Método para get todas las propiedades con opción de búsqueda
    public static function getTodasPropiedades($busqueda = '')
    {
        // Consulta SQL para obtener todas las propiedades, filtrando por nombre si se proporciona
        $consulta = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                      FROM propiedades p 
                      LEFT JOIN imagenes img ON img.id = p.id_img";

        // Añadir condición de búsqueda si se proporciona
        $params = [];
        if (!empty($busqueda)) {
            $consulta .= " WHERE p.nombre LIKE :busqueda";
            $params['busqueda'] = '%' . $busqueda . '%';  // Solo agregar parámetro si hay búsqueda
        }

        return Database::getRecords($consulta, $params); // Pasar parámetros correctamente
    }


    // Método para get una propiedad por su ID
    public static function getPropiedadPorId($id)
    {
        $consulta = "SELECT p.*, img.url AS imagen, inm.nombre AS inmobiliaria 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  LEFT JOIN inmobiliarias inm ON inm.id = p.id_inm 
                  WHERE p.id = :id LIMIT 1"; // LIMIT 1 para asegurarse de que solo se devuelve un resultado

        $resultado = Database::getRecords($consulta, ['id' => $id]);

        return !empty($resultado) ? $resultado[0] : null; // Si no se encuentra la propiedad, devuelve null
    }

    public static function CrearPropiedad($nombre, $descripcion, $precio, $Id, $id_geo, $id_car)
    {
        // Validar parámetros
        if (empty($nombre) || empty($descripcion) || !is_numeric($precio) || $precio <= 0) {
            throw new PDOException("Datos de propiedad inválidos. Verifique los parámetros.");
        }

        try {
            // Iniciar la transacción
            DataBase::connection()->beginTransaction();

            // 1. Obtener el id_inm asociado al usuario
            $consultaInmobiliaria = "SELECT id FROM inmobiliarias WHERE user_id = :user_id LIMIT 1";
            $stmt = DataBase::connection()->prepare($consultaInmobiliaria);
            $stmt->execute(['user_id' => $userId]);
            $inmobiliaria = $stmt->fetch(mode: \PDO::FETCH_ASSOC);

            if (!$inmobiliaria) {
                throw new PDOException("No se encontró la inmobiliaria asociada al usuario con ID $userId.");
            }
            $id_inm = $inmobiliaria['id']; // Obtener el ID de la inmobiliaria

            // 2. Insertar una imagen (suponiendo que se inserta una URL por defecto)
            $consultaImagen = "INSERT INTO imagenes (url) VALUES ('default.jpg')";
            DataBase::connection()->exec($consultaImagen);  // Usamos connection() aquí también
            $id_img = DataBase::connection()->lastInsertId();  // Obtener el ID de la imagen recién insertada

            // 3. Insertar la propiedad
            $consultaPropiedad = "INSERT INTO propiedades (nombre, descripcion, precio, id_inm, id_img, id_geo, id_car, id_estado, activo) 
                             VALUES (:nombre, :descripcion, :precio, :id_inm, :id_img, :id_geo, :id_car, 1, 1)";
            $stmt = DataBase::connection()->prepare($consultaPropiedad);
            $stmt->execute([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'id_inm' => $id_inm,
                'id_img' => $id_img,
                'id_geo' => $id_geo,
                'id_car' => $id_car
            ]);

            // 4. Obtener el ID de la propiedad recién insertada
            $lastInsertId = DataBase::connection()->lastInsertId();  // Usamos connection() nuevamente

            // Confirmar la transacción
            DataBase::connection()->commit();  // Usamos connection() para commit

            // Retornar el ID de la propiedad insertada
            return $lastInsertId;
        } catch (PDOException $e) {
            // Si algo falla, revertimos la transacción
            DataBase::connection()->rollBack();  // Usamos connection() para rollBack
            throw new PDOException("Error al crear la propiedad: " . $e->getMessage());
        }
    }


    // Método para actualizar una propiedad
    public static function ActualizarPropiedad($id, $data)
    {
        $consulta = "UPDATE propiedades 
                  SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                      id_inm = :id_inm, id_img = :id_img, id_geo = :id_geo, id_car = :id_car 
                  WHERE id = :id";

        $data['id'] = $id; // Agrega el ID al arreglo de datos

        try {
            return Database::execute($consulta, $data); // Maneja la actualización en la base de datos
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar la propiedad: " . $e->getMessage());
        }
    }

    // Método para eliminar una propiedad (baja lógica)
    public static function BajaPropiedad($id)
    {
        $consulta = "UPDATE propiedades 
                  SET activo = 0 
                  WHERE id = :id";

        try {
            return Database::execute($consulta, ['id' => $id]); // Cambia el estado a inactivo
        } catch (PDOException $e) {
            throw new PDOException("Error al eliminar la propiedad: " . $e->getMessage());
        }
    }

    // Método para obtener todas las propiedades activas
    public static function getPropiedadesActivas()
    {
        $consulta = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img
                  WHERE p.activo = 1";  // Solo las propiedades activas

        return Database::getRecords($consulta);  // Retorna todas las propiedades activas
    }

    // Método para obtener propiedades por inmobiliaria
    public static function getPropiedadesPorInmobiliaria($inmobiliariaId)
    {
        $consulta = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  WHERE p.id_inm = :id_inm AND p.activo = 1"; // Solo las propiedades activas

        return Database::getRecords($consulta, ['id_inm' => $inmobiliariaId]);  // Devuelve las propiedades de la inmobiliaria
    }

    // Método para obtener propiedades de acuerdo a un filtro de búsqueda
    public static function buscarPropiedades($busqueda)
    {
        $consulta = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.id_inm, img.url AS imagen 
                  FROM propiedades p 
                  LEFT JOIN imagenes img ON img.id = p.id_img 
                  WHERE p.nombre LIKE :busqueda AND p.activo = 1";

        $params = ['busqueda' => '%' . $busqueda . '%'];  // Filtrar por nombre de propiedad
        return Database::getRecords($consulta, $params);  // Devuelve propiedades que coinciden con la búsqueda
    }

    public static function getInmobiliariaPorIdUsuario($userId)
    {
        // Consulta SQL para get el id de la inmobiliaria asociada al usuario
        $consulta = "SELECT id FROM inmobiliarias WHERE user_id = :user_id LIMIT 1"; // Limitamos a un solo resultado

        try {
            // Ejecutamos la consulta con el parámetro user_id
            $resultado = Database::getRecords($consulta, ['user_id' => $userId]);

            // Si encontramos un resultado, retornamos el ID de la inmobiliaria
            if (!empty($resultado)) {
                return $resultado[0]['id'];  // Devuelve el id de la inmobiliaria
            } else {
                return null;  // Si no hay resultado, retorna null
            }
        } catch (PDOException $e) {
            throw new PDOException("Error al get el ID de la inmobiliaria: " . $e->getMessage());
        }
    }
}
