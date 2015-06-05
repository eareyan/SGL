<?php

date_default_timezone_set('America/Caracas');

//Definir la raiz de la aplicacion
define('RAIZ','/Users/enriqueareyan/SGL/sistemadegestionlegal/lib');
//Definir la carpeta LIB
define('LIB',RAIZ);
//Definir la carpeta publica
define('PUBLIC_HTML' , '/Users/enriqueareyan/SGL/sistemadegestionlegal/public_html_SGL');
//Ampliar el include path para leer las librerias
set_include_path(RAIZ. PATH_SEPARATOR .get_include_path());
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', RAIZ . '/SGL');
//incluir el autoloader de zend
require_once 'Zend/Loader/Autoloader.php';
/*Se registra el namespace modules para poder tener modulos*/
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('library_');		
//Concetar con la base de datos
$db = Zend_Db::factory('pdo_mysql', array(	'host'		=>	'127.0.0.1',
											'port'	 	=>	'8889',
											'password' 	=>	'root',
											'username'	=>	'root', 
											'dbname'	=> 	'SGL'));
Zend_Db_Table::setDefaultAdapter($db);
