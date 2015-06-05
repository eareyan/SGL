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
 *	Esta clase es un factory para encargarse de la logica de creacion de casos.
 *	El caso mas complicado es, dado un caso, hallar sus clientes y abogados.
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */

class library_casos_Factory{
	/** 
	 *	Busca los clientes y abogados asociados a el caso cuyo id es recibido por parametro.
	 *	Devuelve un arreglo con los clientes y abogados, ambos arreglos.
	 *
	 *	@uses library_casos_Factory::crearClientes()
	 *	@uses library_casos_Factory::crearAbogados()
	 *	@uses library_casos_Exception
	 *	@param int $id_par id del caso
	 *	@param bool $buscarClientes booleano para buscar o no a los clientes
	 *	@param bool $buscarAbogados booleano para buscar o no a los abogados
	 *	@return array
	 */	
	public static function crearDependencias($id_par,$buscarClientes = true, $buscarAbogados = true){
		//Verificar que el id sea valido
		$id = intval($id_par);
		if($id <= 0){
			/*
			* Para crear un caso desde la base de datos se debe recibir el id. El id debe ser un entero mayor que cero.
			*/
			throw new library_casos_Exception("El caso con id: $id_par no existe");
		}
		$ret = array('clientes'=>array(),'abogados'=>array());
		if($buscarClientes){
			$ret['clientes'] = library_casos_clientes_Factory::crearClientes($id);
		}
		if($buscarAbogados){
			$ret['abogados'] = library_casos_abogados_Factory::crearAbogados($id);
		}
		return $ret;		
	}
	
	/** 
	 *	Dado un objeto tipo library_casos_caso, esta funcion busca sus dependencias (clientes y abogados)
	 *	El usuario puede pasar por parametros cuales dependencias quiere buscar, abogados y clientes o solo abogados o solo clientes.
	 *
	 *	@uses library_casos_Factory::crearDependencias()
	 *	@uses library_casos_caso->setClientes()
	 *	@uses library_casos_caso->setAbogados()
	 *	@param library_casos_caso $obj_caso objeto tipo caso al que se le van a agregar las dependencias
	 *	@param bool $clientes booleano para buscar o no a los clientes
	 *	@param bool $abogados booleano para buscar o no a los abogados
	 *	@return library_casos_caso el caso con las dependencias
	 */
	public static function agregarDependencias(library_casos_caso $obj_caso, $clientes = true, $abogados = true){
		if($clientes && $abogados){
			$dependencias = self::crearDependencias($obj_caso->getPropiedad('id_caso'));
		}elseif($clientes && !$abogados){
			$dependencias = self::crearDependencias($obj_caso->getPropiedad('id_caso'),true,false);		
		}elseif(!$clientes && $abogados){
			$dependencias = self::crearDependencias($obj_caso->getPropiedad('id_caso'),false,true);				
		}else{
			throw new library_casos_Exception('Llamado a la funcion agregarDependencias sin pedirle ni clientes ni abogados');
		}
		if($clientes){
			$obj_caso->setClientes($dependencias['clientes']);
		}
		if($abogados){
			$obj_caso->setAbogados($dependencias['abogados']);
		}
		return $obj_caso;
	}
	
