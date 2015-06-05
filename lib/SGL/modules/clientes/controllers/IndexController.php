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
 * @subpackage clientes 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los clientes.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 * 	@subpackage clientes 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Clientes_IndexController extends modules_default_controllers_BaseController
{
	public function init(){
		$this->view->menu = 2;
		parent::init();
	}

	public function indexAction(){
		$model_clientes = new library_models_clientes;
		$select = $model_clientes->select()->order('id_cliente DESC');
		$this->setBuscador($select,array('nombres_cliente','apellidos_cliente','email_principal_cliente'));
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
		/*for($i=0;$i<10000;$i++){
			$model_clientes->insert(array('nombres_cliente' => 'Nombre Cliente '.$i,'apellidos_cliente'=> 'Apellido Cliente '.$i));
		}*/
	}

	public function vercasosAction(){
		
		$model_clientes = new library_models_clientes;
		$cliente = $model_clientes->find($this->_getParam('id_cliente'))->toArray();
		$this->view->cliente = $cliente[0];
		
		$adapter = new Zend_Paginator_Adapter_Array(library_casos_clientes_Factory::buscarCasosCliente($this->_getParam('id_cliente')));
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
	}
	
	public function agregarAction(){
		try{
			parent::agregarAction(array(
								'form'			=>	'library_form_Cliente',
								'sujeto'		=>	'Cliente',
								'id'			=>	$this->_getParam('id_cliente'),
								'campo_id'		=>	'id_cliente',
								'campo_nombre'	=>	'nombres_cliente',
								'model'			=>	'library_models_clientes',
								'mensaje_extra' =>	"Si desea agregarle un caso a este cliente, hacaga clic <a href='/casos/index/agregar/id_cliente/?'>aqu&iacute;</a>",
								'redirect'		=>	array( 'module'=>'clientes','controller'=>'index','action'=>'index')));
		}catch(Zend_Db_Statement_Exception $e){
			if($e->getCode()== "23000"){
				$this->view->correorepetido = true;
			}else{
				throw $e;
			}
		}
	}
	public function accionPostActualizar(array $registro = null){
		$cache = library_casos_Cache::getCache();
		$cache->remove('cliente_'.$registro['id_cliente']);
	}
	
	public function filtroPreGuardar(array $cliente){
		if(isset($cliente['password_cliente'])){
			$cliente['password_cliente'] = md5($cliente['password_cliente']);
		}
		unset($cliente['password_confirm']);
		return $cliente;
	}
	
	public function cambiarFormEditar(Zend_Form $form){
		$form->removeElement('password_cliente');
		$form->removeElement('password_confirm');
		return $form;
	}
	
	public function eliminarAction(){
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_cliente'),
								'campo_id'		=>	'id_cliente',
								'campo_nombre'	=>	'nombres_cliente',
								'model'			=>	'library_models_clientes',
								'redirect'		=>	array( 'module'=>'clientes','controller'=>'index','action'=>'index')));
	}
	public function accionPreEliminar(array $propiedades = null){
		//Borrar dependencias
		$model_casoscliente = new library_models_casoscliente;
		//Borrar toda la cache de los casos donde aparezca este cliente
		$casocliente = $model_casoscliente->select()->where('cliente_id ='.$propiedades['id'])->query()->fetchAll();
		$cache = library_casos_Cache::getCache();
		foreach($casocliente as $i=>$casocli){
			$cache->remove('casoscliente_'.$casocli['caso_id']);
		}
		//Elimino los registros cliente-caso de donde aparezca este cliente
		$model_casoscliente->delete('cliente_id = '.$propiedades['id']);		
	}
	public function accionPostEliminar(array $propiedades = null){
		//Eliminar la cache del abogado
		$this->accionPostActualizar(array('id_cliente'=>$propiedades['id']));		
	}	
}