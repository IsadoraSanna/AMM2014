-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 02, 2014 alle 18:38
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
  `cap` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `citta` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `telefono` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `addettoOrdini`
--

INSERT INTO `addettoOrdini` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `civico`, `cap`, `citta`, `telefono`) VALUES
(1, 'addetto', '', 'Mario', 'Rossi', 'roma', 115, '09124', 'Cagliari', 444444444);

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
  `civico` int(10) DEFAULT NULL,
  `cap` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `citta` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `telefono` int(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `clienti`
--

INSERT INTO `clienti` (`id`, `username`, `password`, `nome`, `cognome`, `via`, `civico`, `cap`, `citta`, `telefono`) VALUES
(1, 'isadora', '', 'Isadora', 'Sanna', 'Roma', 30, '09033', 'Assemini', 345555556);

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienti`
--

CREATE TABLE IF NOT EXISTS `ingredienti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `orari`
--

CREATE TABLE IF NOT EXISTS `orari` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fasciaOraria` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `ordiniDisponibili` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

--
-- Dump dei dati per la tabella `orari`
--

INSERT INTO `orari` (`id`, `fasciaOraria`, `ordiniDisponibili`) VALUES
(2, '19.30-19.45', 30),
(3, '19.45-20.00', 30),
(4, '20.00-20.15', 30),
(5, '20.15-20.30', 30),
(6, '20.30-20.45', 35),
(7, '20.45-21.00', 35),
(8, '21.00-21.15', 40),
(9, '21.15-21.30', 40),
(10, '21.30-21.45', 40),
(11, '21.45-22.00', 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE IF NOT EXISTS `ordini` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `domicilio` varchar(2) COLLATE utf8_bin DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze`
--

CREATE TABLE IF NOT EXISTS `pizze` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `ingredienti` varchar(256) COLLATE utf8_bin DEFAULT NULL,
  `prezzo` float unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=21 ;

--
-- Dump dei dati per la tabella `pizze`
--

INSERT INTO `pizze` (`id`, `nome`, `ingredienti`, `prezzo`) VALUES
(1, 'Margherita', 'pom., mozz.', 3),
(2, 'Napoli', 'pom., mozz., acciughe, capperi', 3.5),
(3, 'Calzone', 'pom., mozz., prosc., funghi', 4.5),
(4, 'Prosciutto crudo', 'pom., mozz., prosc. crudo', 5),
(6, 'Tonno e cipolla', 'pom., mozz., tonno, cipolla', 5),
(7, '4 stagioni', 'pom., mozz., prosc., funghi, wur, olive, carc', 5.5),
(8, '4 formaggi', 'pom., mozz., gorg., grov., pecor., parmig.', 5.5),
(9, 'Caprese', 'pom., mozz., pom. fresco, origano', 4),
(10, 'Sarda', 'pom., mozz., salsiccia secca, pecorino', 5.5),
(11, 'Rustica', 'pom., mozz., funghi di carne, patate', 5.5),
(12, 'Panna e salmone', 'pom., mozz., salmone, panna', 7),
(14, 'Rucola e grana', 'pom., mozz., rucola, grana', 4.5),
(15, 'Frutti di mare', 'pom., mozz., frutti di mare', 6.5),
(16, 'Carbonara', 'pom., mozz., uovo, pancetta', 6.5),
(17, 'Carlofortina', 'pom., mozz., pesto, pomodorini, tonno', 6),
(18, 'Saporita', 'pom., mozz., salsiccia fr., gorgonzola', 6.5),
(19, 'Bufala', 'pom., mozzarella di bufala', 5),
(20, 'Tirolese', 'pom., mozz., speck, gorgonzola', 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze_ordini`
--

CREATE TABLE IF NOT EXISTS `pizze_ordini` (
  `pizza_id` bigint(20) unsigned NOT NULL,
  `ordine_id` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quantita` int(2) unsigned DEFAULT NULL,
  `dimensione` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pizze_ordini_ibfk_1` (`pizza_id`),
  KEY `pizze_ordini_ibfk_2` (`ordine_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=278 ;

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

--
-- Limiti per la tabella `pizze_ordini`
--
ALTER TABLE `pizze_ordini`
  ADD CONSTRAINT `pizze_ordini_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizze` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pizze_ordini_ibfk_2` FOREIGN KEY (`ordine_id`) REFERENCES `ordini` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
