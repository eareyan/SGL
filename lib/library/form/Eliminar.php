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
 *	Formulario generico de Eliminar
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Eliminar extends library_form_Form
      {
          public function init()
          {
			parent::init();

			$this->setName('formEliminar');
			
			$submit = new Zend_Form_Element_Submit('Eliminar');
			$submit->setLabel('Si, Eliminar')
					 ->setDecorators(array(
			         array('ViewHelper',
			         array('helper' => 'formSubmit'))
			     ));
			
			$reset = new Zend_Form_Element_Reset('Cancelar');
			$reset->setLabel('No, Cancelar')
				->setDecorators(array(
			         array('ViewHelper',
			         array('helper' => 'formSubmit'))
			     ));
			$this->addElements(array(
			  $submit,
			  $reset
			));
          }
      }