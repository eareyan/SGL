<?php

class AliadosController extends modules_default_controllers_BaseController{

	public function indexAction(){
		//Accion dummy para tener URL mas faciles, por ejemplo, /demo,/nosotros...
		$this->_forward('aliados','index','default');
	}
}