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
 * @subpackage models
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Modelo de Leyes.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage models 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
class library_models_emails extends Zend_Db_Table_Abstract{


    protected $_name = 'emails';
    protected $_primary = 'id_email';


}
