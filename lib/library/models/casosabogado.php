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
 *	Modelo de Casos-Abogado.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage models 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
class library_models_casosabogado extends Zend_Db_Table_Abstract{


    protected $_name = 'casos-abogado';
    protected $_primary = 'id_caso-abogado';


}
