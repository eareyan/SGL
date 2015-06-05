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
 * @subpackage configuracion 
 * @subpackage cuentas 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de las cuentas dentro de las configuraciones.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 *  @subpackage configuracion 
 *  @subpackage cuentas 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Configuracion_CuentasController extends modules_default_controllers_BaseController
{
	public function init(){
		$this->view->menu = 6;
		parent::init();
	}

	public function indexAction(){
		$model_cuentas = new library_models_cuentas;
		$select = $model_cuentas->select()->order('id_cuenta DESC');
		$this->setBuscador($select,array('nombre_cuenta','banco_cuenta','titular_cuenta','numero_cuenta','tipo_cuenta','identificacion_cuenta'));
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
		/*for($i=0;$i<10000;$i++){
			$model_cuentas->insert(array('nombre_ley' => 'ley '.$i,'fecha_publicacion_ley'=> time()));
		}*/		
	}
	
	public function agregarAction(){
		parent::agregarAction(array(
								'form'			=>	'library_form_Configuracion_Cuenta',
								'sujeto'		=>	'Cuenta Bancaria',
								'id'			=>	$this->_getParam('id_cuenta'),
								'campo_id'		=>	'id_cuenta',
								'campo_nombre'	=>	'nombre_cuenta',
								'model'			=>	'library_models_cuentas',
								'redirect'		=>	array( 'module'=>'configuracion','controller'=>'cuentas','action'=>'index')));
	}
	public function accionPostActualizar(array $registro = null){
		$cache = library_casos_Factory::getCache();
		$cache->remove('cuenta_'.$registro['id_cuenta']);
	}	
	
	public function eliminarAction(){
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_cuenta'),
								'campo_id'		=>	'id_cuenta',
								'campo_nombre'	=>	'nombre_cuenta',
								'model'			=>	'library_models_cuentas',
								'redirect'		=>	array( 'module'=>'configuracion','controller'=>'cuentas','action'=>'index')));
	}	
	public function accionPreEliminar(array $propiedades = null){
		//Borrar esta cuenta de los casos que la tengan
		$model_casos = new library_models_casos;
		$model_casos->update(array('cuenta_id'=>''),'cuenta_id = '.$propiedades['id']);
		//Eliminar la cache de la cuenta
		$this->accionPostActualizar(array('id_cuenta'=>$propiedades['id']));				
	}	
}