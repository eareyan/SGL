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
 *	Esta clase es un factory para encargarse de la logica de creacion de clientes.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_casos_clientes_Factory{
	/** 
	 *	Dado el id de un caso, se buscan los clientes y se devuelven en un arreglo
	 *
	 *	@uses library_models_casoscliente
	 *	@uses library_models_clientes
	 *	@param int $id id del caso
	 *	@return array
	 */
	public static function crearClientes($id){
		$cache = library_casos_Cache::getCache();
		/* Buscar a los clientes */
		if( ($casosclientes = $cache->load('casoscliente_'.$id)) === false ) {
			//echo "<pre>Busco Casos clientes del caso $id</pre>";		
			//Buscar en la tabla pivote
			$model_casosclientes = new library_models_casoscliente;
			$casosclientes = $model_casosclientes->select()->where('caso_id = '.$id)->query()->fetchAll();
			$cache->save($casosclientes, 'casoscliente_'.$id);
		}
		//buscar en la tabla de clientes
		$model_clientes = new library_models_clientes;
		$clientes = array();
		foreach($casosclientes as $i=>$casocliente){
			if( ($cliente = $cache->load('cliente_'.$casocliente['cliente_id'])) === false ) {
				//echo "<pre>Busco el cliente:".$casocliente['cliente_id']."</pre>";
				$cliente = $model_clientes->find($casocliente['cliente_id'])->toArray();
				$cache->save($cliente, 'cliente_'.$casocliente['cliente_id']);
			}
			$clientes[] = $cliente[0];
		}
		return $clientes;
	}
	
	/** 
	 *	Dado el id de un cliente, busca todos los casos y sus abogados. Evita buscar los clientes para no redundar.
	 *	Devuelvo un arreglo de objetos library_casos_caso
	 *
	 *	@uses library_casos_Factory::buscarDesdeBD()
	 *	@uses library_casos_Factory::agregarDependencias()
	 *	@uses library_models_casoscliente
	 *	@param int $id_cliente id del cliente de la base de datos
	 *	@return array los clientes del caso
	 */		
	public static function buscarCasosCliente($id_cliente){
		$model_casocliente = new library_models_casoscliente;
		$casos_cliente = $model_casocliente->select()->where('cliente_id = '.$id_cliente)->query()->fetchAll();
		$casos = array();
		foreach($casos_cliente as $index=>$data){
			$caso = library_casos_Factory::buscarDesdeBD($data['caso_id'],false);
			$casos[] = library_casos_Factory::agregarDependencias($caso,false,true);
		}
		return $casos;
	}	
}