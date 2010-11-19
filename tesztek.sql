-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2010. nov. 19. 20:47
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
  `csoportkod` int(11) NOT NULL AUTO_INCREMENT,
  `csoport` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `leiras` varchar(200) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`csoportkod`),
  UNIQUE KEY `csoport` (`csoport`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- A tábla adatainak kiíratása `csoportok`
--

INSERT INTO `csoportok` (`csoportkod`, `csoport`, `leiras`) VALUES
(1, 'adminok', 'RendszergazdÃ¡k.'),
(2, 'proba', 'Csak az adatbÃ¡zis tesztelÃ©sÃ©hez.'),
(3, 'vendegek', 'VendÃ©gek.'),
(4, 'diÃ¡kok', 'Az iskola diÃ¡kjai.'),
(5, 'tanÃ¡rok', 'Az iskola tanÃ¡rai.');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `felhasznalok`
--

CREATE TABLE IF NOT EXISTS `felhasznalok` (
  `nev` varchar(50) CHARACTER SET utf8 NOT NULL,
  `jelszo` varchar(64) CHARACTER SET utf8 NOT NULL,
  `vnev` varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `knev` varchar(50) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  `csoportkod` int(11) NOT NULL,
  `jogok` int(2) NOT NULL,
  `belepett` int(1) NOT NULL,
  PRIMARY KEY (`nev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`nev`, `jelszo`, `vnev`, `knev`, `csoportkod`, `jogok`, `belepett`) VALUES
('', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'Rongy', 'Elek', 2, 2, 0),
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Rendszer', 'Gazda', 1, 0, 0),
('vendeg', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'KivÃ¡ncsi', 'FÃ¡ncsi', 3, 3, 0),
('guest', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'Ing', 'Lis', 3, 0, 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `kerdesek`
--

CREATE TABLE IF NOT EXISTS `kerdesek` (
  `tesztkod` int(11) NOT NULL,
  `kerdesszam` int(11) NOT NULL,
  `kerdes` text NOT NULL,
  `valasz_a` text NOT NULL,
  `helyes_a` tinyint(1) NOT NULL,
  `valasz_b` text NOT NULL,
  `helyes_b` tinyint(1) NOT NULL,
  `valasz_c` text NOT NULL,
  `helyes_c` tinyint(1) NOT NULL,
  `valasz_d` text NOT NULL,
  `helyes_d` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `kerdesek`
--

INSERT INTO `kerdesek` (`tesztkod`, `kerdesszam`, `kerdes`, `valasz_a`, `helyes_a`, `valasz_b`, `helyes_b`, `valasz_c`, `helyes_c`, `valasz_d`, `helyes_d`) VALUES
(1, 1, 'A', 'd', 0, 's', 0, '', 0, '', 0),
(1, 2, 'Haha!', 'a', 0, 'b', 0, '', 0, '', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `lathatotesztek`
--

CREATE TABLE IF NOT EXISTS `lathatotesztek` (
  `csoportkod` int(11) NOT NULL,
  `tesztkod` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `lathatotesztek`
--

INSERT INTO `lathatotesztek` (`csoportkod`, `tesztkod`) VALUES
(2, 1),
(3, 3),
(2, 3),
(2, 4),
(3, 8),
(2, 7),
(1, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `megoldasok`
--

CREATE TABLE IF NOT EXISTS `megoldasok` (
  `nev` varchar(50) CHARACTER SET utf8 NOT NULL,
  `tesztkod` int(11) NOT NULL,
  `kerdesszam` int(11) NOT NULL,
  `valasztott_a` tinyint(1) NOT NULL,
  `valasztott_b` tinyint(1) NOT NULL,
  `valasztott_c` tinyint(1) NOT NULL,
  `valasztott_d` tinyint(1) NOT NULL,
  `helyes` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `megoldasok`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `teszt`
--

CREATE TABLE IF NOT EXISTS `teszt` (
  `tesztkod` int(11) NOT NULL AUTO_INCREMENT,
  `tesztnev` varchar(100) CHARACTER SET utf8 COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`tesztkod`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- A tábla adatainak kiíratása `teszt`
--

INSERT INTO `teszt` (`tesztkod`, `tesztnev`) VALUES
(1, 'ElsÅ‘ teszt'),
(2, 'MÃ¡sodik teszt'),
(3, 'Harmadik teszt'),
(4, 'Negyedik'),
(5, 'Hatodik'),
(6, 'Hatodik'),
(7, 'Hetedik'),
(8, 'Nyolcadik'),
(9, 'TizenkettÅ‘');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
