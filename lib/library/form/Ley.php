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
 *	Formulario de ley
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
      class library_form_Ley extends library_form_Form
      {
          public function init()
          {
          	  parent::init();
              $nombre_ley = new Zend_Form_Element_Text('nombre_ley');
              $nombre_ley->setLabel('Nombre:')
              		   ->setRequired(true);

              $descripcion_ley = new Zend_Form_Element_Textarea('descripcion_ley');
              $descripcion_ley->setLabel('Descripcion:')
					          ->setAttrib('COLS', '40')
							  ->setAttrib('ROWS', '7');

			  $fecha_publicacion_ley = new ZendX_JQuery_Form_Element_DatePicker("fecha_publicacion_ley", 
			  																		array("label" => "Fecha Publicacion:"));

              $link_ley = new Zend_Form_Element_Text('link_ley');
              $link_ley->setLabel('URL:');
              
		      $archivo_ley = new Zend_Form_Element_File('archivo_ley');
		      $archivo_ley	->setLabel('Suba el .PDF con la ley:')
		              		->setDestination(PUBLIC_HTML.'/leyes/'.library_gestion_config::getIdEscritorioJuridico())
		              		->addValidator('Count', false, 1)
		              		->addValidator('Size', false, 5242880)
		              		->addValidator('Extension', false, 'pdf');

              $submit = new Zend_Form_Element_Submit('Registrar');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $this->addElements(array(
                  $nombre_ley,
                  $descripcion_ley,
                  $fecha_publicacion_ley,
                  $link_ley,
                  $archivo_ley,
                  $submit
              ));
          }
      }