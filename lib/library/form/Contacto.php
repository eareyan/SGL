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
      class library_form_Contacto extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
              $nombres_cliente = new Zend_Form_Element_Text('nombres');
              $nombres_cliente->setLabel('Nombres:')
              		   ->setRequired(true);
              $apellidos_cliente = new Zend_Form_Element_Text('apellidos');
              $apellidos_cliente->setLabel('Apellidos:')
              		   ->setRequired(true);

              $telefono_cliente = new Zend_Form_Element_Text('telefono');
              $telefono_cliente->setLabel('Telefono:');
              		   
              $email_cliente = new Zend_Form_Element_Text('email');
              $email_cliente->setLabel('Email:')
              		   ->setRequired(true);
              		   
              $comentarios_cliente = new Zend_Form_Element_Textarea('comentarios');
              $comentarios_cliente->setLabel('Comentarios:')
								->setAttrib('COLS', '40')
							    ->setAttrib('ROWS', '7');

			$captcha_element = new Zend_Form_Element_Captcha('foo', array(
			    'label' => "Verifica que eres humano",
			    'captcha' => array(
			        'captcha' 	=>	'Image',
			        'wordLen' 	=>	4,
			        'timeout' 	=>	300,
			        'font' 		=>	LIB.'/library/captcha/font/verdana.ttf',
			        'imgDir'	=>	PUBLIC_HTML.'/images/captcha/',
			        'imgUrl'	=>	'/images/captcha/',
			        'dotNoiseLevel'=>	50,
			        'lineNoiseLevel'=>	4
			    ),
			));

              $submit = new Zend_Form_Element_Submit('Enviar Comentario');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombres_cliente,
                  $apellidos_cliente,
                  $telefono_cliente,
                  $email_cliente,
                  $comentarios_cliente,
                  $captcha_element,
                  $submit
              ));
          }
      }