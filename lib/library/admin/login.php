<?php

class library_admin_login{

	public static function login($db , $datos_usuario){
		$model_escritorios_juridicos = new library_models_escritoriosjuridicos(array('db'=>$db));
		$escritorio_juridico = $model_escritorios_juridicos->find($datos_usuario['escritorio_juridico_id'])->toArray();
		//Verificar que el escritorio juridico en cuestion exista
		if(!isset($escritorio_juridico[0])){
			throw new Exception('Este usuario no tiene asignado ningun escritorio juridico');
		} 
		//Conectarse a la base de datos del escritorio juridico
		$options = array('host'		=>	'localhost',
						 //'port'	 	=>	'8889',
		 				 'password' =>	$escritorio_juridico[0]['password_db_escritorio_juridico'],
						 'username'	=>	$escritorio_juridico[0]['username_db_escritorio_juridico'],
						 'dbname'	=> 	$escritorio_juridico[0]['dbname_db_escritorio_juridico']);
		$db = Zend_Db::factory('pdo_mysql', $options);
		//Para que los acentos funcionen
		$db->query("SET NAMES 'utf8'");					
		//Guardar que el login fue exitoso y la base de datos del cliente
		$session = Zend_Registry::get('session');				
		$session->escritorio_juridico = $escritorio_juridico[0];
		$session->db = $db;
		$session->isLoggedIn = true;
	}
}