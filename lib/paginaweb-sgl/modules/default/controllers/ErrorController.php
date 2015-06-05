<?php

class ErrorController extends Zend_Controller_Action
{
	public function errorAction()
    {
		//Cargar los helpers necesarios. Esto se hace aqui ,en la base y en el de errores
		$this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;
    
		$errors = $this->_getParam('error_handler');
		$exception = $errors->exception;
		echo "<pre>";print_r($exception);echo "</pre>";
		//$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");		
		//$request=$this->getRequest();
		//$this->view->module = $request->getModuleName();
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