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
 *	Formulario de Historia Caso
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_HistoriaCasoCliente extends library_form_Form
      {
          public function init()
          {
          	parent::init();		 
          	  
              $comentario_cliente_historiacaso = new Zend_Form_Element_Textarea('comentario_cliente_historiacaso');
              $comentario_cliente_historiacaso->setLabel('Comentarios por parte del cliente:')
								->setAttrib('COLS', '40')
							    ->setAttrib('ROWS', '7')
							    ->setRequired(true);

              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $comentario_cliente_historiacaso,
                  $submit
              ));
          }
      }