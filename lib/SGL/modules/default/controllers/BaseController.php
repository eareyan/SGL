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
 * @subpackage default 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador base. Todos los controladores extienden de el quien es el que extiende de Zend_Controller_Action
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 *  @subpackage default 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class modules_default_controllers_BaseController extends Zend_Controller_Action
{
	//Se usa para la paginacion
	protected $pagina = 0;
	
	public function init(){
		//Cargar los helpers necesarios. Esto se hace aqui ,en la base y en el de errores
		$this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;		        
        //Agregar otra ruta para las vistas.. la idea de esto es tener un solo archivo para el paginador
		$this->view->addScriptPath(RAIZ.'/library/view/helper/');
		$this->view->addHelperPath('library/view/helper/', 'library_view_helper');
		$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		$this->view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
				
		//Obtengo la sesion del usuario
		$session= Zend_Registry::get('session');
		//Verificar si el usuario esta logeado en el sistema	
		if(!$session->isLoggedIn){
			//No esta logeado, por lo tanto lo redirecciono al index
			$this->_helper->redirector('index', 'index','index');
			return;
		}
		/*Hacer la informacion del escritorio juridico accesible a los demas controladores */
		$session = Zend_Registry::get('session');
		$this->escritorio_juridico = $session->escritorio_juridico;
		//Pasarle la info del escritorio juridico a la vista
		$this->view->escritorio_juridico = $this->escritorio_juridico;
		/* Procesar los parametros de entrada comunes a todos los controladores */
		//page. Se usa para paginar.
		$pagina = $this->_getParam('page');
		if(!is_null($pagina)){
			$this->pagina = intval($pagina);
		}
		
	}

	public function setBuscador(Zend_Db_Table_Select $select,array $camposOR = null){
		$formBuscar = new library_form_Buscar;
		$this->view->formBuscar = $formBuscar;
		if(isset($_POST['Parametro'])){
			$formBuscar->setDefault('Parametro',$_POST['Parametro']);
			$condicion = "";
			foreach($camposOR as $i=>$campo){
				$condicion .= "$campo LIKE '%".$_POST['Parametro']."%' OR ";
			}
			$select->Where( rtrim($condicion, 'OR '));
			$this->view->modoBuscar = true;
		}
	}

	/*
	*	Accion generica para manejar la forma como se agregan y editan de registros.
	*/

	public function agregarAction(array $propiedades = null){
		//Inicializar el formulario
		$form = new $propiedades['form'];
		$this->view->formAgregar = $form;
		
		//Decidir si es editar
		$id = intval($this->_getParam($propiedades['campo_id']));
		if($id > 0){
			/*
			*	Modo editar. Hay que buscar el registro, poner los datos en el formulario
			*/
			$model = new $propiedades['model'];
			$datos = $model->find($id)->toArray();
			if(!isset($datos[0])){
				throw new Exception('El '.$propiedades['sujeto'].' que esta intentado editar no existe.',1234);
			}
			$form->setDefaults($datos[0]);
			$form = $this->cambiarFormEditar($form);
			$this->view->modo = 'Editar';
			$this->view->nombres = $datos[0][$propiedades['campo_nombre']];
		}else{
			$form = $this->cambiarFormAgregar($form);
		}
		
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar el registro
		if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
			/* Guardar el registro */
			//Obtener los valores del formulario
			$registro = $form->getValues();
			//Llamar al filtro
			$registro = $this->filtroPreGuardar($registro);
			//Inicializar el modelo
			$model = new $propiedades['model'];
			//Ver si es editar
			if($id > 0){
				//Si es editar, hay que actualizar el registro
				$model->update($registro,$propiedades['campo_id'].' = '.$id);
				//Ejecutar la accion post editar. Esta realiza logica particular del metodo
				$this->accionPostActualizar(array_merge($registro,array($propiedades['campo_id'] => $id)));
			}else{
				//No es editar, hay que guardar el registro nuevo		
				$id = $model->insert($registro);
				//Ejecutar la accion post guardar. Esta realiza logica particular del metodo
				$this->accionPostGuardar(array_merge($registro,array($propiedades['campo_id'] => $id)));
			}
			if(!isset($propiedades['redirect']['parameters'])){
				$propiedades['redirect']['parameters'] = array();
			}			
			//Reportar que se guardo el registro
			$mensaje_extra = "";
			if(isset($propiedades['mensaje_extra'])){
				$mensaje_extra = str_replace('?', $id , $propiedades['mensaje_extra']);
			}
			$this->_flashMessenger->addMessage($propiedades['sujeto']." ".$registro[$propiedades['campo_nombre']]." guardado exitosamente. ".$mensaje_extra);
			$this->_helper->redirector(	$propiedades['redirect']['action'], 
										$propiedades['redirect']['controller'],
										$propiedades['redirect']['module'],
										$propiedades['redirect']['parameters']);
		}
	}
	
	/*
	*	Se recibe los datos que se van a guardar de forma tal que se puedan filtrar antes de guardar.
	*/
	public function filtroPreGuardar(array $registro = null){
		return $registro;
	}
	/*
	*	Accion que se ejecuta justo despues de actualizar un registro
	*/
	public function accionPostActualizar(array $registro = null){}
	/*
	*	Accion que se ejecuta justo despues de guardar un registro
	*/
	public function accionPostGuardar(array $registro = null){}
	
	/*
	*	Se recibe el formulario de agregar datos para poder cambiar cuando se este en modo de edicion de registro.
	*/
	public function cambiarFormEditar(Zend_Form $form){
		return $form;
	}
	/*
	*	Se recibe el formulario de agregar datos para poder cambiar cuando se este en modo de agregar un registro.
	*/
	public function cambiarFormAgregar(Zend_Form $form){
		return $form;
	}

	/*
	*	Accion generica para manejar la eliminacion de registros.
	*/

	public function eliminarAction(array $propiedades = null){
		
		$id = intval($propiedades['id']);
		if($id <= 0){
			throw new Exception('Para borrar el registro se debe recibir un id mayor que cero');
		}
		//Inicializar el formulario de borrar
		$formEliminar = new library_form_Eliminar;
		$this->view->formEliminar = $formEliminar;
		
		$model = new $propiedades['model'];
		$datos_registro = $model->find($id)->toArray();
		if(!isset($datos_registro[0])){
			throw new Exception('El registro que estas intentando borrar no existe');
		}
		$this->view->nombres = $datos_registro[0][$propiedades['campo_nombre']];	
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar el registro
		if ($this->getRequest()->isPost() && $formEliminar->isValid($_POST)) {
			//Validas que efectivamente le dio a eliminar
			if(isset($_POST['Eliminar'])){
				//Esta accion implementa logica particular al momento de eliminar
				$this->accionPreEliminar($propiedades);
				//Borrar el registro
				$model->delete($propiedades['campo_id'].' = '.$id);
				//Esta accion implementa logica particular despues de eliminar
				$this->accionPostEliminar($propiedades,$datos_registro[0]);
				//Reportar que se guardo el registro
				$this->_flashMessenger->addMessage($propiedades['sujeto']." ".$datos_registro[0][$propiedades['campo_nombre']] ." eliminado exitosamente");
			}
			if(!isset($propiedades['redirect']['parameters'])){
				$propiedades['redirect']['parameters'] = array();
			}
			$this->_helper->redirector(	$propiedades['redirect']['action'], 
										$propiedades['redirect']['controller'],
										$propiedades['redirect']['module'],
										$propiedades['redirect']['parameters']);
		}
	}
	/*
	*	Funcion que se llama antes de eliminar un registro, para poder realizar acciones extras.
	*/
	public function accionPreEliminar(array $propiedades = null){}
	/*
	*	Funcion que se llama despues de eliminar un registro, para poder realizar acciones extras.
	*/
	public function accionPostEliminar(array $propiedades = null,array $data = null){}
}