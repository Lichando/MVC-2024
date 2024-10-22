<?php 
/**
 * Clase para mostrar las vistas
 */
class Response
{
    // Constructor privado para evitar instanciación
    private function __construct() {}

    // Renderizar una vista con variables
    public static function render($viewDir, $view, $vars = [])
    {
        // Validar las variables antes de asignarlas dinámicamente
        foreach ($vars as $key => $value) {
            if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)) {
                $$key = $value;
            }
        }

        $viewPath = APP_PATH . "views/" . $viewDir . "/". $view . ".php";

        // Verificar si la vista existe antes de cargarla
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("La vista $view no se encuentra en el directorio $viewDir");
        }
    }
        // Método para redirigir a otra URL
    public static function redirect($url)
    {
        // Verifica si la URL no está vacía
        if (empty($url)) {
            throw new Exception("La URL de redirección no puede estar vacía.");
        }

        // Enviar el encabezado de redirección
        header("Location: $url");
        
        // Detener la ejecución del script
        exit; 
    }

}
