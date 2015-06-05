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
 * @subpackage view
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	View Helper.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage view 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */ 
class library_view_helper_titulos{
	/** 
	 *	Helper para imprimir un <h2> con los parametros que se reciben
	 *
	 *	@param string $sujeto
	 *	@param string $modo
	 *	@param string $dato
	 *	@return string
	 */	
	public static function titulos($sujeto, $modo , $dato = null){
		if($modo == "Editar"){
			return "<h2>Editar $sujeto <i>".$dato."</i></h2>";
		}elseif($modo == "Eliminar"){
			return "<h2>Eliminar $sujeto <i>".$dato."</i></h2>";
		}else{
			return "<h2>Agregar un nuevo $sujeto</h2>";
		}
	}
}