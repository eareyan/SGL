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
class library_models_leyes extends Zend_Db_Table_Abstract{


    protected $_name = 'leyes';
    protected $_primary = 'id_ley';


}
