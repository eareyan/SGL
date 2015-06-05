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
 *	Formulario generico de Buscar
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Buscar extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
          	  $this->setAttrib('id','formbuscar');

              $text = new Zend_Form_Element_Text('Parametro');
              $text->setLabel('Buscar');
              
              $submit = new Zend_Form_Element_Submit('Eliminar');
              $submit->setLabel('Buscar')
              		 ->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));

              $this->addElements(array(
                  $text,
                  $submit
              ));
          }
      }