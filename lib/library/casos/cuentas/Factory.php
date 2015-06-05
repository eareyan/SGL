<?php

class library_casos_cuentas_Factory{
	/** 
	 *	Dado el id de una cuenta la busca y la devuelve en un arreglo.
	 *
	 *	@uses library_models_cuentas
	 *	@param int $id_cuenta	id del registro en la tabla cuenta
	 *	@return array
	 */		
	public static function crearCuenta($id_cuenta){
		$cache = library_casos_Cache::getCache();
		/* Buscar a los clientes */
		if( ($cuenta = $cache->load('cuenta_'.$id_cuenta)) === false ) {
			//echo "<pre style=color:green>Busco la cuenta en la BD</pre>";
			/* Buscar la cuenta */
			//Buscar en la tabla de cuentas
			$model_cuentas = new library_models_cuentas;
			$cuenta_data = $model_cuentas->find($id_cuenta)->toArray();
			if(!isset($cuenta_data[0])){
				$cuenta = array();
			}else{
				$cuenta = $cuenta_data[0];
			}
			$cache->save($cuenta, 'cuenta_'.$id_cuenta);			
		}
		return $cuenta;
	}
	
	/** 
	 *	Esta funcion recibe un objeto tipo caso y le anade su cuenta bancaria
	 *
	 *	@uses library_casos_Factory::crearCuenta()
	 *	@uses library_casos_caso->setCuenta()
	 *	@param library_casos_caso $obj_caso el objeto de caso
	 *	@return null
	 */	
	public static function agregarCuenta(library_casos_caso $obj_caso){
		$obj_caso->setCuenta(self::crearCuenta($obj_caso->getPropiedad('cuenta_id')));
	}
}