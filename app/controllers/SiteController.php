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

	public static function head(){
		static::path();
		$head = file_get_contents(APP_PATH.'/views/inc/head.php');
		$head = str_replace('#PATH#', self::$ruta, $head);
		return $head;
	}
	public static function nav(){
		static::path();
		$nav = file_get_contents(APP_PATH.'/views/inc/nav.php');
		$nav = str_replace('#PATH#', self::$ruta, $nav);
		return $nav;
	}
	public static function footer(){
		static::path();
		$footer = file_get_contents(APP_PATH.'/views/inc/footer.php');
		$footer = str_replace('#PATH#', self::$ruta, $footer);
		return $footer;
	}

	
}
