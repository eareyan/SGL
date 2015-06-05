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
 *	Formulario de Abogado
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
 class library_form_Abogado extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
              $nombres_abogado = new Zend_Form_Element_Text('nombres_abogado');
              $nombres_abogado->setLabel('Nombres:')
              		   ->setRequired(true);

              $apellidos_abogado = new Zend_Form_Element_Text('apellidos_abogado');
              $apellidos_abogado->setLabel('Apellidos:')
              		   ->setRequired(true);

              $especialidad_abogado = new Zend_Form_Element_Text('especialidad_abogado');
              $especialidad_abogado->setLabel('Especialidad:');

			  $fecha_nacimiento_abogado = new ZendX_JQuery_Form_Element_DatePicker("fecha_nacimiento_abogado", 
			  																		array("label" => "Fecha Nacimiento:"));
			  $fecha_nacimiento_abogado->setJQueryParam('dateFormat', 'yy-mm-dd'); 

              $direccion_habitacion_abogado = new Zend_Form_Element_Text('direccion_habitacion_abogado');
              $direccion_habitacion_abogado->setLabel('Direccion habitacion:');              

              $telefono_habitacion_abogado = new Zend_Form_Element_Text('telefono_habitacion_abogado');
              $telefono_habitacion_abogado->setLabel('Telefono habitacion:');
              
              $telefono_celular_abogado = new Zend_Form_Element_Text('telefono_celular_abogado');
              $telefono_celular_abogado->setLabel('Telefono celular:');              

              $email_principal_abogado = new Zend_Form_Element_Text('email_principal_abogado');
              $email_principal_abogado->setLabel('Email:');

              $email_alternativo_abogado = new Zend_Form_Element_Text('email_alternativo_abogado');
              $email_alternativo_abogado->setLabel('Email Alternativo:');

              $curriculo_abogado = new Zend_Form_Element_Textarea('curriculo_abogado');
              $curriculo_abogado->setLabel('Sintesis curricular:')
								->setAttrib('COLS', '40')
							    ->setAttrib('ROWS', '7');

              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombres_abogado,
                  $apellidos_abogado,
                  $especialidad_abogado,
                  $fecha_nacimiento_abogado,
                  $direccion_habitacion_abogado,
                  $telefono_habitacion_abogado,
                  $telefono_celular_abogado,
                  $email_principal_abogado,
                  $email_alternativo_abogado,
                  $curriculo_abogado,
                  $submit
              ));
          }
      }