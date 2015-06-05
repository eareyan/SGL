-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2011 at 01:34 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `escritoriojuridico`
--

-- --------------------------------------------------------

--
-- Table structure for table `abogados`
--

CREATE TABLE `abogados` (
  `id_abogado` int(11) NOT NULL AUTO_INCREMENT,
  `nombres_abogado` varchar(120) NOT NULL,
  `apellidos_abogado` varchar(120) NOT NULL,
  `especialidad_abogado` varchar(120) DEFAULT NULL,
  `fecha_nacimiento_abogado` date DEFAULT NULL,
  `direccion_habitacion_abogado` varchar(120) DEFAULT NULL,
  `telefono_habitacion_abogado` varchar(16) DEFAULT NULL,
  `telefono_celular_abogado` varchar(16) DEFAULT NULL,
  `email_principal_abogado` varchar(120) NOT NULL,
  `email_alternativo_abogado` varchar(120) DEFAULT NULL,
  `curriculo_abogado` text,
  PRIMARY KEY (`id_abogado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `casos`
--

CREATE TABLE `casos` (
  `id_caso` int(11) NOT NULL AUTO_INCREMENT,
  `finalizado_caso` int(1) NOT NULL DEFAULT '0',
  `nombre_caso` varchar(120) NOT NULL,
  `descripcion_caso` text,
  `activo_caso` int(1) NOT NULL DEFAULT '1',
  `fecha_inicio_caso` date DEFAULT NULL,
  `fecha_fin_caso` date DEFAULT NULL,
  `cuota_caso` float DEFAULT NULL,
  `cuenta_id` varchar(120) DEFAULT NULL,
  `publico_caso` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_caso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `casos-abogado`
--

CREATE TABLE `casos-abogado` (
  `id_caso-abogado` int(11) NOT NULL AUTO_INCREMENT,
  `caso_id` int(11) NOT NULL,
  `abogado_id` int(11) NOT NULL,
  PRIMARY KEY (`id_caso-abogado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `casos-cliente`
--

CREATE TABLE `casos-cliente` (
  `id_caso-cliente` int(11) NOT NULL AUTO_INCREMENT,
  `caso_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY (`id_caso-cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombres_cliente` varchar(120) NOT NULL,
  `apellidos_cliente` varchar(120) NOT NULL,
  `fecha_nacimiento_cliente` date DEFAULT NULL,
  `direccion_habitacion_cliente` varchar(120) DEFAULT NULL,
  `telefono_habitacion_cliente` varchar(16) DEFAULT NULL,
  `telefono_oficina_cliente` varchar(16) DEFAULT NULL,
  `telefono_celular_cliente` varchar(16) DEFAULT NULL,
  `email_principal_cliente` varchar(120) NOT NULL,
  `email_alternativo_cliente` varchar(120) DEFAULT NULL,
  `password_cliente` varchar(120) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `login` (`email_principal_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `cuentas`
--

CREATE TABLE `cuentas` (
  `id_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cuenta` varchar(120) NOT NULL,
  `banco_cuenta` varchar(120) NOT NULL,
  `titular_cuenta` varchar(120) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `tipo_cuenta` varchar(120) NOT NULL,
  `identificacion_cuenta` varchar(120) NOT NULL,
  `correo_cuenta` varchar(120) NOT NULL,
  `comentario_cuenta` text,
  PRIMARY KEY (`id_cuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `historiacasos`
--

CREATE TABLE `historiacasos` (
  `id_historiacaso` int(11) NOT NULL AUTO_INCREMENT,
  `caso_id` int(11) NOT NULL,
  `estatus_historiacaso` varchar(120) NOT NULL,
  `comentario_abogado_historiacaso` text,
  `comentario_cliente_historiacaso` text,
  `archivo_historiacaso` varchar(120) DEFAULT NULL,
  `fecha_historiacaso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_historiacaso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `leyes`
--

CREATE TABLE `leyes` (
  `id_ley` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_ley` varchar(120) NOT NULL,
  `descripcion_ley` text,
  `fecha_publicacion_ley` date NOT NULL,
  `archivo_ley` varchar(120) DEFAULT NULL,
  `link_ley` varchar(240) DEFAULT NULL,
  PRIMARY KEY (`id_ley`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

