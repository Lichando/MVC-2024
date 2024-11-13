<?php

namespace app\models;

use \DataBase;
use \Model;
use PDO;
use PDOException;

class EstadisticaModel extends Model
{
    // Método para obtener el ranking de inmobiliarias por propiedades vendidas
    public static function getTopInmobiliariasPorVentas()
    {
        try {
            $sql = "
                SELECT 
                    inm.id AS inmobiliaria_id,
                    inm.nombre AS inmobiliaria_nombre,
                    COUNT(prop.id) AS propiedades_vendidas
                FROM 
                    inmobiliarias inm
                LEFT JOIN 
                    propiedades prop ON prop.inmobiliaria_id = inm.id
                LEFT JOIN 
                    ventas ven ON ven.propiedad_id = prop.id
                WHERE 
                    prop.estado_venta = 'vendida'
                GROUP BY 
                    inm.id
                ORDER BY 
                    propiedades_vendidas DESC;
            ";

            return DataBase::getRecords($sql);
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el ranking de inmobiliarias por ventas: " . $e->getMessage());
        }
    }

    // Método para obtener el ranking de propiedades más vistas
    public static function getTopPropiedadesPorVistas()
    {
        try {
            $sql = "
                SELECT 
                    prop.id AS propiedad_id,
                    prop.nombre AS propiedad_nombre,
                    COUNT(con.id) AS total_vistas
                FROM 
                    propiedades prop
                LEFT JOIN 
                    consultas con ON con.propiedad_id = prop.id
                GROUP BY 
                    prop.id
                ORDER BY 
                    total_vistas DESC;
            ";

            return DataBase::getRecords($sql);
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el ranking de propiedades más vistas: " . $e->getMessage());
        }
    }

    // Método para obtener el ranking de agentes más consultados dentro de una inmobiliaria
    public static function getTopVendedoresPorConsultas($inmobiliariaId)
    {
        try {
            $sql = "
                SELECT 
                    usr.id AS agente_id,
                    usr.nombre AS agente_nombre,
                    COUNT(con.id) AS total_consultas
                FROM 
                    usuarios usr
                LEFT JOIN 
                    consultas con ON con.usuario_id = usr.id
                LEFT JOIN 
                    propiedades prop ON prop.id = con.propiedad_id
                WHERE 
                    prop.inmobiliaria_id = :inmobiliaria_id
                GROUP BY 
                    usr.id
                ORDER BY 
                    total_consultas DESC;
            ";

            $params = ['inmobiliaria_id' => $inmobiliariaId];
            return DataBase::getRecords($sql, $params);
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el ranking de agentes más consultados: " . $e->getMessage());
        }
    }

    // Método para obtener el ranking de inmobiliarias por puntuación
    public static function getTopInmobiliariasPorPuntuacion()
    {
        try {
            $sql = "
                SELECT 
                    inm.id AS inmobiliaria_id,
                    inm.nombre AS inmobiliaria_nombre,
                    AVG(opin.puntuacion) AS puntuacion_promedio
                FROM 
                    inmobiliarias inm
                LEFT JOIN 
                    opiniones opin ON opin.inmobiliaria_id = inm.id
                GROUP BY 
                    inm.id
                ORDER BY 
                    puntuacion_promedio DESC;
            ";

            return DataBase::getRecords($sql);
        } catch (PDOException $e) {
            throw new PDOException("Error al obtener el ranking de inmobiliarias por puntuación: " . $e->getMessage());
        }
    }
}
