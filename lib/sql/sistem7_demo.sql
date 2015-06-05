-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 07, 2011 at 10:03 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sistem7_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `abogados`
--

CREATE TABLE IF NOT EXISTS `abogados` (
  `id_abogado` int(11) NOT NULL auto_increment,
  `nombres_abogado` varchar(120) NOT NULL,
  `apellidos_abogado` varchar(120) NOT NULL,
  `fecha_nacimiento_abogado` date default NULL,
  `direccion_habitacion_abogado` varchar(120) default NULL,
  `telefono_habitacion_abogado` varchar(16) default NULL,
  `telefono_celular_abogado` varchar(16) default NULL,
  `email_principal_abogado` varchar(120) NOT NULL,
  `email_alternativo_abogado` varchar(120) default NULL,
  `curriculo_abogado` text,
  PRIMARY KEY  (`id_abogado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `abogados`
--

INSERT INTO `abogados` (`id_abogado`, `nombres_abogado`, `apellidos_abogado`, `fecha_nacimiento_abogado`, `direccion_habitacion_abogado`, `telefono_habitacion_abogado`, `telefono_celular_abogado`, `email_principal_abogado`, `email_alternativo_abogado`, `curriculo_abogado`) VALUES
(1, 'Javier', 'Salazar', '1967-01-18', '3948 West 4rd Avenue Hialeah, FL 33010-4839,', '(305) 493-2937', '(305) 493-0098', 'javier@escritoriojuridico.com', 'javier@salazar.com', 'FORMACION Y ESTUDIOS\r\n \r\nFecha: \r\nInstituciÃ³n formadora: \r\nTitulaciÃ³n: \r\n\r\nFecha: \r\nInstituciÃ³n formadora: \r\nTitulaciÃ³n: \r\n\r\n\r\nEXPERIENCIA PROFESIONAL\r\n \r\nFecha: \r\nEmpresa: \r\nPuesto/Actividad desarrollada: \r\n\r\nFecha: \r\nEmpresa: \r\nPuesto/Actividad desarrollada: \r\n\r\n\r\nDATOS COMPLEMENTARIOS\r\n \r\nIdiomas: \r\nConocimientos informÃ¡ticos: \r\nCarnet de conducir, vehÃ­culo propio, disponibilidad geogrÃ¡fica...\r\n'),
(2, 'SofÃ­a', 'MuÃ±oz', '0000-00-00', 'OE2-06 y Av. 10 de Agosto Quito, Ecuador', '(593) 02 5905513', '(593) 99 1233084', 'sofia_m@escritoriojuridico.com', 'sofiam@munoz.com', 'FormaciÃ³n acadÃ©mica\r\n\r\n1998-2000	TitulaciÃ³n\r\nInstituciÃ³n formadora y lugar\r\nBreve descripciÃ³n de la formaciÃ³n\r\n\r\n1992-1998	TitulaciÃ³n\r\nInstituciÃ³n formadora y lugar\r\nBreve descripciÃ³n de la formaciÃ³n\r\n\r\n\r\nIdiomas\r\n\r\nIdioma 1:	Nivel. TÃ­tulo\r\nIdioma 2:	Nivel. TÃ­tulo\r\n\r\nInformÃ¡tica\r\n\r\nPrograma 1:	Nivel\r\nPrograma 2:	Nivel\r\n\r\nOtros datos de interÃ©s\r\n\r\nEstudios y seminarios\r\nCarnet de conducir\r\nDisponibilidad para viajar\r\nAficiones\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `casos`
--

CREATE TABLE IF NOT EXISTS `casos` (
  `id_caso` int(11) NOT NULL auto_increment,
  `finalizado_caso` int(1) NOT NULL default '0',
  `nombre_caso` varchar(120) NOT NULL,
  `descripcion_caso` text,
  `activo_caso` int(1) NOT NULL default '1',
  `fecha_inicio_caso` date default NULL,
  `fecha_fin_caso` date default NULL,
  `cuota_caso` float default NULL,
  `cuenta_id` varchar(120) default NULL,
  `publico_caso` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_caso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `casos`
--

INSERT INTO `casos` (`id_caso`, `finalizado_caso`, `nombre_caso`, `descripcion_caso`, `activo_caso`, `fecha_inicio_caso`, `fecha_fin_caso`, `cuota_caso`, `cuenta_id`, `publico_caso`) VALUES
(1, 1, 'Demanda contra Empresas ACME C.A.', 'Demanda contra las empresas ACME C.A. por las siguientes razones:\r\n\r\nRazÃ³n 1, RazÃ³n 2, ...', 0, '2011-06-27', '2011-06-30', 0, '', 1),
(2, 0, 'Caso MarÃ­a GÃ³mez contra Pedro PÃ©rez', 'Demanda contra el ciudadano Pedro PÃ©rez por parte de MarÃ­a GÃ³mez', 1, '2011-07-06', '2011-07-25', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `casos-abogado`
--

CREATE TABLE IF NOT EXISTS `casos-abogado` (
  `id_caso-abogado` int(11) NOT NULL auto_increment,
  `caso_id` int(11) NOT NULL,
  `abogado_id` int(11) NOT NULL,
  PRIMARY KEY  (`id_caso-abogado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `casos-abogado`
--

INSERT INTO `casos-abogado` (`id_caso-abogado`, `caso_id`, `abogado_id`) VALUES
(1, 1, 1),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `casos-cliente`
--

CREATE TABLE IF NOT EXISTS `casos-cliente` (
  `id_caso-cliente` int(11) NOT NULL auto_increment,
  `caso_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY  (`id_caso-cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `casos-cliente`
--

INSERT INTO `casos-cliente` (`id_caso-cliente`, `caso_id`, `cliente_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL auto_increment,
  `nombres_cliente` varchar(120) NOT NULL,
  `apellidos_cliente` varchar(120) NOT NULL,
  `fecha_nacimiento_cliente` date default NULL,
  `direccion_habitacion_cliente` varchar(120) default NULL,
  `telefono_habitacion_cliente` varchar(16) default NULL,
  `telefono_oficina_cliente` varchar(16) default NULL,
  `telefono_celular_cliente` varchar(16) default NULL,
  `email_principal_cliente` varchar(120) NOT NULL,
  `email_alternativo_cliente` varchar(120) default NULL,
  `password_cliente` varchar(120) NOT NULL,
  PRIMARY KEY  (`id_cliente`),
  UNIQUE KEY `login` (`email_principal_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombres_cliente`, `apellidos_cliente`, `fecha_nacimiento_cliente`, `direccion_habitacion_cliente`, `telefono_habitacion_cliente`, `telefono_oficina_cliente`, `telefono_celular_cliente`, `email_principal_cliente`, `email_alternativo_cliente`, `password_cliente`) VALUES
(1, 'Juan', 'Perez', '1980-03-08', 'Av. AntÃ¡rtida Argentina 1355 - C1104ACA Buenos Aires', '(0)11 2344 1233', '', '(0)11 1123 2344', 'juan_p@prueba.com', 'juanpe@perez.com', '202cb962ac59075b964b07152d234b70'),
(2, 'MarÃ­a', ' GÃ³mez', '1970-06-01', 'Calle Quintiliano, 21 28002 - Madrid (EspaÃ±a)', '34 91 343 55 56', '34 91 556 33 46', '34 91 776 55 89', 'maria@prueba.com', 'maria@gomez.com', '202cb962ac59075b964b07152d234b70'),
(3, 'Miguel', 'Paz', '0000-00-00', 'Colonia Bosques de Chapultepec 11580 MÃ©xico D.F.. MÃ©xico.', '+55123444355', '+55154555421', '+55150384756', 'miguel_p@proveedorcorreo.com', 'miguel@paz.com', '202cb962ac59075b964b07152d234b70'),
(4, 'Bruno', 'DÃ­az', '1958-12-21', 'Av. Venezuela, Edf. Valfer, PH. Urb. El Rosal, Caracas', '+00139495889', '+00757884944', '+00166785544', 'bruno@diaz.com', 'bruno_diaz@legal.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `cuentas`
--

CREATE TABLE IF NOT EXISTS `cuentas` (
  `id_cuenta` int(11) NOT NULL auto_increment,
  `nombre_cuenta` varchar(120) NOT NULL,
  `banco_cuenta` varchar(120) NOT NULL,
  `titular_cuenta` varchar(120) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `tipo_cuenta` varchar(120) NOT NULL,
  `identificacion_cuenta` varchar(120) NOT NULL,
  `correo_cuenta` varchar(120) NOT NULL,
  `comentario_cuenta` text,
  PRIMARY KEY  (`id_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cuentas`
--


-- --------------------------------------------------------

--
-- Table structure for table `historiacasos`
--

CREATE TABLE IF NOT EXISTS `historiacasos` (
  `id_historiacaso` int(11) NOT NULL auto_increment,
  `caso_id` int(11) NOT NULL,
  `estatus_historiacaso` varchar(120) NOT NULL,
  `comentario_abogado_historiacaso` text,
  `comentario_cliente_historiacaso` text,
  `archivo_historiacaso` varchar(120) default NULL,
  `fecha_historiacaso` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_historiacaso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `historiacasos`
--

INSERT INTO `historiacasos` (`id_historiacaso`, `caso_id`, `estatus_historiacaso`, `comentario_abogado_historiacaso`, `comentario_cliente_historiacaso`, `archivo_historiacaso`, `fecha_historiacaso`) VALUES
(1, 2, 'Ejemplo de Estatus', 'Paso algo con el caso...', NULL, 'Parallels_Power_Panel_Users_Guide.pdf', '2011-06-30 15:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `leyes`
--

CREATE TABLE IF NOT EXISTS `leyes` (
  `id_ley` int(11) NOT NULL auto_increment,
  `nombre_ley` varchar(120) NOT NULL,
  `descripcion_ley` text,
  `fecha_publicacion_ley` date NOT NULL,
  `archivo_ley` varchar(120) default NULL,
  `link_ley` varchar(240) default NULL,
  PRIMARY KEY  (`id_ley`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `leyes`
--

INSERT INTO `leyes` (`id_ley`, `nombre_ley`, `descripcion_ley`, `fecha_publicacion_ley`, `archivo_ley`, `link_ley`) VALUES
(1, 'ConstituciÃ³n de la RepÃºblica Bolivariana de Venezuela', 'Esta es la ConstituciÃ³n de la RepÃºblica Bolivariana de Venezuela, la Carta Magna vigente en Venezuela, adoptada el 15 de diciembre de 1999.', '1999-11-17', 'ConstitucionRBV1999.pdf', 'http://www.tsj.gov.ve/legislacion/constitucion1999.htm');
