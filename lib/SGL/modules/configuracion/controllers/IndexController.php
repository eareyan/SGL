<?php


class Configuracion_IndexController extends modules_default_controllers_BaseController{
	public function init(){
		$this->view->menu = 6;
		parent::init();
	}
	public function indexAction(){}

}