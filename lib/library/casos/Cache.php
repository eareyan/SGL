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
 *	Inicializa la cache
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage casos 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_casos_Cache{
	/** 
	 *	Cache de Zend. Se accede a traves de un singleton por la funcion getCache.
	 *	@var Zend_Cache
	 */
	private static $cache = null;

	/** 
	 *	Resetea la cache. Esto por si acaso en una misma corrida busco la cache de varios escritorios juridicos
	 *	por ejemplo al momento de enviar reportes automaticos de pago
	 *
	 *	@return null
	 */
	 public static function resetCache(){
	 	self::$cache = null;
	 }
	/** 
	 *	Crea la cache que se utiliza en los metodos crearClientes y crearAbogados para evitar pedirle
	 *	a la base de datos muchas veces el mismo cliente o abogado. Tambien guarda los registros pivotes de
	 *	clientes y abogados. Esta funcion implementa un singleton sobre $cache.
	 *
	 *	@uses Zend_Cache::factory
	 *	@return Zend_Cache
	 */
	public static function getCache(){
		//Verifico si ya se creo la cache...
		if(self::$cache == null){
			//no se ha creado la cache, vamos a crearla
			$frontendOptions = array(
			 'lifetime' => 604800, // vida del cache de 1 semana
			 'automatic_serialization' => true
			);
			$backendOptions = array(
			  'cache_dir' => LIB.'/cache/'.library_gestion_config::getIdEscritorioJuridico() // Directorio donde estaran los archivos del cache
			);
			// obtened un objeto Zend_Cache_Core
			$cache = Zend_Cache::factory('Core',
			                           'File',
			                           $frontendOptions,
			                           $backendOptions);
			//Colocar el cache recien creado en el singleton
			self::$cache = $cache;
		}else{
			//Obtener el cache del singleton
			$cache = self::$cache;
		}
		//Devolver el cache
		return $cache;
	}	
}