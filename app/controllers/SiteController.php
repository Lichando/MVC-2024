<?php 
namespace app\controllers;
use \Controller;
use app\models\CategoriaModel;

class SiteController extends Controller
{
    // Constructor
    public function __construct(){
        // self::$sessionStatus = SessionController::sessionVerificacion();
    }

	public static function head() {
		static::path(); // Genera la URL base
		$head = file_get_contents(APP_PATH . '/views/inc/head.php');
		$head = str_replace('#PATH#', self::$ruta, $head);
		return $head;
	}
	
	public static function header() {
		static::path(); // Genera la URL base
		$header = file_get_contents(APP_PATH . '/views/inc/header.php');
		$header = str_replace('#PATH#', self::$ruta, $header);
		return $header;
	}
	
	public static function footer() {
		static::path(); // Genera la URL base
		$footer = file_get_contents(APP_PATH . '/views/inc/footer.php');
		$footer = str_replace('#PATH#', self::$ruta, $footer);
		return $footer;
	}
	
	public static function scripts() {
		static::path(); // Genera la URL base
		$scripts = file_get_contents(APP_PATH . '/views/inc/script.php');
		$scripts = str_replace('#PATH#', self::$ruta, $scripts);
		return $scripts;
	}
	
	
}
