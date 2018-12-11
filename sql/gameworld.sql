-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Czas generowania: 29 Lis 2018, 10:21
-- Wersja serwera: 5.7.19
-- Wersja PHP: 7.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `gameworld`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games`
--

CREATE TABLE `games` (
  `ID` int(11) NOT NULL,
  `gameName` text COLLATE utf8_polish_ci NOT NULL,
  `gameImg` text COLLATE utf8_polish_ci NOT NULL,
  `gamePrice` decimal(10,2) NOT NULL,
  `gamesLeft` int(11) NOT NULL,
  `PS3` tinyint(1) NOT NULL,
  `XBOX` tinyint(1) NOT NULL,
  `PC` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `games`
--

INSERT INTO `games` (`ID`, `gameName`, `gameImg`, `gamePrice`, `gamesLeft`, `PS3`, `XBOX`, `PC`) VALUES
(1, 'Grand Theft Auto V', 'gtav.jpg', '31.99', 46, 1, 1, 1),
(2, 'Counter Strike: Global Offensive', 'csgo.jpg', '6.99', 145, 0, 0, 1),
(3, 'Garry\'s Mod', 'garrysmod.jpg', '4.99', 234, 0, 0, 1),
(4, 'Rocket League', 'rocketleague.jpg', '12.99', 67, 1, 1, 1),
(5, 'Overwatch', 'overwatch.jpg', '35.99', 34, 0, 0, 1),
(6, 'Call of Duty: Black Ops 2', 'codbo2.jpg', '15.95', 78, 1, 1, 1),
(7, 'Playerunknown\'s Battlegrounds', 'pubg.jpg', '30.49', 112, 0, 0, 1),
(8, 'Human Fall Flat', 'humanfallflat.jpg', '13.49', 256, 1, 0, 1),
(9, 'Euro Track Simulator 2', 'ets2.jpg', '7.95', 87, 1, 0, 1),
(10, '7 Days to Die', '7daystodie.jpg', '11.99', 45, 0, 0, 1),
(11, 'Dead by Daylight', 'dbd.jpg', '10.99', 73, 0, 0, 1),
(12, 'Hearts of Iron IV', 'heartsofironiv.jpg', '24.99', 42, 0, 0, 1),
(13, 'ARK: Survival Evolved', 'ark.jpg', '27.49', 79, 0, 0, 1),
(14, 'Battlefield 4', 'bf4.jpg', '6.95', 128, 1, 1, 1),
(15, 'Forza Horizon 4', 'fh4.jpg', '70.95', 467, 0, 1, 0),
(16, 'Shadow of the Tomb Raider', 'sottr.jpg', '33.49', 346, 1, 1, 0),
(17, 'Grand Theft Auto V', 'gtav.jpg', '32.99', 46, 1, 1, 1),
(18, 'Fifa 19', 'fifa19.jpg', '16.99', 35, 1, 1, 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `games`
--
ALTER TABLE `games`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
