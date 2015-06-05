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
      class library_form_HistoriaCaso extends library_form_Form
      {
          public function init()
          {
          	parent::init();		 
          	  
              $estatus_historiacaso = new Zend_Form_Element_Text('estatus_historiacaso');
              $estatus_historiacaso->setLabel('Estatus:')
              		   ->setRequired(true);

              $comentario_abogado_historiacaso = new Zend_Form_Element_Textarea('comentario_abogado_historiacaso');
              $comentario_abogado_historiacaso->setLabel('Comentarios por parte del abogado:')
								->setAttrib('COLS', '40')
							    ->setAttrib('ROWS', '7');
							    
		      $archivo_historiacaso = new Zend_Form_Element_File('archivo_historiacaso');
		      $archivo_historiacaso	->setLabel('.PDF anexo:')
		              		->setDestination(RAIZ.'/users/files/historiacasos/'.library_gestion_config::getIdEscritorioJuridico())
		              		->addValidator('Count', false, 1)
		              		->addValidator('Size', false, 5242880)
		              		->addValidator('Extension', false, 'pdf');							    

              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $estatus_historiacaso,
                  $comentario_abogado_historiacaso,
                  $archivo_historiacaso,
                  $submit
              ));
          }
      }