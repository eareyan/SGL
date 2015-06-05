<?php	
	/* OJO: hay que cambiar las siguientes variables segun el ambiente en el que se ejecute la app*/
	define('BASE', '/Users/enriqueareyan/SGL/sistemadegestionlegal');
	define('RAIZ', BASE.'/lib');
	define('LIB', RAIZ);
	define('APPLICATION_PATH', RAIZ.'/SGL');
	define('PUBLIC_HTML', BASE.'/public_html_SGL');
	/* Ampliar el include_path de PHP para incluir el directorio donde esta la aplicacion*/
	set_include_path(RAIZ.PATH_SEPARATOR.APPLICATION_PATH.PATH_SEPARATOR.get_include_path());
	/** Zend_Application */
	require_once 'Zend/Application.php';	
	// Create application, bootstrap, and run
	$application = new Zend_Application('production', APPLICATION_PATH . '/configs/application.ini');
	$application->bootstrap()->run();	