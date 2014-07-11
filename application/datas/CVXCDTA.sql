-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Ven 11 Juillet 2014 à 07:36
-- Version du serveur: 5.5.37
-- Version de PHP: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `CVXCDTA`
--

-- --------------------------------------------------------

--
-- Structure de la table `OCUSMA`
--

CREATE TABLE IF NOT EXISTS `OCUSMA` (
  `OKCUNO` varchar(10) NOT NULL,
  `OKCUNM` varchar(80) NOT NULL,
  `OKCUCL` varchar(3) NOT NULL,
  `OKACGR` varchar(4) NOT NULL,
  `OKCFC7` varchar(80) NOT NULL,
  `OKCUA1` varchar(80) NOT NULL,
  `OKCUA2` varchar(80) NOT NULL,
  `OKCUA3` varchar(80) NOT NULL,
  `OKCUA4` varchar(80) NOT NULL,
  `OKPHNO` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `OCUSMA`
--

INSERT INTO `OCUSMA` (`OKCUNO`, `OKCUNM`, `OKCUCL`, `OKACGR`, `OKCFC7`, `OKCUA1`, `OKCUA2`, `OKCUA3`, `OKCUA4`, `OKPHNO`) VALUES
('I01277', 'GIRBAU ROBOTICS     ', 'CA3', 'IS06', '0', '.     ', 'ZI ECHANGEUR AIX NORD', '    ', '73100 AIX LES BAINS', '04 79 34 80 80');

-- --------------------------------------------------------

--
-- Structure de la table `OOLINE`
--

CREATE TABLE IF NOT EXISTS `OOLINE` (
  `OBORNO` varchar(15) NOT NULL,
  `OBCUNO` varchar(10) NOT NULL,
  `OBITNO` int(10) NOT NULL,
  `OBITDS` varchar(15) NOT NULL,
  `OBORQT` varchar(15) NOT NULL,
  `OBLNA2` varchar(15) NOT NULL,
  `OBLNAM` varchar(15) NOT NULL,
  `OBNEPR` varchar(15) NOT NULL,
  `OBSAPR` varchar(10) NOT NULL,
  `OBELNO` date NOT NULL,
  `OBRGDT` date NOT NULL,
  `OBLMDT` date NOT NULL,
  `OBSMCD` varchar(4) NOT NULL,
  `OBDIVI` varchar(3) NOT NULL DEFAULT 'FR0',
  `OBCONO` int(3) NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `OOLINE`
--

INSERT INTO `OOLINE` (`OBORNO`, `OBCUNO`, `OBITNO`, `OBITDS`, `OBORQT`, `OBLNA2`, `OBLNAM`, `OBNEPR`, `OBSAPR`, `OBELNO`, `OBRGDT`, `OBLMDT`, `OBSMCD`, `OBDIVI`, `OBCONO`) VALUES
('0090636993', 'I01277', 28125, 'TU0425R-100  ', '20.000000', '212.40', '212.40', '10.620000', '42.480000', '2014-06-12', '2014-06-10', '2014-06-10', 'IB06', 'FR0', 100),
('0090636993', 'I01277', 28169, 'TU0604BU-100', '20.000000', '465.80', '465.80', '23.290000', '93.170000', '2014-06-12', '2014-06-10', '2014-06-10', 'IB06', 'FR0', 100),
('0090636993', 'I01277', 28285, 'TU0805R-100', '5.000000', '209.05', '209.05', '41.810000', '167.260000', '2014-06-12', '2014-06-10', '2014-06-10', 'IB06', 'FR0', 100);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
