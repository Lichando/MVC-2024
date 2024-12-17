<?php
namespace app\controllers;
use app\models\InmobiliariaModel;
use app\models\PropiedadModel;
use app\models\UserModel;
use \Controller;
use \Response;


class HomeController extends Controller
{

	// Constructor
	public function __construct()
	{

	}

	public function actionIndex($var = null)
	{
		echo 'hola desde index de home';
	}

	public function actionInicio()
	{
		$contInmo = InmobiliariaController::ContadorInmobiliarias();
		$contUsuario=UserModel::contarClientes();
		$contPropiedades=PropiedadModel::contarPropiedades();
		$nuevasPropiedades = PropiedadModel::ObtenerUltimasPropiedades();
    	$inmobiliariasActivas=InmobiliariaController::inmobiliariasActivas();
		$head = SiteController::head();
		$header = SiteController::header();
		$scripts = SiteController::scripts();
		$path = static::path();
		$footer = SiteController::footer();

		Response::render($this->viewDir(__NAMESPACE__), "inicio", [
			"title" => 'Inicio MVC',
			"head" => $head,
			"header" => $header,
			"contadorInmo" => $contInmo,
			"contadorUser"=>$contUsuario,
			"contadorPropiedades"=>$contPropiedades,
			"propiedades_mostrar" => $nuevasPropiedades,
			"inmobiliarias"=>$inmobiliariasActivas,
			"scripts" => $scripts,
			"footer" => $footer,
		]);
	}
	public function actionContacto()
	{
	
		$head = SiteController::head();
		$header = SiteController::header();
		$scripts = SiteController::scripts();
		$path = static::path();
		$footer = SiteController::footer();

		Response::render($this->viewDir(__NAMESPACE__), "contacto", [
			"title" => 'Inicio MVC',
			"head" => $head,
			"header" => $header,
			"scripts" => $scripts,
			"footer" => $footer,
		]);
	}
	public function actionEmpresa()
	{

		$head = SiteController::head();
		$header = SiteController::header();
		$scripts = SiteController::scripts();
		$path = static::path();
		$footer = SiteController::footer();

		Response::render($this->viewDir(__NAMESPACE__), "empresa", [
			"title" => 'Inicio MVC',
			"head" => $head,
			"header" => $header,
			"scripts" => $scripts,
			"footer" => $footer,
		]);
	}

	public function action404()
	{
		$head = SiteController::head();
		$header = SiteController::header();
		$footer = SiteController::footer();
		Response::render($this->viewDir(__NAMESPACE__), "404", [
			"title" => $this->title . ' 404',
			"head" => $head,
			"header" => $header,
			"footer" => $footer,
		]);
	}

	private function actionHola()
	{
		echo 'hola';
	}
}