-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 03 apr 2013 om 15:25
-- Serverversie: 5.5.29
-- PHP-Versie: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fileswine`
--
CREATE DATABASE `fileswine` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fileswine`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(16) NOT NULL DEFAULT 'file',
  `size` bigint(20) NOT NULL,
  `first_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_2` (`name`,`type`,`size`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=112590 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `path`
--

CREATE TABLE IF NOT EXISTS `path` (
  `item_id` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`,`path`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `name` varchar(255) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `share`
--

CREATE TABLE IF NOT EXISTS `share` (
  `ip` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `description` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL DEFAULT '0',
  `files` bigint(20) NOT NULL DEFAULT '0',
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
