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
 *	Controlador para el manejo de errores
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 *  @subpackage default 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class ErrorController extends Zend_Controller_Action
{
	public function errorAction()
    {
    	//Cargar los helpers necesarios. Esto se hace aqui ,en la base y en el de errores
		$this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;
		$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");			
        $this->view->noDojo = true;
        
        //Tratar el error
		$errors = $this->_getParam('error_handler');
		$exception = $errors->exception;
		//La proxima linea es solo a efectos de debugging, hay que quitarla para produccion
		echo "<pre>";print_r($exception);echo "</pre>";

		switch ($errors->type) {
        	case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        		$this->view->Mensaje = 'Esta pagina o controlador no existe';
        		break;
        	case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        		$this->view->Mensaje = 'Esta pagina o accion no existe';
        		break;
        	default:
        		$this->view->Mensaje = $exception->getMessage();
		}            	

    }
}