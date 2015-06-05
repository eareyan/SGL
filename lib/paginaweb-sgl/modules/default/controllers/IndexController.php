<?php

class IndexController extends modules_default_controllers_BaseController
{

	public function indexAction(){
		$this->view->page_id = 1;
	}
	
	public function nosotrosAction(){
		$this->view->page_id = 2;
	
	}
	public function sistemaAction(){
		$this->view->page_id = 3;
	
	}
	public function demoAction(){
		$this->view->page_id = 4;
		$form = new library_form_Login;
		$form->getElement('username')->setValue('admin@demo.com');
		$form->setAction('http://admin.sistemadegestionlegal.com');
		$form->setAttrib('target','_blank');
		$this->view->form = $form;
	
	}
	public function aliadosAction(){
		$this->view->page_id = 5;
	
	}
	public function contactoAction(){
		$this->view->page_id = 6;
		$form = new library_form_ContactoSGL;
		$this->view->form = $form;
		
		//Revisar si es un request tipo POST y si el formulario tiene los valores correctos para entonces guardar el registro
		if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
			/* Guardar el registro */
			//Obtener los valores del formulario
			$contacto = $form->getValues();
			$texto_email = 	"Nombres: "		.	$contacto['nombres']	.	"\n".
							"Apellidos: "	.	$contacto['apellidos']	.	"\n".
							"Escritorio: "	.	$contacto['escritorio']	.	"\n".
							"Pais: "		.	$contacto['pais']		.	"\n".
							"Telefono: "	.	$contacto['telefono']	.	"\n".
							"Email: "		.	$contacto['email']		.	"\n".
							"Referidor: "	.	$contacto['referidor']		.	"\n".
							"Comentarios: "	.	$contacto['comentarios'].	"\n";
			//echo "<pre>";print_r($texto_email);echo "</pre>";
			/*Enviar email	*/
			$mail = new Zend_Mail();
			$mail->setBodyText($texto_email);
			$mail->setFrom("contacto@sistemadegestionlegal.com", "Sistema de Gestion Legal");
			$mail->addTo("enrique3@gmail.com", "Enrique Areyan");
			$mail->addTo("basotes20@gmail.com", "Jose Alfredo De Bastos");
			$mail->addTo("contacto@sistemadegestionlegal.com", "Sistema de Gestion Legal");
			$mail->setSubject('Contacto SGL');
			$mail->send();
			//Reportar que se envio el contacto
			$this->_helper->redirector('gracias','index');
		}
	}
	public function graciasAction(){
		$this->view->page_id = 6;
	}
	public function politicaprivacidadAction(){
		//$this->view->page_id = 7;
	}
	public function faqAction(){
		$this->view->page_id = 7;
	}
}