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
 * @subpackage gestion
 * @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 * @license    Todos los derechos reservados
 */
 
/**
 *	Clase para el manejo del archivo de configuracion config.ini
 *
 *	@category   BufeteLegal
 *	@package    library
 *  @subpackage gestion 
 *  @copyright  Copyright (c) 2011 Enrique Areyan (enrique3@gmail.com)
 *  @license    Todos los derechos reservados
 */
class library_gestion_config{
	/** 
	 *	Objeto tipo Zend_Config_Ini. Implementa singleton
	 *	@var Zend_Config_Ini
	 */
	private static $config = null;

	/** 
	 *	Objeto tipo Zend_Config_Writer_Ini. Implementa singleton
	 *	@var Zend_Config_Writer_Ini
	 */	
	private static $writer = null;

	/** 
	 *	Entero con el id del escritorio al que pertenece el usuario logeado
	 *	@var int
	 */		
	private static $id_escritorio = null;

	/** 
	 *	Setter id_escritorio
	 *	@var int
	 */		
	public static function setIdEscritorioJuridico($id_escritorio_juridico){
		//Setear el id del cliente
		self::$id_escritorio = $id_escritorio_juridico;
		//Si se seteo el id, hay que volver a nulo el config y writer, ya que estos implementan singleton
		self::$writer = null;
		self::$config = null;
	}
	
	/** 
	 *	Si el id escritorio es nulo, lo busca en la sesion y lo coloca para futuras referencias (singleton)
	 *	@var int
	 */		
	public static function getIdEscritorioJuridico(){
		if(is_null(self::$id_escritorio)){
			//Si no hay id_escritorio, entonces lo busco en la session
			$session = Zend_Registry::get('session');
			if(!isset($session->escritorio_juridico['id_escritorio_juridico'])){
				throw new Exception('Error buscando el id del escritorio juridico');
			}else{
				self::$id_escritorio = $session->escritorio_juridico['id_escritorio_juridico'];
			}
		}
		return self::$id_escritorio;
	}
	/** 
	 *	Accesor de $config.Implementa singleton
	 *
	 *	@return self::$config
	 */		
	public static function getInstance(){
		if(is_null(self::$config)){
			$config = new Zend_Config_Ini(	LIB . '/users/config/config_'.self::getIdEscritorioJuridico().'.ini',
											'production',
										array(	'skipExtends' => true,'allowModifications' => true));
			self::$config = $config;
		}
		return self::$config;
	}
	
	/** 
	 *	Accesor de $writer.Implementa singleton
	 *
	 *	@return self::$writer
	 */		
	public static function write(){
	
		if(is_null(self::$writer)){
			$writer = new Zend_Config_Writer_Ini(array(	'config'   => self::$config,
                                           				'filename' => RAIZ . '/users/config/config_'.self::getIdEscritorioJuridico().'.ini'));
			self::$writer = $writer;
		}
		self::$writer->write();
	}
}