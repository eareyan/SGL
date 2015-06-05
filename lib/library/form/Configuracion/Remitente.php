<?php
/**
 * Sistema para Bufete Legal
 * 
 * LICENCIA
 * 
 * Todos los derechos reservados.
 *
 * @category   BufeteLegal
 * @package    library
 * @subpackage form
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Formulario de ley
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Configuracion_Remitente extends library_form_Form
      {
          public function init()
          {
          	  parent::init();

              $sender = new Zend_Form_Element_Text('sender');
              $sender->setLabel('Remitente de los Correo automaticos:');
					 //->addValidator('EmailAddress');

              $sender_name = new Zend_Form_Element_Text('sender_name');
              $sender_name->setLabel('Nombre del remitente del Correo automatico:');

              $receiver = new Zend_Form_Element_Text('receiver');
              $receiver->setLabel('Destinatario del Correo automatico de reportes:');
                	   //->addValidator('EmailAddress');
              
              $receiver_name = new Zend_Form_Element_Text('receiver_name');
              $receiver_name->setLabel('Nombre del destinatario del Correo automatico de reportes:');
              
              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
	              $sender,
	              $sender_name,
	              $receiver,
	              $receiver_name,
                  $submit
              ));
          }
      }