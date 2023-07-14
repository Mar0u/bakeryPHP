-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Sty 2023, 20:14
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bakery`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logged_in_users`
--

CREATE TABLE `logged_in_users` (
  `sessionId` varchar(100) NOT NULL,
  `userId` int(11) NOT NULL,
  `lastUpdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product0` int(11) NOT NULL DEFAULT 0,
  `product1` int(11) NOT NULL DEFAULT 0,
  `product2` int(11) NOT NULL DEFAULT 0,
  `info` varchar(255) DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `realisation_date` datetime DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product0`, `product1`, `product2`, `info`, `order_date`, `realisation_date`, `status`) VALUES
(73, 44, 4, 2, 4, '', '2023-01-21 10:16:16', '2023-01-26 00:00:00', 2),
(74, 44, 0, 3, 0, '', '2023-01-22 20:36:56', '2023-01-23 00:00:00', 2),
(75, 44, 1, 0, 1, 'Double chocolate', '2023-01-24 20:38:40', '2023-01-27 00:00:00', 1),
(76, 44, 1, 0, 1, '', '2023-01-25 20:38:51', '2023-01-29 00:00:00', 0),
(77, 44, 1, 0, 1, '', '2023-01-17 20:43:25', '2023-01-19 00:00:00', 3),
(78, 45, 3, 20, 3, '', '2023-01-24 20:46:09', '2023-01-27 00:00:00', 1),
(79, 45, 7, 0, 7, '', '2023-01-23 11:00:21', '2023-01-26 00:00:00', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `userName`, `fullName`, `email`, `passwd`, `status`, `date`) VALUES
(43, 'admin', 'Jan Kowal', 'jankowal@mail.com', '$2y$10$k8HF0JFdP/I1HST6T8BQAupXSdvZIwvzdIm2o4tvr0o26UbEF.fU6', 2, '2023-01-24 00:00:00'),
(44, 'user1', 'Anna Maj', 'anna@maj.pl', '$2y$10$5LPcFMnWzR22siQUY40s.u/B3Pw.SI3UVxhBJfKoKc6y.NIZqKkgu', 1, '2023-01-24 00:00:00'),
(45, 'user2', 'Krzysztof Malinowski', 'krzysio@mail.com', '$2y$10$tKEzaqs6gMc0UxUN6iNp6OtNQMeVLuUczNvWLJ0VowFiJ0kMYXJD.', 1, '2023-01-24 00:00:00'),
(46, 'user3', 'Maria Jakaś', 'maria@gmail.com', '$2y$10$oLe0M99V2uUI4g807dCb2ev2o146QA5z.huuCj2bVE6jwotIcAmoG', 1, '2023-01-24 00:00:00');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `logged_in_users`
--
ALTER TABLE `logged_in_users`
  ADD PRIMARY KEY (`sessionId`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userName` (`userName`,`email`),
  ADD UNIQUE KEY `userName_2` (`userName`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
