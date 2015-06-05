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
 * @subpackage emails 
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Controlador para el manejo de los emails dentro de las configuraciones.
 *
 *	@category   BufeteLegal
 *	@package    sistadministrativo
 *	@subpackage modules 
 *  @subpackage configuracion 
 *  @subpackage emails 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class Configuracion_EmailsController extends modules_default_controllers_BaseController
{
	public function init(){
		$this->view->menu = 6;
		parent::init();
		
		/* Revisar si en el archivo config.ini el sender del email es vacio. Si es asi, poner uno por defecto. */
		if(library_gestion_config::getInstance()->emails->sender == ""){
			library_gestion_config::getInstance()->emails->sender = "remitente@correo.com";
			library_gestion_config::write();
		}
		/* Revisar si en el archivo config.ini el nombre sender del email es vacio. Si es asi, poner uno por defecto. */
		if(library_gestion_config::getInstance()->emails->sender_name == ""){
			library_gestion_config::getInstance()->emails->sender_name = "Nombre Remitente";
			library_gestion_config::write();
		}
		/* Revisar si en el archivo config.ini el receiver del email es vacio. Si es asi, poner uno por defecto. */
		if(library_gestion_config::getInstance()->emails->receiver == ""){
			library_gestion_config::getInstance()->emails->receiver = "receptor@correo.com";
			library_gestion_config::write();
		}
		/* Revisar si en el archivo config.ini el nombre del receiver del email es vacio. Si es asi, poner uno por defecto. */
		if(library_gestion_config::getInstance()->emails->receiver_name == ""){
			library_gestion_config::getInstance()->emails->receiver_name = "Nombre Receptor";
			library_gestion_config::write();
		}
		/* Revisar si en el archivo config.ini el texto del email de pago es vacio. Si es asi, poner uno por defecto. */		
		if(library_gestion_config::getInstance()->emails->pago == ""){
			library_gestion_config::getInstance()->emails->pago = 'Hola [cliente_caso],

Se le recuerda que debe cancelar su cuota numero [cuota_numero], correspondiente al caso [nombre_caso], por bsf [cuota_caso]. Por favor, deposite en el banco: [cuenta_caso]. 

Si usted ya cancel&oacute; esta cuota, por favor haga caso omiso de este mensaje.

Saludos,
Escritorio ';
			library_gestion_config::write();
		}
		/* Revisar si en el archivo config.ini el texto del email de registro es vacio. Si es asi, poner uno por defecto. */	
		if(library_gestion_config::getInstance()->emails->registro == ""){
			library_gestion_config::getInstance()->emails->registro = 'Hola [nombres_cliente] [apellidos_cliente]

Gracias por registrarte con nosotros. Para ingresar al Sistema de Gestion Legal debes visitar el siguiente url www.tamayo-tamayo.com/clientes e ingresar como su usuario el correo electronico [email_principal_cliente] y la clave que ingreso al registrarse.

Saludos,
Sistema de Gestion Legal';
			library_gestion_config::write();
		}	
		/* Revisar si en el archivo config.ini el texto del email de cambio estatus. Si es asi, poner uno por defecto. */	
		if(library_gestion_config::getInstance()->emails->cambioestatus == ""){
			library_gestion_config::getInstance()->emails->cambioestatus = 'Att. [nombres_cliente]

Le informamos que el caso identificado como [nombre_caso] ha cambiado de estatus.
Para revisar los detalles, por favor ingrese con su usuario y password al sistema de gestion legal.

Nota: no responda este mensaje, ya que el mismo fue generado automaticamente y nadie monitorea esta cuenta.';
			library_gestion_config::write();
		}						
		
		
	}
	public function remitenteAction(){
		//Inicializar el formulario
		$form = new library_form_Configuracion_Remitente;
		$this->view->form = $form;
		$form->setDefaults(array('sender'		=>	library_gestion_config::getInstance()->emails->sender,
								 'sender_name'	=>	library_gestion_config::getInstance()->emails->sender_name,
								 'receiver'		=>	library_gestion_config::getInstance()->emails->receiver,
								 'receiver_name'=>	library_gestion_config::getInstance()->emails->receiver_name));
								 
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar el registro
		if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
			/* Guardar el registro */
			//Obtener los valores del formulario
			$registro = $form->getValues();
			//Se guarda no en la base de datos sino en el archivo de configuracion xml.
			library_gestion_config::getInstance()->emails->sender 		= $registro['sender'];
			library_gestion_config::getInstance()->emails->sender_name 	= $registro['sender_name'];
			library_gestion_config::getInstance()->emails->receiver 	= $registro['receiver'];
			library_gestion_config::getInstance()->emails->receiver_name= $registro['receiver_name'];
			library_gestion_config::write();
			$this->_flashMessenger->addMessage("Los datos fueron guardado exitosamente");
			$this->_helper->redirector(	'remitente', 'emails','configuracion');
		}
	}
	public function emailAction(){
		$form = new library_form_Configuracion_TextoEmail;
		$this->view->form = $form;
		$tipo_email = (string) $this->_getParam("tipo");
		$data = array('titulos' => array(	'pago'			=>	'texto del email de pago',
											'cambioestatus'	=>	'texto del email de cambio de estatus',
											'registro'		=>	'texto del email de registro'),
					  'textos'	=> array('pago' 			=> library_gestion_config::getInstance()->emails->pago,
										 'registro' 		=> library_gestion_config::getInstance()->emails->registro,
										 'cambioestatus' 	=> library_gestion_config::getInstance()->emails->cambioestatus));
		
		$this->view->Titulo = $data['titulos'][$tipo_email];
		$form->getElement('texto_email')->setLabel($this->view->Titulo . ":")->setValue($data['textos'][$tipo_email]);
		
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar el registro
		if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
			/* Guardar el registro */
			//Obtener los valores del formulario
			$registro = $form->getValues();
			library_gestion_config::getInstance()->emails->$tipo_email 	= $registro['texto_email'];
			library_gestion_config::write();
			$this->_flashMessenger->addMessage("Los datos fueron guardado exitosamente");
			$this->_helper->redirector(	'email', 'emails','configuracion', array('tipo'=>$tipo_email));
			
		}		
	}
}