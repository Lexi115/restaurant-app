-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 08, 2022 alle 23:28
-- Versione del server: 10.4.22-MariaDB
-- Versione PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ristorante`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `username` varchar(30) NOT NULL,
  `password_hash` char(60) NOT NULL,
  `cod_gruppo` tinyint(3) NOT NULL,
  `token_accesso` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `accounts`
--

INSERT INTO `accounts` (`username`, `password_hash`, `cod_gruppo`, `token_accesso`) VALUES
('luigi.verdi', '$2y$10$aSTmvorZsWMgTTg1g09utOU7WlCI6C3p56O2O8EkloBO1KP2JUYpK', 2, 'bafd253a8dbced5bf4ac'),
('mario.rossi', '$2y$10$E5rsBF97M.J0.F/SOUyNeejq2vY.86L32PtKVhofSPAK4XYlVFa/y', 1, '71432638cf7a9293edbe');

-- --------------------------------------------------------

--
-- Struttura della tabella `clienti`
--

CREATE TABLE `clienti` (
  `cf_cliente` char(16) NOT NULL,
  `cognome` varchar(40) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `indirizzo` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi`
--

CREATE TABLE `gruppi` (
  `cod_gruppo` tinyint(3) NOT NULL,
  `nome_gruppo` varchar(20) NOT NULL,
  `cod_set_permessi` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `gruppi`
--

INSERT INTO `gruppi` (`cod_gruppo`, `nome_gruppo`, `cod_set_permessi`) VALUES
(0, 'ospite', 0),
(1, 'cameriere', 1),
(2, 'proprietario', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `permessi`
--

CREATE TABLE `permessi` (
  `cod_set_permessi` tinyint(3) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `mostra_prenotazioni` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `permessi`
--

INSERT INTO `permessi` (`cod_set_permessi`, `admin`, `mostra_prenotazioni`) VALUES
(0, 0, 0),
(1, 0, 1),
(2, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `cod_prenotazione` char(10) NOT NULL,
  `cf_cliente` char(16) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `n_persone` smallint(6) NOT NULL,
  `note_aggiuntive` varchar(300) DEFAULT NULL,
  `cod_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `sale`
--

CREATE TABLE `sale` (
  `cod_sala` int(11) NOT NULL,
  `nome_sala` varchar(80) NOT NULL,
  `cod_tipo_sala` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sale`
--

INSERT INTO `sale` (`cod_sala`, `nome_sala`, `cod_tipo_sala`) VALUES
(1, 'Sala Principale', 0),
(2, 'Spazio Bambini', 1),
(4, 'Sala Piccola', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `statusprenotazione`
--

CREATE TABLE `statusprenotazione` (
  `cod_status` tinyint(4) NOT NULL,
  `descrizione_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `statusprenotazione`
--

INSERT INTO `statusprenotazione` (`cod_status`, `descrizione_status`) VALUES
(0, 'Confermata'),
(2, 'Disdetta'),
(1, 'In attesa di validazione');

-- --------------------------------------------------------

--
-- Struttura della tabella `tavoli`
--

CREATE TABLE `tavoli` (
  `n_tavolo` smallint(6) NOT NULL,
  `n_posti` smallint(6) NOT NULL,
  `cod_sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tavoli`
--

INSERT INTO `tavoli` (`n_tavolo`, `n_posti`, `cod_sala`) VALUES
(1, 6, 2),
(2, 4, 2),
(3, 8, 1),
(4, 4, 1),
(5, 4, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `tavoliprenotati`
--

CREATE TABLE `tavoliprenotati` (
  `cod_prenotazione` char(10) NOT NULL,
  `n_tavolo` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `tipisala`
--

CREATE TABLE `tipisala` (
  `cod_tipo_sala` smallint(6) NOT NULL,
  `nome_tipo_sala` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tipisala`
--

INSERT INTO `tipisala` (`cod_tipo_sala`, `nome_tipo_sala`) VALUES
(0, 'Al chiuso'),
(1, 'All''aperto');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `token_accesso` (`token_accesso`),
  ADD KEY `gruppo` (`cod_gruppo`);

--
-- Indici per le tabelle `clienti`
--
ALTER TABLE `clienti`
  ADD PRIMARY KEY (`cf_cliente`),
  ADD UNIQUE KEY `telefono` (`telefono`) USING BTREE;

--
-- Indici per le tabelle `gruppi`
--
ALTER TABLE `gruppi`
  ADD PRIMARY KEY (`cod_gruppo`),
  ADD KEY `set_permessi` (`cod_set_permessi`);

--
-- Indici per le tabelle `permessi`
--
ALTER TABLE `permessi`
  ADD PRIMARY KEY (`cod_set_permessi`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`cod_prenotazione`),
  ADD KEY `cf_cliente` (`cf_cliente`),
  ADD KEY `status` (`cod_status`);

--
-- Indici per le tabelle `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`cod_sala`),
  ADD KEY `tipo_sala` (`cod_tipo_sala`);

--
-- Indici per le tabelle `statusprenotazione`
--
ALTER TABLE `statusprenotazione`
  ADD PRIMARY KEY (`cod_status`),
  ADD UNIQUE KEY `descrizione_status` (`descrizione_status`);

--
-- Indici per le tabelle `tavoli`
--
ALTER TABLE `tavoli`
  ADD PRIMARY KEY (`n_tavolo`),
  ADD KEY `cod_sala` (`cod_sala`);

--
-- Indici per le tabelle `tavoliprenotati`
--
ALTER TABLE `tavoliprenotati`
  ADD PRIMARY KEY (`cod_prenotazione`,`n_tavolo`),
  ADD KEY `numero_tavolo` (`n_tavolo`);

--
-- Indici per le tabelle `tipisala`
--
ALTER TABLE `tipisala`
  ADD PRIMARY KEY (`cod_tipo_sala`),
  ADD UNIQUE KEY `nome_sala` (`nome_tipo_sala`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `sale`
--
ALTER TABLE `sale`
  MODIFY `cod_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`cod_gruppo`) REFERENCES `gruppi` (`cod_gruppo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  ADD CONSTRAINT `gruppi_ibfk_1` FOREIGN KEY (`cod_set_permessi`) REFERENCES `permessi` (`cod_set_permessi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`cf_cliente`) REFERENCES `clienti` (`cf_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`cod_status`) REFERENCES `statusprenotazione` (`cod_status`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`cod_tipo_sala`) REFERENCES `tipisala` (`cod_tipo_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tavoli`
--
ALTER TABLE `tavoli`
  ADD CONSTRAINT `tavoli_ibfk_1` FOREIGN KEY (`cod_sala`) REFERENCES `sale` (`cod_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tavoliprenotati`
--
ALTER TABLE `tavoliprenotati`
  ADD CONSTRAINT `tavoliprenotati_ibfk_3` FOREIGN KEY (`cod_prenotazione`) REFERENCES `prenotazioni` (`cod_prenotazione`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tavoliprenotati_ibfk_4` FOREIGN KEY (`n_tavolo`) REFERENCES `tavoli` (`n_tavolo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
