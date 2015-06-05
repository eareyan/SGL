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
 * @subpackage casos 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los casos.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 * 	@subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Casos_IndexController extends modules_default_controllers_BaseController
{

	public function init(){
		$this->view->menu = 1;
		parent::init();
	}

	public function cambiarestatusAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$id_caso	=	$this->_getParam('id_caso');
		$opcion		=	$this->_getParam('opcion');
		$update = array();
		switch($opcion){
			case 'activar':
				$update['activo_caso'] = 1;
			break;
			case 'desactivar':
				$update['activo_caso'] = 0;
			break;
			case 'reiniciar':
				$update['finalizado_caso'] = 0;
			break;
			case 'finalizar':
				$update['finalizado_caso'] = 1;
			break;
			default:
				throw new Exception('opcion invalida');
		}
		//echo "<pre>";print_r($update);echo "</pre>";
		//return;
		$model_casos = new library_models_casos;
		$model_casos->update($update,'id_caso = '.$id_caso);
		$this->_flashMessenger->addMessage("Caso cambiado de estatus exitosamente");
		$this->_helper->redirector('verdetalle', 'index','casos',array('id_caso'=>$id_caso));
		
	}

	public function veranexoAction(){
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$file = RAIZ.'/users/files/historiacasos/'.library_gestion_config::getIdEscritorioJuridico().'/'. $this->_getParam('archivo');

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
	
		/*$caso = library_casos_Factory::buscarDesdeBD(1);
		echo "<pre>";print_r($caso);echo "</pre>";*/
		
		$casos = library_casos_Factory::buscarVariosBD(null,false);
		//echo "<pre>";print_r($casos);echo "</pre>";
		$adapter = new Zend_Paginator_Adapter_Array($casos);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;
		
		/*//Crear registros dummies
		$model_casos = new library_models_casos;
		for($i=0;$i<10000;$i++){
			$model_casos->insert(array('nombre_caso' => 'Caso - '.$i));
		}*/
	}
	
	public function verdetalleAction(){
		
		/* Buscar el caso. Se crea el objeto desde el factory junto con todas sus dependencias. */
		$id_caso = $this->_getParam('id_caso');
		$caso = library_casos_Factory::buscarDesdeBD($id_caso);
		//echo "<pre>"; print_r($caso); echo "</pre>";
		$this->view->caso = $caso;
		
		/* Parametros para el paginado */
		$parameters = array('id_caso' => $id_caso);
		if($this->pagina > 0){
			$parameters['page'] =  $this->pagina;
		}
		
		/* Heredar la funcion de agregar de la base */
		parent::agregarAction(array(
								'form'			=>	'library_form_HistoriaCaso',
								'sujeto'		=>	'Historia Caso',
								'id'			=>	$id_caso,
								'campo_id'		=>	'id_historiacaso',
								'campo_nombre'	=>	'estatus_historiacaso',
								'model'			=>	'library_models_historiacasos',
								'redirect'		=>	array( 	'module'		=>	'casos',
															'controller'	=>	'index',
															'action'		=>	'verdetalle',
															'parameters'	=>	$parameters
															)));
		/*
		*	Buscar la historia del caso.
		*/
		$model_historiacasos = new library_models_historiacasos;	
		$adapter = new Zend_Paginator_Adapter_DbSelect($model_historiacasos->select()->where('caso_id = '.$id_caso)->order('id_historiacaso DESC'));
		$paginator = new Zend_Paginator($adapter);
		$paginator->setCurrentPageNumber($this->pagina);
		$this->view->paginator = $paginator;		
	}
	
	public function filtroPreGuardar(array $registro = null){
		$registro['caso_id'] = $this->_getParam('id_caso');
		return $registro;
	}
	
	public function accionPostGuardar(array $registro = null){
		/* Notificar a los clientes por medio de email que cambio el estatus del caso */
		$caso = library_casos_Factory::buscarDesdeBD($registro['caso_id']);
		$reporte = $caso->enviarNotificacionCambioEstatus();
		$feedback = "Historia del caso editada exitosamente. <br/><br/>Se le envio un email de notificaci&oacute;n a:<br/><br/> <ul>";
		foreach($reporte['ok'] as $i=>$datos){
			$feedback .= "<li>". $datos['nombre'] . ' - '.$datos['email'] . "</li>";
		}
		$feedback .= "</ul>";
		$this->_flashMessenger->addMessage($feedback);
	}
		
	public function agregarAction(){
		
		//Decidir si es editar
		$id = intval($this->_getParam('id_caso'));
		if($id > 0){
			/* Es editar un caso */
			//Buscar el caso desde el Factory
			$caso = library_casos_Factory::buscarDesdeBD($id);
			//Poner las propiedades del caso por defecto en el formulario
			$formCaso = new library_form_Caso($caso->getClientes(),$caso->getAbogados());			
			$formCaso->setDefaults($caso->getPropiedades());
			//Pasar datos a la vista
			$this->view->nombre_caso = $caso->getPropiedad('nombre_caso');
			$this->view->id_caso = $id;
			//Como es editar, no se requiere que el campo de clientes y abogados de Dojo sea obligatorio
			$formCaso->getElement('cliente_id_0')->setRequired(false);
			$formCaso->getElement('abogado_id_0')->setRequired(false);
		}else{
			//Revisar si vino un cliente por defecto
			$id_cliente = intval($this->_getParam('id_cliente'));
			$clientes = array();
			if($id_cliente > 0 ){
				$model_clientes = new library_models_clientes;
				$clientes = $model_clientes->find($id_cliente)->toArray();
				if(!isset($clientes[0])){
					throw new Exception('No existe el cliente que viene por defecto');
				}
			}
			//Revisar si vino un abogado por defecto
			$id_abogado = intval($this->_getParam('id_abogado'));
			$abogados = array();			
			if($id_abogado > 0 ){
				$model_abogados = new library_models_abogados;
				$abogados = $model_abogados->find($id_abogado)->toArray();
				if(!isset($abogados[0])){
					throw new Exception('No existe el abogado que viene por defecto');
				}
			}
			/* Es agregar un nuevo caso */
			$formCaso = new library_form_Caso($clientes,$abogados);
			if(count($clientes)>0){
				$formCaso->getElement('cliente_id_0')->setRequired(false);			
			}
			if(count($abogados)>0){
				$formCaso->getElement('abogado_id_0')->setRequired(false);			
			}
		}
		$this->view->formCaso = $formCaso;
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar al cliente
		if ($this->getRequest()->isPost()) {
			if($formCaso->isValid($_POST)){			
				$caso = $formCaso->getValues();
				//Crear el objeto caso desde el Factory
				$caso_obj = library_casos_Factory::crearDesdeFormulario($caso);
				if($id > 0){
					$caso['id_caso'] = $id;
					$caso_obj->setPropiedad('id_caso',$id);
					/* Es editar un caso */
					//Editar dependencias
					library_casos_casoBD::editarClientes($caso);		
					library_casos_casoBD::editarAbogados($caso);
					//Editar el caso
					$caso_obj->editarCaso();
					//Reportar que se edito el caso
					$this->_flashMessenger->addMessage("Caso ".$caso["nombre_caso"]." editado exitosamente");
					$this->_helper->redirector('verdetalle', 'index','casos',array('id_caso'=>$id));
					
				}else{
				/* Es agregar un nuevo caso */
					//Guardar el caso
					$id_caso = $caso_obj->guardarCaso();
					$caso['id_caso'] = $id_caso;
					if($id_cliente > 0 ){
						library_casos_casoBD::editarClientes($caso);
					}
					if($id_abogado > 0 ){
						library_casos_casoBD::editarAbogados($caso);
					}					
					//Reportar que se guardo el caso
					$this->_flashMessenger->addMessage("Caso ".$caso_obj->getPropiedad("nombre_caso")." agregado exitosamente");
					$this->_helper->redirector('index', 'index','casos');
				}
			}else{
			
				/*
				*	Por algun motivo el elemento Zend_Dojo_Form_Element_FilteringSelect, no mantiene el estado,
				*	es decir, el nombre que se ve en la caja y el id oculto, al momento de que el formulario despliegue
				*	un error. Lo mas seguro es que en el futuro esto lo arreglen. Mientras tanto, aqui esta un parche
				*	para que funcione esta parte del sistema. Sin embargo, este parche funciona cuando el formulario se reenvia
				*	2 o 3 veces. De repente se pierde el estado de todas formas. Al menos este parche hace que funciona para
				*	la mayoria de los casos
				*/
				$model_abogado = new library_models_abogados;
				for($i=0;$i<3;$i++){
					$abogado_i = intval($formCaso->getElement('abogado_id_'.$i)->getValue());
					if($abogado_i > 0){
						$abogado = $model_abogado->find($abogado_i)->toArray();
						$formCaso->getElement('abogado_id_'.$i)	->setAttrib('displayedValue',$abogado[0]['nombres_abogado'])
																->setAttrib('value',$abogado[0]['id_abogado']);
					}
				}
				$model_cliente = new library_models_clientes;
				for($i=0;$i<3;$i++){
					$cliente_i = intval($formCaso->getElement('cliente_id_'.$i)->getValue());
					if($cliente_i > 0){
						$cliente = $model_cliente->find($cliente_i)->toArray();
						$formCaso->getElement('cliente_id_'.$i)	->setAttrib('displayedValue',$cliente[0]['nombres_cliente'])
																->setAttrib('value',$cliente[0]['id_cliente']);
					}
				}
			}		
		}
	}
	
	public function autocompleteAction() {
		/*
		*	Accion que se llama por Dojo para proveer la informacion que se muestra en el
		*	autocompletado de clientes y abogados al momento de crear un caso
		*/
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        //Determinar si es abogado o cliente
        $tipo = $this->_getParam("tipo");
        if($tipo == "cliente"){
	        $model= new library_models_clientes;
	        $parametros = array('campo_nombre'=>'nombres_cliente','campo_apellido'=>'apellidos_cliente','campo_id'=>'id_cliente');
        }elseif($tipo == "abogado"){
	        $model= new library_models_abogados;
	        $parametros = array('campo_nombre'=>'nombres_abogado','campo_apellido'=>'apellidos_abogado','campo_id'=>'id_abogado');
        }else{
        	throw new Exception('Tipo invalido');
        }
        
        $nombre = $this->_getParam($parametros["campo_nombre"], "");
        $nombre = substr($nombre, 0, -1); //fix : remove * at the end of the nombres_cliente.
 
        $select = $model->select(true, array($parametros["campo_nombre"]))->limit(20);
        $select->where($parametros["campo_nombre"].' LIKE ?', "%".$nombre."%");
        $results = $model->fetchAll($select)->toArray();
        $data = array();
        foreach($results as $id=>$result){
        	//Solo quiero el nombre del cliente
        	$data[] = array($parametros["campo_id"]		=>	$result[$parametros["campo_id"]],
        					$parametros["campo_nombre"]	=>	$result[$parametros["campo_nombre"]] .' '. $result[$parametros["campo_apellido"]]);
        }
        $data = new Zend_Dojo_Data($parametros["campo_id"], $data);
 
        // Send our output
        $this->_helper->autoCompleteDojo($data);

	}
	
	public function eliminarAction(){
		parent::eliminarAction(array(
								'id'			=>	$this->_getParam('id_caso'),
								'sujeto'		=>	'Caso',
								'campo_id'		=>	'id_caso',
								'campo_nombre'	=>	'nombre_caso',
								'model'			=>	'library_models_casos',
								'redirect'		=>	array( 'module'=>'casos','controller'=>'index','action'=>'index')));
	}	
	public function accionPreEliminar(array $propiedades = null){
		//Obtener el objeto cache
		$cache = library_casos_Cache::getCache();
		//Borrar abogados del caso
		$model_casosabogado = new library_models_casosabogado;
		$model_casosabogado->delete('caso_id = '.$propiedades['id']);
		//Borar cache casosabogado
		$cache->remove('casosabogado_'.$propiedades['id']);
		//Borrar clientes del caso
		$model_casoscliente = new library_models_casoscliente;
		$model_casoscliente->delete('caso_id = '.$propiedades['id']);			
		//Borar cache casosabogado
		$cache->remove('casoscliente_'.$propiedades['id']);
		//Borrar historia del caso
		$model_historiacasos = new library_models_historiacasos;
		$model_historiacasos->delete('caso_id = '.$propiedades['id']);			
	}	
}