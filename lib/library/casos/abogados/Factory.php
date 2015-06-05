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
 * @subpackage casos
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Esta clase es un factory para encargarse de la logica de creacion de abogados.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_casos_abogados_Factory{

	/** 
	 *	Dado el id de un caso, se buscan los abogados y se devuelven en un arreglo
	 *
	 *	@uses library_models_casosabogado
	 *	@uses library_models_abogados
	 *	@return array
	 */	
	public static function crearAbogados($id){
		$cache = library_casos_Cache::getCache();	
		/* Buscar a los abogados */
		if( ($casosabogados = $cache->load('casosabogado_'.$id)) === false ) {
			//echo "<pre>Busco Casos abogados del caso $id</pre>";			
			//Buscar en la tabla pivote
			$model_casosabogados = new library_models_casosabogado;
			$casosabogados = $model_casosabogados->select()->where('caso_id = '.$id)->query()->fetchAll();
			$cache->save($casosabogados, 'casosabogado_'.$id);
		}
		//buscar en la tabla de abogados		
		$model_abogados = new library_models_abogados;
		$abogados = array();
		foreach($casosabogados as $i=>$casoabogado){
			if( ($abogado = $cache->load('abogado_'.$casoabogado['abogado_id'])) === false ) {		
				//echo "<pre>Busco el abogado:".$casoabogado['abogado_id']."</pre>";		
				$abogado = $model_abogados->find($casoabogado['abogado_id'])->toArray();
				$cache->save($abogado, 'abogado_'.$casoabogado['abogado_id']);				
			}
			$abogados[] = $abogado[0];
		}
		return $abogados;
	}
	
	/** 
	 *	Dado el id de un abogado, busca todos los casos y sus clientes. Evita buscar los abogados para no redundar.
	 *	Devuelvo un arreglo de objetos library_casos_caso
	 *
	 *	@uses library_casos_Factory::buscarDesdeBD()
	 *	@uses library_casos_Factory::agregarDependencias()
	 *	@uses library_models_casosabogado
	 *	@param int $id_cliente id del cliente de la base de datos
	 *	@return array los abogados del caso
	 */
	public static function buscarCasosAbogado($id_abogado){
		$model_casoabogado = new library_models_casosabogado;
		$casos_abogado = $model_casoabogado->select()->where('abogado_id = '.$id_abogado)->query()->fetchAll();
		$casos = array();
		foreach($casos_abogado as $index=>$data){
			$caso = library_casos_Factory::buscarDesdeBD($data['caso_id'],false);
			$casos[] = library_casos_Factory::agregarDependencias($caso,true,false);
		}
		return $casos;
	}	

}