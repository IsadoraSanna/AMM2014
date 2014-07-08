-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Lug 06, 2014 alle 11:30
-- Versione del server: 5.5.35
-- Versione PHP: 5.4.6-1ubuntu1.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pizzeria`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `addettoOrdini`
--

CREATE TABLE IF NOT EXISTS `addettoOrdini` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `nome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `cognome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `via` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `civico` int(10) unsigned DEFAULT NULL,
  `cap` int(5) unsigned DEFAULT NULL,
  `citta` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `telefono` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `addettoOrdini`
--

INSERT INTO `addettoOrdini` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `civico`, `cap`, `citta`, `telefono`) VALUES
(1, 'addettoRossi', 'amm14', 'mario', 'rossi', 'roma', 115, NULL, NULL, 444444444);

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE IF NOT EXISTS `clienti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `nome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `cognome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `via` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `civico` int(10) unsigned DEFAULT NULL,
  `cap` int(5) unsigned DEFAULT NULL,
  `citta` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `telefono` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `civico`, `cap`, `citta`, `telefono`) VALUES
(1, 'isadora', 'amm14', 'isadora', 'sanna', NULL, NULL, NULL, NULL, 333333333);

-- --------------------------------------------------------

--
-- Struttura della tabella `orari`
--

CREATE TABLE IF NOT EXISTS `orari` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fasciaOraria` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `ordiniDisponibili` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orario` timestamp NULL DEFAULT NULL,
  `domicilio` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `indirizzoConsegna` varchar(512) COLLATE utf8_bin DEFAULT NULL,
  `cliente_id` bigint(20) unsigned DEFAULT NULL,
  `addettoOrdini_id` bigint(20) unsigned DEFAULT NULL,
  `orario_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clienti_fk` (`cliente_id`),
  KEY `addettoOrdini_fk` (`addettoOrdini_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze`
--

CREATE TABLE IF NOT EXISTS `pizze` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `ingredienti` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `prezzo` float unsigned DEFAULT NULL,
  `immagine` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze_ordini`
--

CREATE TABLE IF NOT EXISTS `pizze_ordini` (
  `pizza_id` bigint(20) unsigned NOT NULL,
  `ordine_id` bigint(20) unsigned NOT NULL,
  KEY `pizze_fk` (`pizza_id`),
  KEY `ordini_fk` (`ordine_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordini_ibfk_2` FOREIGN KEY (`addettoOrdini_id`) REFERENCES `addettoOrdini` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `pizze_ordini`
--
ALTER TABLE `pizze_ordini`
  ADD CONSTRAINT `pizze_ordini_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizze` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pizze_ordini_ibfk_2` FOREIGN KEY (`ordine_id`) REFERENCES `ordini` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
