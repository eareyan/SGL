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
 *	Formulario de Cliente
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Cliente extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
              $nombres_cliente = new Zend_Form_Element_Text('nombres_cliente');
              $nombres_cliente->setLabel('Nombres:')
              		   ->setRequired(true);
              $apellidos_cliente = new Zend_Form_Element_Text('apellidos_cliente');
              $apellidos_cliente->setLabel('Apellidos:')
              		   ->setRequired(true);

			  $fecha_nacimiento_cliente = new ZendX_JQuery_Form_Element_DatePicker("fecha_nacimiento_cliente", 
			  																		array("label" => "Fecha Nacimiento:"));
			  $fecha_nacimiento_cliente->setJQueryParam('dateFormat', 'yy-mm-dd'); 
              
              $direccion_habitacion_cliente = new Zend_Form_Element_Text('direccion_habitacion_cliente');
              $direccion_habitacion_cliente->setLabel('Direccion habitacion:');
              
              $telefono_habitacion_cliente = new Zend_Form_Element_Text('telefono_habitacion_cliente');
              $telefono_habitacion_cliente->setLabel('Telefono habitacion:');
              
              $telefono_oficina_cliente = new Zend_Form_Element_Text('telefono_oficina_cliente');
              $telefono_oficina_cliente->setLabel('Telefono oficina:');

              $telefono_celular_cliente = new Zend_Form_Element_Text('telefono_celular_cliente');
              $telefono_celular_cliente ->setLabel('Telefono celular:');

              $email_principal_cliente = new Zend_Form_Element_Text('email_principal_cliente');
              $email_principal_cliente->setLabel('Email:');
              		   //->addValidator('EmailAddress');

              $email_alternativo_cliente = new Zend_Form_Element_Text('email_alternativo_cliente');
              $email_alternativo_cliente->setLabel('Email Alternativo:');
              
              $password_cliente = new Zend_Form_Element_Password('password_cliente');
              $password_cliente->setLabel('Password:')
              		   ->addValidator('PasswordConfirmation');
              		   
              $repetir_password = new Zend_Form_Element_Password('password_confirm');
              $repetir_password->setLabel('Repetir Password:');
               
              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombres_cliente,
                  $apellidos_cliente,
                  $fecha_nacimiento_cliente,
                  $direccion_habitacion_cliente,
                  $telefono_habitacion_cliente,
                  $telefono_oficina_cliente,
                  $telefono_celular_cliente,
                  $email_principal_cliente,
                  $email_alternativo_cliente,
                  $password_cliente,
                  $repetir_password,
                  $submit
              ));
          }
      }