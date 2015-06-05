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
 * @subpackage abogados 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los abogados.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 * 	@subpackage abogados 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Abogados_IndexController extends modules_default_controllers_BaseController
{

	public function init(){
		$this->view->menu = 3;
		parent::init();
	}

	public function indexAction(){
		$model_abogados = new library_models_abogados;
		$select = $model_abogados->select()->order('id_abogado DESC');
		$this->setBuscador($select,array('nombres_abogado','apellidos_abogado','email_principal_abogado'));
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
		/*for($i=0;$i<10000;$i++){
			$model_abogados->insert(array('nombres_abogado' => 'Pedro '.$i,'apellidos_abogado'=> 'Perez '.$i));
		}*/			
	}
	
	public function vercasosAction(){
		
		$model_abogados = new library_models_abogados;
		$abogado = $model_abogados->find($this->_getParam('id_abogado'))->toArray();
		if(!isset($abogado[0])){
			throw new Exception('El abogado '.$this->_getParam('id_abogado').' no existe');
		}
		$this->view->abogado = $abogado[0];
		
		$adapter = new Zend_Paginator_Adapter_Array(library_casos_abogados_Factory::buscarCasosAbogado($this->_getParam('id_abogado')));
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
	}	
	
	public function agregarAction(){
		parent::agregarAction(array(
								'form'			=>	'library_form_Abogado',
								'sujeto'		=>	'Abogado',
								'id'			=>	$this->_getParam('id_abogado'),
								'campo_id'		=>	'id_abogado',
								'campo_nombre'	=>	'nombres_abogado',
								'model'			=>	'library_models_abogados',
								'mensaje_extra' =>	"Si desea agregarle un caso a este abogado, haga clic <a href='/casos/index/agregar/id_abogado/?'>aqu&iacute;</a>",								
								'redirect'		=>	array( 'module'=>'abogados','controller'=>'index','action'=>'index')));
	}
	public function accionPostActualizar(array $registro = null){
		$cache = library_casos_Cache::getCache();
		$cache->remove('abogado_'.$registro['id_abogado']);
	}	
	
	public function eliminarAction(){
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_abogado'),
								'sujeto'		=>	'Abogado',
								'campo_id'		=>	'id_abogado',
								'campo_nombre'	=>	'nombres_abogado',
								'model'			=>	'library_models_abogados',
								'redirect'		=>	array( 'module'=>'abogados','controller'=>'index','action'=>'index')));
	}
	public function accionPreEliminar(array $propiedades = null){
		//Borrar dependencias
		$model_casosabogado = new library_models_casosabogado;
		//Borrar toda la cache de los casos donde aparezca este abogado
		$casosabogado = $model_casosabogado->select()->where('abogado_id ='.$propiedades['id'])->query()->fetchAll();
		$cache = library_casos_Cache::getCache();
		foreach($casosabogado as $i=>$casoabo){
			$cache->remove('casosabogado_'.$casoabo['caso_id']);
		}
		//Elimino los registros abogado-caso de donde aparezca este abogado
		$model_casosabogado->delete('abogado_id = '.$propiedades['id']);
	}
	public function accionPostEliminar(array $propiedades = null){
		//Eliminar la cache del abogado
		$this->accionPostActualizar(array('id_abogado'=>$propiedades['id']));		
	}
}