<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

	protected function _initRequest()
    {
		$autoloader = Zend_Loader_Autoloader::getInstance();
		/*Se registra el namespace modules para poder tener modulos*/
		$autoloader->registerNamespace('modules_');
		$autoloader->registerNamespace('library_');
		
		/*Configurar el registry*/
		$registry = new Zend_Registry(array());
		Zend_Registry::setInstance($registry);
		
		/*Configurar la sesion*/
		$session_namespace = new Zend_Session_Namespace('STT_PaginaWeb_Session');
		$session_namespace->setExpirationSeconds(3600); //expira en 1 hora
		Zend_Registry::set('session', $session_namespace);
		
		/* Crear el objeto de traducciones */
		$translate = new Zend_Translate(
		    array(
		        'adapter' => 'array',
		        'content' => RAIZ. '/library/translate/mensajes.php',
		        'locale'  => 'es'
		    )
		);		
		//Poner las traducciones por defecto.
		Zend_Validate_Abstract::setDefaultTranslator($translate);
		
		
		/* Utilizar el nombre controls.phtml como defecto para el control del paginador. El paginador sera tipo google por defecto */
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('controls.phtml');
		Zend_Paginator::setDefaultScrollingStyle('Elastic');
		Zend_Paginator::setDefaultItemCountPerPage(20);	
	}
}