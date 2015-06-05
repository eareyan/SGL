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
 *	Formulario de Login
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Login extends library_form_Form
      {
          public function init()
          {
	          parent::init();
              $username = new Zend_Form_Element_Text('username');
              $username->setLabel('E-mail:')
              		   ->setRequired(true);
              $password = new Zend_Form_Element_Password('password');
              $password->setLabel('Clave:')
              		   ->setRequired(true);
              $submit = new Zend_Form_Element_Submit('Entrar');
              $submit->setValue('Ingresar al Sistema')
                     ->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $username,
                  $password,
                  $submit
              ));
          }
      }