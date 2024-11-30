<?php 
namespace app\controllers;
use \Controller;
use app\models\UserModel;

class UserController extends Controller
{
    // Constructor
    public function __construct(){

    }
    
	public function actionIndex($var = null){
		self::action404();
	}

	/*obtiene todos los datos de un usuarios por id o por email según dato ingresado*/
	public static function getUser($emailOrId){
		if (filter_var($emailOrId, FILTER_VALIDATE_EMAIL)) {
			# get datos de usuario por Email
			$userData = UserModel::BuscarEmail($emailOrId);
			// var_dump($userData);
		}else{
			# get datos de usuario por Id
			$userData = UserModel::findId($emailOrId);
			// var_dump($userData);
		}
		return $userData;
	}

	/*obtiene todos los datos de un usuarios token*/
	public static function getUserbytoken($token){

		# get datos de usuario por token
		$userData = UserModel::getUserbytoken($token);

		return $userData;
	}




}