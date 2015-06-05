<?php
//Incluir el archivo base.php, donde esta la logica de conexion con la base de datos
require 'base.php';
//Buscar los datos de la BD demo para conectarse
$demo = new library_models_escritoriosjuridicos;
//Buscar el usuario 1 que es el demo
$datos_demo = $demo->find(1)->toArray();
if(is_null($datos_demo[0])){
	//No existe el cliente 1, arrojar una excepcion
	throw new Exception('No existe el cliente DEMO');
}
//Conectarse BD demo
$db = Zend_Db::factory('pdo_mysql', array(	'host'		=>	'127.0.0.1',
											'port'		=>	'8889',
											'password' 	=>	$datos_demo[0]['password_db_escritorio_juridico'],
											'username'	=>	$datos_demo[0]['username_db_escritorio_juridico'], 
											'dbname'	=> 	$datos_demo[0]['dbname_db_escritorio_juridico']));
//Poner la base de datos de demo por defecto											
Zend_Db_Table::setDefaultAdapter($db);

/****** Resetear lo siguiente ******/
//---Tablas MySql
$db->query("
TRUNCATE TABLE `abogados`;
INSERT INTO `abogados` (`id_abogado`, `nombres_abogado`, `apellidos_abogado`, `fecha_nacimiento_abogado`, `direccion_habitacion_abogado`, `telefono_habitacion_abogado`, `telefono_celular_abogado`, `email_principal_abogado`, `email_alternativo_abogado`, `curriculo_abogado`) VALUES
(1, 'Javier', 'Salazar', '1967-01-18', '3948 West 4rd Avenue Hialeah, FL 33010-4839,', '(305) 493-2937', '(305) 493-0098', 'javier@escritoriojuridico.com', 'javier@salazar.com', 'FORMACION Y ESTUDIOS\r\n \r\nFecha: \r\nInstitución formadora: \r\nTitulación: \r\n\r\nFecha: \r\nInstitución formadora: \r\nTitulación: \r\n\r\n\r\nEXPERIENCIA PROFESIONAL\r\n \r\nFecha: \r\nEmpresa: \r\nPuesto/Actividad desarrollada: \r\n\r\nFecha: \r\nEmpresa: \r\nPuesto/Actividad desarrollada: \r\n\r\n\r\nDATOS COMPLEMENTARIOS\r\n \r\nIdiomas: \r\nConocimientos informáticos: \r\nCarnet de conducir, vehículo propio, disponibilidad geográfica...\r\n'),
(2, 'Sofía', 'Muñoz', '0000-00-00', 'OE2-06 y Av. 10 de Agosto Quito, Ecuador', '(593) 02 5905513', '(593) 99 1233084', 'sofia_m@escritoriojuridico.com', 'sofiam@munoz.com', 'Formación académica\r\n\r\n1998-2000	Titulación\r\nInstitución formadora y lugar\r\nBreve descripción de la formación\r\n\r\n1992-1998	Titulación\r\nInstitución formadora y lugar\r\nBreve descripción de la formación\r\n\r\n\r\nIdiomas\r\n\r\nIdioma 1:	Nivel. Título\r\nIdioma 2:	Nivel. Título\r\n\r\nInformática\r\n\r\nPrograma 1:	Nivel\r\nPrograma 2:	Nivel\r\n\r\nOtros datos de interés\r\n\r\nEstudios y seminarios\r\nCarnet de conducir\r\nDisponibilidad para viajar\r\nAficiones\r\n');

TRUNCATE TABLE `casos`;
INSERT INTO `casos` (`id_caso`, `finalizado_caso`, `nombre_caso`, `descripcion_caso`, `activo_caso`, `fecha_inicio_caso`, `fecha_fin_caso`, `cuota_caso`, `cuenta_id`, `publico_caso`) VALUES
(1, 1, 'Demanda contra Empresas ACME C.A.', 'Demanda contra las empresas ACME C.A. por las siguientes razones:\r\n\r\nRazón 1, Razón 2, ...', 0, '2011-06-27', '2011-06-30', 0, '', 1),
(2, 0, 'Caso María Gómez contra Pedro Pérez', 'Demanda contra el ciudadano Pedro Pérez por parte de María Gómez', 1, '2011-07-06', '2011-07-25', 0, '', 1);

TRUNCATE TABLE `casos-abogado`;
INSERT INTO `casos-abogado` (`id_caso-abogado`, `caso_id`, `abogado_id`) VALUES
(1, 1, 1),
(3, 2, 2);

TRUNCATE TABLE `casos-cliente`;
INSERT INTO `casos-cliente` (`id_caso-cliente`, `caso_id`, `cliente_id`) VALUES
(1, 1, 1),
(2, 2, 2);

TRUNCATE TABLE `clientes`;
INSERT INTO `clientes` (`id_cliente`, `nombres_cliente`, `apellidos_cliente`, `fecha_nacimiento_cliente`, `direccion_habitacion_cliente`, `telefono_habitacion_cliente`, `telefono_oficina_cliente`, `telefono_celular_cliente`, `email_principal_cliente`, `email_alternativo_cliente`, `password_cliente`) VALUES
(1, 'Juan', 'Perez', '1980-03-08', 'Av. Antártida Argentina 1355 - C1104ACA Buenos Aires', '(0)11 2344 1233', '', '(0)11 1123 2344', 'juan_p@prueba.com', 'juanpe@perez.com', '202cb962ac59075b964b07152d234b70'),
(2, 'María', ' Gómez', '1970-06-01', 'Calle Quintiliano, 21 28002 - Madrid (España)', '34 91 343 55 56', '34 91 556 33 46', '34 91 776 55 89', 'maria@prueba.com', 'maria@gomez.com', '202cb962ac59075b964b07152d234b70'),
(3, 'Miguel', 'Paz', '0000-00-00', 'Colonia Bosques de Chapultepec 11580 México D.F.. México.', '+55123444355', '+55154555421', '+55150384756', 'miguel_p@proveedorcorreo.com', 'miguel@paz.com', '202cb962ac59075b964b07152d234b70'),
(4, 'Bruno', 'Díaz', '1958-12-21', 'Av. Venezuela, Edf. Valfer, PH. Urb. El Rosal, Caracas', '+00139495889', '+00757884944', '+00166785544', 'bruno@diaz.com', 'bruno_diaz@legal.com', '202cb962ac59075b964b07152d234b70');

TRUNCATE TABLE `historiacasos`;
INSERT INTO `historiacasos` (`id_historiacaso`, `caso_id`, `estatus_historiacaso`, `comentario_abogado_historiacaso`, `comentario_cliente_historiacaso`, `archivo_historiacaso`, `fecha_historiacaso`) VALUES
(1, 2, 'Ejemplo de Estatus', 'Paso algo con el caso...', NULL, 'Parallels_Power_Panel_Users_Guide.pdf', '2011-06-30 15:44:42');

TRUNCATE TABLE `leyes`;
INSERT INTO `leyes` (`id_ley`, `nombre_ley`, `descripcion_ley`, `fecha_publicacion_ley`, `archivo_ley`, `link_ley`) VALUES
(1, 'Constitución de la República Bolivariana de Venezuela', 'Esta es la Constitución de la República Bolivariana de Venezuela, la Carta Magna vigente en Venezuela, adoptada el 15 de diciembre de 1999.', '1999-11-17', 'ConstitucionRBV1999.pdf', 'http://www.tsj.gov.ve/legislacion/constitucion1999.htm');

");
//---Archivo config_<id_cliente>.ini
unlink(LIB . '/users/config/config_1.ini');
copy(LIB . '/users/files/demo/config/config_1.ini' , LIB . '/users/config/config_1.ini');

//---Carpeta privada historiacasos/<id_cliente>/
library_Directory_Util::recursive_remove_directory(LIB . '/users/files/historiacasos/1',true);
//Agregar archivos de prueba 
library_Directory_Util::rcopy(LIB . '/users/files/demo/historiacasos' , LIB . '/users/files/historiacasos/1' );

//---Carpeta publica	leyes/<id_cliente>/
library_Directory_Util::recursive_remove_directory(PUBLIC_HTML . '/leyes/1',true);
//Agregar archivos de prueba 
library_Directory_Util::rcopy(LIB . '/users/files/demo/leyes' , PUBLIC_HTML . '/leyes/1' );

//---Borrar contenido Carpeta Cache
library_Directory_Util::recursive_remove_directory(LIB . '/cache/1',true);