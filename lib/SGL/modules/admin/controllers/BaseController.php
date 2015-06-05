<?php

class modules_admin_controllers_BaseController extends modules_default_controllers_BaseController{
	public function init(){
		parent::init();
		//Obtengo la sesion del usuario
		$session= Zend_Registry::get('session');
		//Verificar si el usuario esta logeado como super admin en el sistema	
		if(!$session->isLoggedInSuperAdmin){
			//No esta logeado, por lo tanto lo redirecciono al index
			$this->_helper->redirector('index', 'index','index');
			return;
		}
		//Colocar la base de datos maestra como la por defecto
		$dbAdapter = $this->getInvokeArg('bootstrap')->getResource('db');
		Zend_Db_Table::setDefaultAdapter($dbAdapter);		
	}
}