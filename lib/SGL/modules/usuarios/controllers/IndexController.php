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
 * @subpackage usuarios 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los usuarios.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 * 	@subpackage usuarios 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Usuarios_IndexController extends modules_default_controllers_BaseController
{
	public function init(){
		$this->view->menu = 5;
		parent::init();
		//Aqui vamos a editar los usuarios que se encuentran en la base de datos de SGL
		$dbAdapter = $this->getInvokeArg('bootstrap')->getResource('db');
		Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);
		//Para que los acentos funcionen
		$dbAdapter->query("SET NAMES 'utf8'");		
	}
	/*
	*	Chequea que el id del usuario recibido por parametros pertenezca al escritorio juridico
	*	actualmente logeado.
	*/
	public function chequeoSeguridad($id){
		//Iniciar el modelo de usuarios
		$model = new library_models_usuarios;
		//Buscar al usuario cuyo id se recibio por parametros
		$datos = $model->find($id)->toArray();
		//Chequear que el usuario sea de este escritorio juridico
		if($datos[0]['escritorio_juridico_id'] != $this->escritorio_juridico['id_escritorio_juridico']){
			throw new Exception('No puede editar este usuario');
		}
		//No permitir borrar el cliente 1, admin
		if($id == 1){
			throw new Exception('En modo de demostracion no puede editar o eliminar el usuario admin');
		}
	}

	/*
	*	Lista los usuarios del sistema.
	*	Tambien llama a la funcion crearSuperAdmin.
	*/
	public function indexAction(){	
		$model_usuarios = new library_models_usuarios;
		//Para buscar los usuarios obligamos a que pertenezcan a este escritorio_juridico
		$select = $model_usuarios->select()->where('escritorio_juridico_id = '.$this->escritorio_juridico['id_escritorio_juridico']);
		$this->setBuscador($select, array('nombres_usuario','apellidos_usuario','email_principal_usuario'));
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
	}
	public function agregarAction(){
		/* Si es editar, vamos a chequear que el usuario pertenezca a este escritorio juridico */
		$id = intval($this->_getParam('id_usuario'));
		if($id > 0){
			$this->chequeoSeguridad($id);
		}
		try{
			parent::agregarAction(array(
									'form'			=>	'library_form_Usuario',
									'sujeto'		=>	'Usuario',
									'id'			=>	$this->_getParam('id_usuario'),
									'campo_id'		=>	'id_usuario',
									'campo_nombre'	=>	'nombres_usuario',
									'model'			=>	'library_models_usuarios',
									'redirect'		=>	array( 'module'=>'usuarios','controller'=>'index','action'=>'index')));
		}catch(Zend_Db_Statement_Exception $e){
			if($e->getCode() == "23000"){
				$this->view->correorepetido = true;
			}else{
				throw $e;
			}
		}
	}	
	public function cambiarFormEditar(Zend_Form $form){
		//Quitar el @dominio
		$email = $form->getElement('email_principal_usuario')->getValue();
		$email = str_replace('@'.$this->escritorio_juridico['dominio_escritorio_juridico'],"",$email);
		$form->getElement('email_principal_usuario')->setValue($email);
		return $this->cambiarFormAgregar($form);
	}
	public function cambiarFormAgregar(Zend_Form $form){
		//Poner como mensaje extra, luego del campo de email, el dominio del escritorio juridico
		$form->getElement('email_principal_usuario')->addDecorator('DescripcionExtendida',array('MensajeExtra' => '@'.$this->escritorio_juridico['dominio_escritorio_juridico']));
		return $form;
		
	}	
	public function filtroPreGuardar(array $usuario){
		//Codificar el password
		if(isset($usuario['password_usuario'])){
			$usuario['password_usuario'] = md5($usuario['password_usuario']);
		}
		//Obligar que el usuario que se va a guardar pertenezca a este escritorio juridico
		$usuario['escritorio_juridico_id'] = $this->escritorio_juridico['id_escritorio_juridico'];
		//Formar el usuario con su dominio
		$usuario['email_principal_usuario'] = $usuario['email_principal_usuario'] .'@'. $this->escritorio_juridico['dominio_escritorio_juridico'];
		//No necesitamos enviar la confirmacion del password
		unset($usuario['password_confirm']);
		return $usuario;
	}
	public function eliminarAction(){
		/* Vamos a chequear que el usuario pertenezca a este escritorio juridico */
		$this->chequeoSeguridad(intval($this->_getParam('id_usuario')));
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_usuario'),
								'campo_id'		=>	'id_usuario',
								'campo_nombre'	=>	'nombres_usuario',
								'model'			=>	'library_models_usuarios',
								'redirect'		=>	array( 'module'=>'usuarios','controller'=>'index','action'=>'index')));
	}	
}