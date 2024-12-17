<?php
namespace app\models;

use \DataBase;
use \Model;
use PDOException;

class PropiedadModel extends Model
{

    public static function ObtenerPropiedadesActivas()
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM propiedades WHERE activo = 1";

        try {
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die('Error en la consulta: ' . $e->getMessage());
        }
    }

    public static function ObtenerPropiedadPorId($idpropiedad)
    {
        try {
            // Preparar la consulta SQL para obtener una propiedad por su ID
            $sql = "SELECT * FROM propiedades WHERE id = :idpropiedad";
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':idpropiedad', $idpropiedad, \PDO::PARAM_INT);
            $stmt->execute();

            // Recuperar una sola propiedad
            return $stmt->fetch(\PDO::FETCH_OBJ); // Utiliza PDO::FETCH_OBJ para obtener la propiedad como un objeto
        } catch (PDOException $e) {
            // Manejo de errores
            throw new PDOException("Error al obtener la propiedad: " . $e->getMessage());
        }
    }

    public static function crearPropiedad(
        $inmobiliariaId,
        $direccionFake,
        $direccionTrue,
        $moneda,
        $precio,
        $descripcion,
        $img1,
        $img2,
        $img3,
        $idEstado,
        $activo,
        $banos,
        $ambientes,
        $dormitorios,
        $metros
    ) {
        try {
            // Validar que los datos no estén vacíos o nulos
            if (empty($direccionFake) || empty($direccionTrue) || empty($moneda) || empty($precio) || empty($descripcion) || empty($idEstado)) {
                throw new PDOException("Todos los campos obligatorios deben ser completados.");
            }

            // Preparar la consulta SQL para insertar la propiedad
            $sql = "INSERT INTO propiedades (
                        id_inm, direccionFake, direccionTrue, moneda, precio, descripcion, 
                        img1, img2, img3, id_estado, activo, banos, ambientes, dormitorios, metros
                    ) VALUES (
                        :id_inm, :direccionFake, :direccionTrue, :moneda, :precio, :descripcion, 
                        :img1, :img2, :img3, :id_estado, :activo, :banos, :ambientes, :dormitorios, :metros
                    )";

            // Obtener la conexión de la base de datos
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':id_inm', $inmobiliariaId);
            $stmt->bindParam(':direccionFake', $direccionFake);
            $stmt->bindParam(':direccionTrue', $direccionTrue);
            $stmt->bindParam(':moneda', $moneda);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':img1', $img1);
            $stmt->bindParam(':img2', $img2);
            $stmt->bindParam(':img3', $img3);
            $stmt->bindParam(':id_estado', $idEstado);
            $stmt->bindParam(':activo', $activo);
            $stmt->bindParam(':banos', $banos);
            $stmt->bindParam(':ambientes', $ambientes);
            $stmt->bindParam(':dormitorios', $dormitorios);
            $stmt->bindParam(':metros', $metros);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el último ID insertado (ID de la propiedad)
            $propiedadId = $db->lastInsertId();

            return $propiedadId; // Devuelve el ID de la propiedad creada
        } catch (PDOException $e) {
            // Manejo de errores
            throw new PDOException("Error al crear la propiedad: " . $e->getMessage());
        }
    }


    // Método para obtener las propiedades de una inmobiliaria
    public static function ObtenerPropiedadesPorInmobiliaria($inmobiliariaId)
    {
        try {
            // Preparar la consulta SQL para obtener las propiedades de la inmobiliaria
            $sql = "SELECT * FROM propiedades WHERE id_inm = :inmobiliaria_id";
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':inmobiliaria_id', $inmobiliariaId);
            $stmt->execute();

            // Recuperar todas las propiedades
            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            // Manejo de errores
            throw new pdoException("Error al obtener propiedades: " . $e->getMessage());
        }
    }

    // Método para obtener las imágenes de una propiedad
    public static function obtenerImagenesPorPropiedad($propiedadId)
    {
        try {
            // Preparar la consulta SQL para obtener las imágenes de la propiedad
            $sql = "SELECT * FROM propiedades WHERE propiedad_id = :propiedad_id";
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':propiedad_id', $propiedadId);
            $stmt->execute();

            // Recuperar todas las imágenes
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            throw new PDOException("Error al obtener imágenes: " . $e->getMessage());
        }
    }



    // Método para actualizar una propiedad
    // Método para actualizar una propiedad
    public static function ActualizarPropiedad($idpropiedad, $data)
    {
        // Consulta de actualización SQL
        $consulta = "UPDATE propiedades 
                SET direccionFake = :direccionFake, direccionTrue = :direccionTrue, 
                    moneda = :moneda, precio = :precio, descripcion = :descripcion, 
                    id_estado = :id_estado, 
                    banos = :banos, ambientes = :ambientes, 
                    dormitorios = :dormitorios, metros = :metros
                WHERE id = :id";

        // Añadimos el ID de la propiedad al arreglo de datos
        $data['id'] = $idpropiedad;

        try {
            // Ejecutamos la consulta de actualización
            return Database::execute($consulta, $data);
        } catch (PDOException $e) {
            // Si ocurre un error, lanzamos una excepción
            throw new PDOException("Error al actualizar la propiedad: " . $e->getMessage());
        }
    }

    public static function AltaPropiedad($idpropiedad, $estado)
    {
        try {
            // Preparar la consulta para actualizar el estado de la propiedad
            $query = "UPDATE propiedades SET activo = :estado WHERE id = :id";
            $stmt = DataBase::getInstance()->prepare($query);

            // Ejecutar la consulta
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $idpropiedad);

            // Ejecutar la consulta y verificar si la actualización fue exitosa
            $stmt->execute();

            // Verificar si se afectaron filas
            return $stmt->rowCount() > 0; // Retorna true si se actualizó alguna fila, false si no
        } catch (PDOException $e) {
            // Manejar cualquier error en la consulta
            return false;
        }
    }

    public static function bajaPropiedad($idpropiedad, $estado)
    {
        try {
            // Preparar la consulta para actualizar el estado de la propiedad
            $query = "UPDATE propiedades SET activo = :estado WHERE id = :id";
            $stmt = DataBase::getInstance()->prepare($query);

            // Ejecutar la consulta
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $idpropiedad);

            // Ejecutar la consulta y verificar si la actualización fue exitosa
            $stmt->execute();

            // Verificar si se afectaron filas
            return $stmt->rowCount() > 0; // Retorna true si se actualizó alguna fila, false si no
        } catch (PDOException $e) {
            // Manejar cualquier error en la consulta
            return false;
        }
    }





    // Método para obtener todas las propiedades activas
    public static function ObtenerPropiedadesActivasPorInmobiliaria($inmobiliariaId)
    {
        try {
            // Preparar la consulta SQL para obtener las propiedades de la inmobiliaria
            $sql = "SELECT * FROM propiedades WHERE activo = 1 AND id_inm = :inmobiliaria_id";
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':inmobiliaria_id', $inmobiliariaId);
            $stmt->execute();

            // Recuperar todas las propiedades
            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            // Manejo de errores
            throw new pdoException("Error al obtener propiedades: " . $e->getMessage());
        }
    }
    public static function ObtenerPropiedadesInactivasPorInmobiliaria($inmobiliariaId)
    {
        try {
            // Preparar la consulta SQL para obtener las propiedades de la inmobiliaria
            $sql = "SELECT * FROM propiedades WHERE activo = 0 AND id_inm = :inmobiliaria_id";
            $db = DataBase::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':inmobiliaria_id', $inmobiliariaId);
            $stmt->execute();

            // Recuperar todas las propiedades
            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            // Manejo de errores
            throw new pdoException("Error al obtener propiedades: " . $e->getMessage());
        }
    }






    // Método para obtener propiedades por inmobiliaria
    public static function getPropiedadesPorInmobiliaria($inmobiliariaId)
    {
        $consulta = "SELECT p.id, p.direccionFake, p.descripcion, p.precio, p.id_inm, p.img1 AS imagen 
                  FROM propiedades p 
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




    public static function contarPropiedades()
    {
        try {
            $db = DataBase::getInstance();
            $stmt = $db->query("SELECT COUNT(*) as total FROM propiedades");
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result->total;
        } catch (PDOException $e) {
            throw new Exception("Error al contar las propiedades: " . $e->getMessage());
        }
    }




    public static function ObtenerUltimasPropiedades()
    {
        try {
            // Instancia de la base de datos
            $db = DataBase::getInstance();

            // Preparar la consulta
            $sql = "SELECT * FROM propiedades ORDER BY id DESC LIMIT 5";
            $stmt = $db->prepare($sql);

            // Ejecutar la consulta
            $stmt->execute();

            // Retornar los resultados como objetos
            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            // Manejo de errores
            throw new PDOException("Error al obtener las últimas propiedades: " . $e->getMessage());
        }
    }
    public static function ObtenerPropiedadesPorBusqueda($direccionTrue, $id_estado, $banos, $dormitorios)
    {
        // Consulta base para obtener todas las propiedades activas
        $sql = "SELECT * FROM propiedades WHERE activo = 1";

        // Filtrar por dirección
        if (!empty($direccionTrue)) {
            $sql .= " AND direccionTrue LIKE :direccionTrue";
        }

        // Filtrar por estado, solo si el valor es 1, 2 o 3
        if (!empty($id_estado) && in_array($id_estado, [1, 2, 3])) {
            $sql .= " AND id_estado = :id_estado";
        }

        // Filtrar por baños
        if (!empty($banos)) {
            if ($banos == '5') {
                // Para "5 o más", usamos >=
                $sql .= " AND banos >= 5";
            } else {
                // Para otros valores, usamos el igual (=)
                $sql .= " AND banos = :banos";
            }
        }

        // Filtrar por dormitorios
        if (!empty($dormitorios)) {
            if ($dormitorios === '5') {
                // Para "5 o más", usamos >=
                $sql .= " AND dormitorios >= 5";
            } else {
                // Para otros valores, usamos el igual (=)
                $sql .= " AND dormitorios = :dormitorios";
            }
        }

        // Preparar la consulta
        $db = DataBase::getInstance();
        $stmt = $db->prepare($sql);

        // Asociar los valores a los parámetros en la consulta
        if (!empty($direccionTrue)) {
            $direccionTrueParam = "%$direccionTrue%";  // Asignamos a una variable para LIKE
            $stmt->bindParam(':direccionTrue', $direccionTrueParam);
        }

        if (!empty($id_estado) && in_array($id_estado, [1, 2, 3])) {
            $stmt->bindParam(':id_estado', $id_estado);
        }

        if (!empty($banos)) {
            // Si no es '5', asignamos el valor del parámetro
            if ($banos !== '5') {
                $stmt->bindParam(':banos', $banos);
            }
        }

        if (!empty($dormitorios)) {
            // Si no es '5', asignamos el valor del parámetro
            if ($dormitorios !== '5') {
                $stmt->bindParam(':dormitorios', $dormitorios);
            }
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Retornar los resultados de la búsqueda
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }










}