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
 *	Clase con metodos estaticos par guardar/editar un caso y sus dependencias en la base de datos. 
 *
 *	@category   BufeteLegal
 *	@package    library
 *	@subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_casos_casoBD{

	/** 
	 *	Recibe un arreglo con los datos del caso tal cual como llegan del formulario de editar caso
	 *	Es decir, llegan clientes y abogados tanto por checkbox como por Dojo. Esta funcion entonces
	 *	Determinar cuales clientes o abogados hay que eliminar del caso, cuales hay que mantener y 
	 *	cuales hay que agregar.
	 *
	 *	@uses library_models_casoscliente
	 *	@uses library_models_casosabogado
	 *	@uses library_casos_Exception
	 *	@param array $caso datos del caso
	 *	@param string $nombre_dependencia tiene el valor 'cliente' o 'abogado'
	 *	@return true
	 */	
	public static function editarDependencia(array $caso,$nombre_dependencia){
		//Verificar que se recibieron los parametros apropiados
		if($nombre_dependencia != 'cliente' && $nombre_dependencia !='abogado'){
			throw new library_casos_Exception('Para editar una dependencia se debe especificar si se trata de los clientes o abogados');
		}
		/* Buscar las dependencias que llegaron del usuario */
		$dependencia = array();
		$j = 0;
		while(isset($caso[$nombre_dependencia.'_'.$j])){
			if($caso[$nombre_dependencia.'_'.$j]!= "0"){
				$dependencia[$nombre_dependencia.'_id_'.$j] = $caso[$nombre_dependencia.'_'.$j];
			}
			$j++;
		}
		for($i=0;$i<3;$i++){
			if($caso[$nombre_dependencia.'_id_'.$i] != ""){
				if(!in_array($caso[$nombre_dependencia.'_id_'.$i],$dependencia)){
					$dependencia[$nombre_dependencia.'_id_'.($j+$i)] = $caso[$nombre_dependencia.'_id_'.$i];
				}
			}
		}
		//Verificar que el caso tenga al menos una dependencia
		if(count($dependencia) == 0){
			throw new library_casos_Exception('Los casos deben tener al menos un '.$nombre_dependencia);
		}
		/* Buscar las dependencias que estaban guardados en la BD */
		if($nombre_dependencia == 'cliente'){
			$model_casos_dependencia = new library_models_casoscliente;
		}else{
			$model_casos_dependencia = new library_models_casosabogado;
		}
		$dependencia_caso = $model_casos_dependencia->select()->where('caso_id = ' . $caso['id_caso'])->query()->fetchAll();
		$caso_dependencia = array();
		foreach($dependencia_caso as $i=>$registro){
			$caso_dependencia[$nombre_dependencia.'_id_'.$i] = $registro[$nombre_dependencia.'_id'];
		}	
		/* Agrega las dependencias que hagan falta al caso */
		$registros_agregar = array_diff($dependencia,$caso_dependencia);
		foreach($registros_agregar as $i=>$dato_dependencia){
			$model_casos_dependencia->insert(array($nombre_dependencia.'_id'=>$dato_dependencia,'caso_id'=>$caso['id_caso']));
		}
		/* Borrar las dependencias que ya el usuario no quiere */
		$registros_borrar = array_diff($caso_dependencia,$dependencia);
		foreach($registros_borrar as $i=>$dato_dependencia){
			$model_casos_dependencia->delete('caso_id = '.$caso['id_caso'].' AND '.$nombre_dependencia.'_id ='.$dato_dependencia);
		}
		//Borrar la cache de la dependencia con la que se este trabajando
		$cache = library_casos_Cache::getCache();
		$cache->remove('casos'.$nombre_dependencia.'_'.$caso['id_caso']);		
		return true;
	}
	/** 
	 *	Wrapper para llamar a la funcion editarDependencia de esta misma clase pero con la opcion 'cliente' fija
	 *
	 *	@uses librarey_casos_casoBD::editarDependencia
	 *	@param array $caso datos del caso
	 *	@return null
	 */	
	public static function editarClientes(array $caso){
			self::editarDependencia($caso,'cliente');
	}
	/** 
	 *	Wrapper para llamar a la funcion editarDependencia de esta misma clase pero con la opcion 'abogado' fija
	 *
	 *	@uses librarey_casos_casoBD::editarDependencia
	 *	@param array $caso datos del caso
	 *	@return null
	 */	
	public static function editarAbogados(array $caso){
			self::editarDependencia($caso,'abogado');
	}
	/** 
	 *	Edita (hace update) los datos de un caso en la base de datos. 
	 *
	 *	@uses library_models_casos->update
	 *	@param library_casos_caso $caso objeto caso
	 *	@return true
	 */		
	public static function editarCaso(library_casos_caso $caso){
		$model_caso = new library_models_casos;
		$model_caso->update($caso->getPropiedades(),'id_caso ='.$caso->getPropiedad('id_caso'));
		return true;
	}
	/** 
	 *	Guarda (hace insert) los datos de un caso en la base de datos. 
	 *
	 *	@uses library_models_casos
	 *	@uses library_models_casoscliente
	 *	@uses library_models_casosabogado
	 *	@param library_casos_caso $caso objeto caso
	 *	@return string $id_caso el id del caso que se acaba de guardar
	 */	
	public static function guardarCaso(library_casos_caso $caso){
		
		//Inicializar el modelo
		$model_casos = new library_models_casos;
		//Guardar el caso
		$id_caso = $model_casos->insert($caso->getPropiedades());				
		/* Guardar casos-clientes */
		//Inicializar modelo
		$casoscliente = new library_models_casoscliente;
		$clientes = $caso->getClientes();
		foreach($clientes as $i=>$cliente){
			//Insertar registro
			$casoscliente->insert(array('caso_id'=>$id_caso,'cliente_id'=>$cliente['id_cliente']));
		}
		/* Guardar casos-abogados */
		//Inicializar modelo
		$casosabogado = new library_models_casosabogado;
		$abogados = $caso->getAbogados();
		foreach($abogados as $i=>$abogado){
			//Insertar registro
			$casosabogado->insert(array('caso_id'=>$id_caso,'abogado_id'=>$abogado['id_abogado']));
		}
		return $id_caso;
	}
}