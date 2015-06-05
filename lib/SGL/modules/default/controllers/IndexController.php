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
 * @subpackage modules 
 * @subpackage default 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador de inicio. Es el unico que no extiende de la base sino directamente de Zend_Controller_Action
 *	porque para verlo el usuario no necesita hacer login.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 *  @subpackage default 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class IndexController extends Zend_Controller_Action
{
	public function indexAction(){
	
		//Cargar los helpers necesarios. Esto se hace aqui ,en la base y en el de errores
		$this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;
		$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');	
		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");		
        $this->view->noDojo = true;
		/*Verificar que el usuario ya este logeado */
	
    	if(Zend_Registry::get('session')->isLoggedIn){
    		//Redireccionar al usuario si ya esta logeado
	    	$this->_helper->redirector('index', 'casos');	
    	}else{
			//Inicializar el formulario de login
			$form = new library_form_Login();
			$this->view->LoginForm = $form;
			//Poner usuario y password por defecto
			if($this->_getParam('username') != null){
				$form->getElement('username')->setValue($this->_getParam('username'));
			}
			$this->view->hideMenu = true;
			//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces hacer la validacion
			if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
				/* Hacer la validacion */
				//Obtener el adaptador de la bd
				$dbAdapter = $this->getInvokeArg('bootstrap')->getResource('db');
				//Para que los acentos funcionen
				$dbAdapter->query("SET NAMES 'utf8'");
				//Ver si es el super admin
				if($form->getValue('username') == "admin" && $form->getValue('password') == "elpasswordloco"){
					//Si es el super admin, logearlo como tal
					$session = Zend_Registry::get('session');				
					$session->isLoggedIn = true;					
					$session->isLoggedInSuperAdmin = true;
					$this->_helper->redirector('index', 'index','admin');
				}
				//--- Continua logeo normal ---//
				//Se va a hacer la autenticacion con el objeto Zend_Auth
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
				$authAdapter->setTableName('usuarios')
						    ->setIdentityColumn('email_principal_usuario')
				    		->setCredentialColumn('password_usuario')
							->setIdentity($form->getValue('username'))
						    ->setCredential(md5($form->getValue('password')));
				$result = $authAdapter->authenticate();
				if($result->isValid()){
					//Hacer Login del usuario
					$datos_usuario = $authAdapter->getResultRowObject();
					library_admin_login::login($dbAdapter,array('escritorio_juridico_id'=>$datos_usuario->escritorio_juridico_id));
					//Redireccionarlo a los casos
					$this->_helper->redirector('index', 'casos');		
				}else{
					//Reportar que no pudo hacer login
					$this->view->FalloLogin = true;
				}
			}
		}
	}
	public function logoutAction(){
		//Logout al usuario y limpiar la sesion
		Zend_Session::destroy(true);
		$this->_helper->redirector('index');
	}
}