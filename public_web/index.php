<?php
	//Carpeta donde vive la aplicacion
	$carpeta = '/paginaweb-sgl';
	//Definir constante RAIZ
	define('RAIZ','/home/sistem7');
	define('APPLICATION_PATH',RAIZ.$carpeta);
	/* Ampliar el include_path de PHP para incluir el directorio donde esta la aplicacion*/
	set_include_path(RAIZ.PATH_SEPARATOR.APPLICATION_PATH.PATH_SEPARATOR.get_include_path());
	/** Zend_Application */
	require_once 'Zend/Application.php';	
	// Create application, bootstrap, and run
	$application = new Zend_Application('production', APPLICATION_PATH . '/configs/application.ini');
	$application->bootstrap()->run();