	/** 
	 *	Esta funcion recibe el id de un caso de la base de datos, lo busca en la base de datos y devulve
	 *	un objeto tipo library_casos_caso con el caso correspondiente. 
 	 *	Adicionalmente, Tambien se puede decidir si buscar las dependencias del caso (clientes y abogados) o no.
	 *
	 *	@uses library_casos_Factory::crearDesdeCero()
	 *	@uses library_models_casos
	 *	@uses library_casos_Exception
	 *	@param int $id es el id del caso
	 *	@param bool $buscar_dependencias booleano para buscar o no las dependencias
	 *	@return library_casos_caso el caso con las dependencias
	 */
	public static function buscarDesdeBD($id,$buscar_dependencias = true){
		//Factory. Recibe unicamente el id de la base de datos y crea el objeto
		$id = intval($id);
		if($id <= 0){
			throw new library_casos_Exception('Para crear un caso desde la base de datos se debe recibir el id. 
												El id debe ser un entero mayor que cero.');
		}
		//Buscar el caso
		$model_casos = new library_models_casos;
		$caso = $model_casos->find($id)->toArray();
		if(!isset($caso[0])){
			throw new library_casos_Exception("El caso con id: $id no existe");		
		}
		$propiedades_caso = $caso[0];
		return self::crearDesdeCero($propiedades_caso,$buscar_dependencias);
	}
	
	/** 
	 *	Esta funcion recibe un arreglo con las propiedades del caso y devulve un objeto de tipos library_casos_caso
	 *	con esas propiedades. Adicionalmente, Tambien se puede decidir si buscar las dependencias del caso (clientes y abogados) o no.
	 *
	 *	@uses library_casos_Factory::crearDependencias()
	 *	@uses library_casos_Factory::crearCuenta()
	 *	@param array $propiedades_caso las propiedades del caso, indexadas por los mismos campos que en la base de datos
	 *	@param bool $buscar_dependencias booleano para buscar o no las dependencias
	 *	@return library_casos_caso el caso con las dependencias
	 */	
	public static function crearDesdeCero($propiedades_caso, $buscar_dependencias = true){
		if($buscar_dependencias){
			//Buscar dependencias, es decir, clientes y abogados asociados a este caso
			$dependencias = self::crearDependencias($propiedades_caso['id_caso']);
			$cuenta = library_casos_cuentas_Factory::crearCuenta($propiedades_caso['cuenta_id']);
			return new library_casos_caso($propiedades_caso,$dependencias['clientes'],$dependencias['abogados'],$cuenta);
		}else{
			//Crear y devolver el objeto caso
			return new library_casos_caso($propiedades_caso);
		}
	}
	
	/** 
	 *	Esta funcion devulve un arreglo de objetos tipo library_casos_caso. En el arreglo se pueden poner
	 *	los casos que cumplan con el $where recibido por parametros. Tambien se puede decidir si buscar las dependencias de 
	 *	cada caso (clientes y abogados) o no.
	 *
	 *	@uses library_casos_Factory::crearDesdeCero()
	 *	@uses library_models_casos
	 *	@param string $where clausula SQL para filtrar los casos
	 *	@param bool $buscar_dependencias booleano para buscar o no las dependencias
	 *	@return array library_casos_caso el caso con las dependencias
	 */		
	public static function buscarVariosBD($where = null,$buscar_dependencias = true){
		
		$model_casos = new library_models_casos;
		$data_casos = $model_casos->select();
		//Verificar si se van a buscar algunos casos en particular
		if(!is_null($where)){
			$data_casos = $data_casos->where($where)->order('id_caso DESC');
		}
		$data_casos = $data_casos->query()->fetchAll();
		$casos = array();
		foreach($data_casos as $id=>$caso){
		/**
		**	MOSCA! aqui podria implementarse una cache de clientes y abogados. Asi cuando se buscan varios casos,
		**	no se rebuscan clientes ni abogados....!!!
		**/
			$casos[] = self::crearDesdeCero($caso,$buscar_dependencias);
		}
		return $casos;
	}
	
	/** 
	 *	Dado un arreglo con el caso, se crea el objeto de caso. El arreglo sigue la estructura
	 *	Del formulario de agregar caso. 
	 *
	 *	@uses library_casos_Factory::crearDesdeCero()
	 *	@uses library_casos_caso->setClientes()
	 *	@uses library_casos_caso->setAbogados()
	 *	@param array $caso datos del caso
	 *	@return library_casos_caso el objeto caso
	 */	
	 public static function crearDesdeFormulario(array $caso = null){
	
		/* 
		*	Primero procesamos los clientes y abogados que vienen por el campo de Dojo
		*/
		$clientes = array();
		$j = 0;
		$k = 0;
		while(isset($caso['cliente_id_'.$j])){
			if($caso['cliente_id_'.$j] != ""){
				$clientes[$k]['id_cliente'] = $caso['cliente_id_'.$j];
				$k++;
			}
			unset($caso['cliente_id_'.$j]);
			$j++;
		}
		$abogados = array();
		$j = 0;
		$k =0;
		while(isset($caso['abogado_id_'.$j])){
			if($caso['abogado_id_'.$j] != ""){
				$abogados[$k]['id_abogado'] = $caso['abogado_id_'.$j];
				$k++;
			}
			unset($caso['abogado_id_'.$j]);
			$j++;
		}
		/* 
		*	Ahora procesamos los clientes y abogados que vienen por checkbox.
		*	Estos checkbox los genera el formulario cuando ya el caso tenia previamente clientes
		*/
		$j = 0;
		while(isset($caso['cliente_'.$j])){
			unset($caso['cliente_'.$j]);
			$j++;
		}
		$j = 0;
		while(isset($caso['abogado_'.$j])){
			unset($caso['abogado_'.$j]);
			$j++;
		}
		//Creo el objeto caso
		$caso = self::crearDesdeCero($caso,false);
		//Le agrego los clientes y abogados
		$caso->setClientes($clientes);
		$caso->setAbogados($abogados);
		//Devuelvo el objeto caso
		return $caso;
	}
}
