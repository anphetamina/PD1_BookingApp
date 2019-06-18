-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 17, 2019 alle 14:49
-- Versione del server: 10.1.40-MariaDB
-- Versione PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_app`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `seat`
--

CREATE TABLE `seat` (
  `seat_id` varchar(10) NOT NULL,
  `state` varchar(10) NOT NULL,
  `user` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `seat`
--

INSERT INTO `seat` (`seat_id`, `state`, `user`) VALUES
('A4', 'booked', 'u1@p.it'),
('B2', 'bought', 'u2@p.it'),
('B3', 'bought', 'u2@p.it'),
('B4', 'bought', 'u2@p.it'),
('D4', 'booked', 'u1@p.it'),
('F4', 'booked', 'u2@p.it');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('u1@p.it', '$2y$10$Bk81sXH2oG2.U.9LZEC39.XlfN2xX5qpVRxHf8sMjPbWx5AIAmy/C'),
('u2@p.it', '$2y$10$bW4g7CyWXTO.1NBZPR.7.OPoVIpJv1jeCVJtoZCH/YwijEXKqqEvi');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`seat_id`),
  ADD UNIQUE KEY `seat_seat_id_uindex` (`seat_id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `user_user_uindex` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
