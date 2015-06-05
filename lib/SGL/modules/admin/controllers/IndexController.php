<?php

class Admin_IndexController extends modules_admin_controllers_BaseController
{
	public function indexAction(){
		$this->_helper->layout()->disableLayout();
		$usuarios_model = new library_models_usuarios;
		$this->view->usuarios = $usuarios_model->select()->query()->fetchAll();	
	}
	public function hacerloginAction(){
		//Deshabilitar la vista y layout
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		//Buscar los datos del usuario
		$usuarios_model = new library_models_usuarios;
		$datos_usuario = $usuarios_model->find($this->_getParam('id_usuario'))->toArray();
		//Hacer login
		library_admin_login::login($this->getInvokeArg('bootstrap')->getResource('db'),$datos_usuario[0]);
		//Redireccionar a los casos
		$this->_helper->redirector('index','index', 'casos');
	}
	public function reseteardemoAction(){
		//Deshabilitar la vista y layout
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$ubicacion_cron = RAIZ."/cron/reseteardemo.php";
		//$ubicacion_cron = RAIZ."/cron/notificacionpago.php";
		require $ubicacion_cron;
		/*echo "<h2>Corriendo cron de resetear demo en: ".$ubicacion_cron."</h2>";
		$resultado =  exec($ubicacion_cron);
		echo "<h3>Resultado</h3>";
		if($resultado==""){
			echo "<pre>Resultado vac&iacute;o</pre>";
		}else{
			echo "<pre style='background-color:black;color:white'>".$resultado."</pre>";
		}*/
		echo "<a href='/admin'>Regresar</a>";
	}
}