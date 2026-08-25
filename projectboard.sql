-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Aug 25. 10:06
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `projectboard`
--
CREATE DATABASE IF NOT EXISTS `projectboard` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `projectboard`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$g1C/DGkXlji12/q1SRa/BuFmslvipXadT7jLPIFE5B9N220W4BCiC', '2026-07-04 14:59:22');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('personal','client') NOT NULL DEFAULT 'personal',
  `status` enum('planning','active','completed','cancelled') NOT NULL DEFAULT 'planning',
  `price` int(11) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `live_url` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `type`, `status`, `price`, `deadline`, `github_url`, `live_url`, `notes`, `created_at`) VALUES
(4, 'Portfólió weboldal', 'Bemutatkozó oldal készítése menüsorral.                                         ', 'client', 'planning', 205000, '2026-09-22', NULL, NULL, 'Még nem dőlt el hány oldalas, megrendelővel megbeszélni.', '2026-07-04 16:02:46'),
(5, 'Zenei adatbázis (új)', 'Music DataBase  new                                                                                               ', 'client', 'active', 103000, '2026-12-30', NULL, NULL, '0.2 verzió után elmenteni az m3u fájl adatait. 0.3 után a lejátszást', '2026-07-04 16:20:28'),
(6, 'Pékség weboldal', 'Pék vállalkozás weboldala. Klasszikus statikus oldal.', 'client', 'planning', 79000, '2026-10-01', NULL, NULL, '', '2026-07-05 08:20:04'),
(7, 'Könyv adatbázis', 'Tobbfunkciós könyv adatbázis. CRUD. Keresés, ebook betöltés, rendezés.            ', 'personal', 'planning', NULL, '2026-10-11', NULL, NULL, 'helyi mappában tárolt ebook-ok', '2026-07-05 09:25:05'),
(8, 'Kisgép kölcsönző weboldal', 'Gépkölcsönző vállalkozás weboldala. Bejelentkezéssel, és gép lefoglalással.                         ', 'client', 'planning', 305900, '2026-11-07', NULL, NULL, 'A fejlesztés MARIADB adatbázissal. ', '2026-07-05 09:32:03');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `project_versions`
--

CREATE TABLE `project_versions` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `version_number` varchar(50) NOT NULL,
  `change_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deployed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `project_versions`
--

INSERT INTO `project_versions` (`id`, `project_id`, `version_number`, `change_type`, `description`, `deployed`, `created_at`) VALUES
(1, 5, '0.1', 'feature', 'Projekt létrehozása', 0, '2026-07-04 16:20:28'),
(2, 5, '0.3', 'feature', 'm3u fájl beolvasása', 0, '2026-07-04 16:43:17'),
(10, 4, '0.1', 'feature', 'Projekt módosítása', 0, '2026-07-04 17:57:52'),
(12, 5, '0.4', 'bugfix', 'A mentés javítása. Kétirányú adatbázis kapcsolat helyreállítása. CSS fájl tisztítás, komment csere. Javítva. ', 0, '2026-07-05 05:12:41'),
(13, 5, '0.2', 'content', 'Próba a verzió sorrendre.', 0, '2026-07-05 06:51:00'),
(14, 6, '0.1', 'Létrehozás', 'Projekt létrehozása', 0, '2026-07-05 08:20:04'),
(15, 6, '0.2', 'feature', 'Statikus főoldal elkészítése.', 0, '2026-07-05 09:23:16'),
(16, 7, '0.1', 'Létrehozás', 'Projekt létrehozása', 0, '2026-07-05 09:25:05'),
(17, 8, '0.1', 'Létrehozás', 'Projekt létrehozása', 0, '2026-07-05 09:32:03'),
(18, 8, '0.2', 'feature', 'Bejelentkezési oldal elkészült', 0, '2026-07-05 09:33:39'),
(20, 8, '0.3', 'feature', 'Gép lefoglalási rutin elkészült', 0, '2026-07-06 04:20:35'),
(21, 8, '0.4', 'feature', 'Személy adatbázis elkészült', 0, '2026-07-07 04:06:18'),
(22, 7, '0.2', 'feature', 'Új könyv hozzáadása oldal javítása', 0, '2026-07-07 04:14:17'),
(25, 4, '0.2', 'bugfix', 'Verziókezelés tesztelése.', 1, '2026-08-24 16:04:33');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- A tábla indexei `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `project_versions`
--
ALTER TABLE `project_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_versions_project` (`project_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `project_versions`
--
ALTER TABLE `project_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `project_versions`
--
ALTER TABLE `project_versions`
  ADD CONSTRAINT `fk_project_versions_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
