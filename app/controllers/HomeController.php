<?php 
namespace app\controllers;
use \Controller;
use \Response;


class HomeController extends Controller
{

    // Constructor
    public function __construct(){

    }

	public function actionIndex($var = null){
		Echo 'hola desde index de home';
	}

	public function actionInicio(){
		$head = SiteController::head();
		$nav = SiteController::nav();
		$path = static::path();
		$footer = SiteController::footer();
		Response::render($this->viewDir(__NAMESPACE__),"inicio", [
																"title" => 'Inicio MVC',
																 "head" => $head,
																 "nav" => $nav,	
																 "footer" => $footer,	
																]);
	}

	public function action404(){
		$head = SiteController::head();
		$nav = SiteController::nav();
		$footer = SiteController::footer();
		Response::render($this->viewDir(__NAMESPACE__),"404", [
																"title" => $this->title.' 404',
																"head" => $head,
																"nav" => $nav,
																"footer" => $footer,		
															   ]);
	}

	private function actionHola(){
		echo 'hola';
	}
}