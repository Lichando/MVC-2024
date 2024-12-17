<?php 
class Controller
{
    protected $title = 'MVC Proyecto | ';
    protected static $sessionStatus;
    public static $ruta;

    public function actionIndex($var = null){
        $this->action404();
        // echo "funcionando";
    }
    
    // Obtiene el path base para la URL
    public static function path() {
        // Detecta el protocolo (http o https)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        
        // Obtén el host (localhost o dominio)
        $host = $_SERVER['HTTP_HOST'];
        
        // Obtener el script name para saber en qué directorio se encuentra el archivo
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); // Ejemplo: '/MVC-2024/public'
        
        // Si el script se ejecuta desde 'public', eliminamos 'public' de la ruta
        $basePath = str_replace('/public', '', $scriptName);
        
        // Añadir la barra al final si no existe
        $basePath = rtrim($basePath, '/') . '/';
        
        // Combina todo para obtener la URL base
        self::$ruta = $protocol . $host . $basePath;
        
        return self::$ruta;
    }
    
    

    protected function viewDir($nameSpace){
        $replace = array($nameSpace,'Controller');
        $viewDir = str_replace($replace , '', get_class($this)).'/';
        $viewDir = str_replace('\\', '', $viewDir);
        $viewDir = strtolower($viewDir);
        return $viewDir;
    }

    public function action404(){
        // echo "Error 404 - Página no encontrada - CONTROLLER";
        static::path();
        header('Location:'.self::$ruta.'404');
    }

    // Genera un token de seguridad
    public static function generarToken($longitud = 32)
    {
        return bin2hex(random_bytes($longitud));
    }

    // Genera un token de seguridad simplificado
    protected static function tokenSeguro($longitud = 25)
    {
        return self::generarToken($longitud);
    }


}
