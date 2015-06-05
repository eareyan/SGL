<?php
/**
 * Sistema para Bufete Legal
 * 
 * LICENCIA
 * 
 * Todos los derechos reservados.
 *
 * @category   BufeteLegal
 * @package    sistadministrativo
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Bootstrap del sistema administrativo.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
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
		$session_namespace = new Zend_Session_Namespace('STTSession');
		$session_namespace->setExpirationSeconds(3600); //expira en 1 hora
		Zend_Registry::set('session', $session_namespace);
		
		/*Configurar la base de datos*/
		$session = Zend_Registry::get('session');
    	if(isset($session->db)){
			Zend_Db_Table_Abstract::setDefaultAdapter($session->db);
			//Para que los acentos funcionen
			$session->db->query("SET NAMES 'utf8'");			
    	}		
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