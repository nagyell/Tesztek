-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2010. nov. 17. 19:17
-- Szerver verzió: 5.1.41
-- PHP verzió: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `tesztek`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `csoportok`
--

CREATE TABLE IF NOT EXISTS `csoportok` (
  `csoport` varchar(10) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `leiras` varchar(200) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`csoport`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `csoportok`
--

INSERT INTO `csoportok` (`csoport`, `leiras`) VALUES
('adminok', 'Rendszergazdák.'),
('proba', 'Csak az adatbázis teszteléséhez.');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `felhasznalok`
--

CREATE TABLE IF NOT EXISTS `felhasznalok` (
  `nev` varchar(50) CHARACTER SET utf8 NOT NULL,
  `jelszo` varchar(64) CHARACTER SET utf8 NOT NULL,
  `vnev` varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `knev` varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `csoport` varchar(10) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `jogok` int(2) NOT NULL,
  `belepett` int(1) NOT NULL,
  PRIMARY KEY (`nev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`nev`, `jelszo`, `vnev`, `knev`, `csoport`, `jogok`, `belepett`) VALUES
('', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'Rongy', 'Elek', 'proba', 0, 0),
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Rendszer', 'Gazda', 'adminok', 0, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `lathatotesztek`
--

CREATE TABLE IF NOT EXISTS `lathatotesztek` (
  `csoport` varchar(10) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `tesztkod` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `lathatotesztek`
--

INSERT INTO `lathatotesztek` (`csoport`, `tesztkod`) VALUES
('proba', 1),
('proba', 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `teszt`
--

CREATE TABLE IF NOT EXISTS `teszt` (
  `tesztkod` int(11) NOT NULL AUTO_INCREMENT,
  `tesztnev` varchar(100) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`tesztkod`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- A tábla adatainak kiíratása `teszt`
--

INSERT INTO `teszt` (`tesztkod`, `tesztnev`) VALUES
(1, 'Első teszt'),
(2, 'Második teszt'),
(3, 'Harmadik teszt'),
(4, 'Negyedik'),
(5, 'Ötödik'),
(6, 'Hatodik'),
(7, 'Hetedik'),
(8, 'Nyolcadik');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
