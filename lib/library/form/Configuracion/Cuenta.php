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
 *	Formulario de Cuenta
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Configuracion_Cuenta extends library_form_Form
      {
          public function init()
          {
          	  parent::init();

              $banco_cuenta = new Zend_Form_Element_Text('banco_cuenta');
              $banco_cuenta->setLabel('Banco:')
              		   ->setRequired(true);

              $nombre_cuenta = new Zend_Form_Element_Text('nombre_cuenta');
              $nombre_cuenta->setLabel('Nombre:')
              		   ->setRequired(true);

              $titular_cuenta = new Zend_Form_Element_Text('titular_cuenta');
              $titular_cuenta->setLabel('Titular:')
              		   ->setRequired(true);

              $numero_cuenta = new Zend_Form_Element_Text('numero_cuenta');
              $numero_cuenta->setLabel('Numero:')
              			->setAttrib('maxLength',20)
              		   	->setRequired(true);

              $tipo_cuenta = new Zend_Form_Element_Text('tipo_cuenta');
              $tipo_cuenta->setLabel('Tipo:')
              		   ->setRequired(true);

              $identificacion_cuenta = new Zend_Form_Element_Text('identificacion_cuenta');
              $identificacion_cuenta->setLabel('C.I. o R.I.F:')
              		   ->setRequired(true);

              $correo_cuenta = new Zend_Form_Element_Text('correo_cuenta');
              $correo_cuenta->setLabel('Correo Electronico:')
							->addValidator('EmailAddress');

              $comentario_cuenta = new Zend_Form_Element_Textarea('comentario_cuenta');
              $comentario_cuenta->setLabel('Comentario:')
					          ->setAttrib('COLS', '40')
							  ->setAttrib('ROWS', '7');


              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $banco_cuenta,
                  $nombre_cuenta,
                  $titular_cuenta,
                  $numero_cuenta,
                  $tipo_cuenta,
                  $identificacion_cuenta,
                  $correo_cuenta,
                  $comentario_cuenta,
                  $submit
              ));
          }
      }