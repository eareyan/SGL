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
 * @subpackage leyes 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los leyes.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 * 	@subpackage leyes 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Leyes_IndexController extends modules_default_controllers_BaseController
{
	public function init(){
		$this->view->menu = 4;
		parent::init();
	}

	public static function getPathLeyes(){
		return PUBLIC_HTML.'/leyes/'.library_gestion_config::getIdEscritorioJuridico();
	}

	public function verleyAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$file = self::getPathLeyes().'/'.$this->_getParam('archivo');

		if (file_exists($file) && is_file($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.basename($file));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    ob_clean();
		    flush();
		    readfile($file);
		    exit;
		}
	}

	public function indexAction(){
		$model_leyes = new library_models_leyes;	
		$select = $model_leyes->select()->order('id_ley DESC');
		$this->setBuscador($select,array('nombre_ley'));
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;		
	}
	
	public function agregarAction(){
		parent::agregarAction(array(
								'form'			=>	'library_form_Ley',
								'sujeto'		=>	'Ley',
								'id'			=>	$this->_getParam('id_ley'),
								'campo_id'		=>	'id_ley',
								'campo_nombre'	=>	'nombre_ley',
								'model'			=>	'library_models_leyes',
								'redirect'		=>	array( 'module'=>'leyes','controller'=>'index','action'=>'index')));
	}
	
	public function eliminarAction(){
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_ley'),
								'campo_id'		=>	'id_ley',
								'campo_nombre'	=>	'nombre_ley',
								'model'			=>	'library_models_leyes',
								'redirect'		=>	array( 'module'=>'leyes','controller'=>'index','action'=>'index')));
	}
	public function accionPostEliminar(array $propiedades = null,array $data = null){
		//Eliminar el archivo de ley, si hubiere
		if($data['archivo_ley']!=""){
			library_Directory_Util::deleteFileSafe(self::getPathLeyes().'/'.$data['archivo_ley']);
		}
	}		
}