CREATE DATABASE julian1828;
USE julian1828;
--
-- Datenbank: `julian1828`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `result`
--

CREATE TABLE `result` (
  `ID` int(11) NOT NULL,
  `F_userID` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `totaltime` time DEFAULT NULL,
  `flips` int(11) DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `language` varchar(50) DEFAULT 'english'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `F_results_user` (`F_userID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `result`
--
ALTER TABLE `result`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `F_results_user` FOREIGN KEY (`F_userID`) REFERENCES `user` (`userID`);
