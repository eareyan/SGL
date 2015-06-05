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
 *	Formulario de Usuario
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Usuario extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
              $nombres_usuario = new Zend_Form_Element_Text('nombres_usuario');
              $nombres_usuario->setLabel('Nombres:')
              		   ->setRequired(true);
              $apellidos_usuario = new Zend_Form_Element_Text('apellidos_usuario');
              $apellidos_usuario->setLabel('Apellidos:')
              		   ->setRequired(true);
              
              $direccion_habitacion_usuario = new Zend_Form_Element_Text('direccion_habitacion_usuario');
              $direccion_habitacion_usuario->setLabel('Direccion habitacion:');
              
              $telefono_habitacion_usuario = new Zend_Form_Element_Text('telefono_habitacion_usuario');
              $telefono_habitacion_usuario->setLabel('Telefono habitacion:');
              
              $telefono_celular_usuario = new Zend_Form_Element_Text('telefono_celular_usuario');
              $telefono_celular_usuario->setLabel('Telefono celular:');

              $email_principal_usuario = new Zend_Form_Element_Text('email_principal_usuario');
              $email_principal_usuario->setLabel('Email:')
              		   ->setRequired(true);

              $email_alternativo_usuario = new Zend_Form_Element_Text('email_alternativo_usuario');
              $email_alternativo_usuario->setLabel('Email Alternativo:');
              
              $password_usuario = new Zend_Form_Element_Password('password_usuario');
              $password_usuario->setLabel('Password:')
              		   ->setRequired(true)
              		   ->addValidator('PasswordConfirmation');
              		   
              $repetir_password = new Zend_Form_Element_Password('password_confirm');
              $repetir_password->setLabel('Repetir Password:')
              		   ->setRequired(true);
              		       		   
              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombres_usuario,
                  $apellidos_usuario,
                  $direccion_habitacion_usuario,
                  $telefono_habitacion_usuario,
                  $telefono_celular_usuario,
                  $email_principal_usuario,
                  $email_alternativo_usuario,
                  $password_usuario,
                  $repetir_password,
                  $submit
              ));
          }
      }