<?php

class modules_default_controllers_BaseController extends Zend_Controller_Action
{
	public function init(){
		//Cargar los helpers necesarios que utilizaran la mayoria de los controladores
		$this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;	
        //Agregar otra ruta para las vistas.. la idea de esto es tener un solo archivo para el paginador
		$this->view->addScriptPath(RAIZ.'/library/view/helper/');
		$this->view->addHelperPath('library/view/helper/', 'library_view_helper');
		//$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");		
        
	}
}