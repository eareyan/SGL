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
 *	Formulario Base. Todosl los demas formularios extienden de el.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage form 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */      
 class library_form_Form extends Zend_Form
      {
      	var $decorators_text = array(
                          	array('ViewHelper',
                                 array('helper' => 'formText')),
                          	array('Label',
                                 array('class' => 'label')));

      	var $decorators_password = array(
                          	array('ViewHelper',
                                 array('helper' => 'formPassword')),
                          	array('Label',
                                 array('class' => 'label')));
      
      
          public function init()
          {  
			$this->addElementPrefixPath('library_validate',
            			                'library/validate',
                        			    'validate');
	        $this->addElementPrefixPath('library_decorator', 
	        							'library/decorator', 
	        							'decorator');
                        			           
          	$this->setDecorators(array(
                  'FormElements',
                  'Fieldset',
                  'Form'
              ));
      	 }
      }