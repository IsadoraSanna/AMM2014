-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Ago 13, 2014 alle 14:35
-- Versione del server: 5.5.35
-- Versione PHP: 5.4.6-1ubuntu1.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amm14_sannaIsadora`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `domicilio` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `prezzo` float unsigned DEFAULT NULL,
  `stato` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `cliente_id` bigint(20) unsigned DEFAULT NULL,
  `addettoOrdini_id` bigint(20) unsigned DEFAULT NULL,
  `orario_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordini_ibfk_1` (`cliente_id`),
  KEY `ordini_ibfk_2` (`addettoOrdini_id`),
  KEY `ordini_ibfk_3` (`orario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `ordini`
--

INSERT INTO `ordini` (`id`, `domicilio`, `prezzo`, `stato`, `data`, `cliente_id`, `addettoOrdini_id`, `orario_id`) VALUES
(1, 's', 103, 'non pagato', '2014-08-04 00:00:00', 1, 1, 2),
(2, 's', 20, 'non pagato', '2014-08-04 00:00:00', 1, 1, 2),
(3, 's', 45, 'non pagato', '2014-08-04 00:00:00', 1, 1, 3),
(4, 's', 8, 'non pagato', '2014-08-04 00:00:00', 1, 1, 2),
(5, 's', 8, 'non pagato', '2014-08-05 00:00:00', 1, 1, 2),
(6, 'n', 17, 'pagato', '2014-08-05 00:00:00', 1, 1, 3),
(7, 's', 9, 'non pagato', '2014-08-11 00:00:00', 1, 1, 2),
(8, 's', 8, 'non pagato', '2014-08-11 00:00:00', 1, 1, 2),
(9, 's', 9, 'non pagato', '2014-08-11 00:00:00', 1, 1, 3),
(10, 's', 13, 'non pagato', '2014-08-11 00:00:00', 1, 1, 4);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clienti` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordini_ibfk_2` FOREIGN KEY (`addettoOrdini_id`) REFERENCES `addettoOrdini` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordini_ibfk_3` FOREIGN KEY (`orario_id`) REFERENCES `orari` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
