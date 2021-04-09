-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 09 2021 г., 13:15
-- Версия сервера: 10.1.38-MariaDB
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii2_booking`
--

-- --------------------------------------------------------

--
-- Структура таблицы `booking_cph_v2_hotel`
--

CREATE TABLE `booking_cph_v2_hotel` (
  `book_id` int(11) NOT NULL,
  `booked_by_user` int(11) NOT NULL,
  `booked_guest` varchar(77) NOT NULL,
  `booked_guest_email` varchar(77) NOT NULL,
  `book_from` varchar(33) NOT NULL,
  `book_to` varchar(33) NOT NULL,
  `book_from_unix` int(11) NOT NULL,
  `book_to_unix` int(11) NOT NULL,
  `book_room_id` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `booking_cph_v2_hotel`
--

INSERT INTO `booking_cph_v2_hotel` (`book_id`, `booked_by_user`, `booked_guest`, `booked_guest_email`, `book_from`, `book_to`, `book_from_unix`, `book_to_unix`, `book_room_id`, `createdAt`, `updatedAt`) VALUES
(2, 1, 'Dima', 'dima@ukr.net', '2021-04-19', '2021-04-21', 1618790400, 1618963200, 1, '2021-04-08 12:40:44', '0000-00-00 00:00:00'),
(3, 1, 'Dima', 'dima@ukr.net', '2021-04-24', '2021-04-26', 1619222400, 1619395200, 1, '2021-04-08 12:41:12', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1617885393),
('m190523_102536_create_user_table', 1617885399),
('m210408_101546_create_booking_cph_v2_hotel_table', 1617885400);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'dima', '', '$2y$13$nq.EvB68H59sz3zXyszTZeQLl.c/WJqhDq4kIQh678u9Qbl7wCAb2', NULL, 'dima@ukr.net', 10, 0, 0),
(2, 'test', '', '$2y$13$xfA3DQfq560lUh4Q.OfU4.eKPx6jQUILERXboU63LUknua7t3kdgO', NULL, 'test@ukr.net', 10, 0, 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `booking_cph_v2_hotel`
--
ALTER TABLE `booking_cph_v2_hotel`
  ADD PRIMARY KEY (`book_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `booking_cph_v2_hotel`
--
ALTER TABLE `booking_cph_v2_hotel`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
