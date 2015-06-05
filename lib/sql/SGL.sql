-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 24, 2011 at 07:37 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `SGL`
--

-- --------------------------------------------------------

--
-- Table structure for table `escritorios_juridicos`
--

CREATE TABLE `escritorios_juridicos` (
  `id_escritorio_juridico` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_escritorio_juridico` varchar(120) NOT NULL,
  `dominio_escritorio_juridico` varchar(120) NOT NULL,
  `username_db_escritorio_juridico` varchar(120) DEFAULT NULL,
  `password_db_escritorio_juridico` varchar(120) DEFAULT NULL,
  `dbname_db_escritorio_juridico` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id_escritorio_juridico`),
  UNIQUE KEY `dominio_escritorio_juridico` (`dominio_escritorio_juridico`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombres_usuario` varchar(120) NOT NULL,
  `apellidos_usuario` varchar(120) NOT NULL,
  `direccion_habitacion_usuario` varchar(120) DEFAULT NULL,
  `telefono_habitacion_usuario` varchar(16) DEFAULT NULL,
  `telefono_celular_usuario` varchar(16) DEFAULT NULL,
  `email_principal_usuario` varchar(120) NOT NULL,
  `email_alternativo_usuario` varchar(120) DEFAULT NULL,
  `password_usuario` varchar(120) NOT NULL,
  `escritorio_juridico_id` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `login` (`email_principal_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;