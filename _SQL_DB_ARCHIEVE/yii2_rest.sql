-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Час створення: Вер 25 2019 р., 18:30
-- Версія сервера: 5.5.28-log
-- Версія PHP: 5.4.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `yii2_rest`
--

-- --------------------------------------------------------

--
-- Структура таблиці `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('adminX', '4', 1559130168),
('user', '9', 1560858002);

-- --------------------------------------------------------

--
-- Структура таблиці `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('adminX', 1, 'core RBAC role', NULL, NULL, 1559129811, 1559129811),
('user', 1, 'ground user role', NULL, NULL, 1559122354, 1559122354),
('user2', 1, 'some DB description', NULL, NULL, 1559738685, 1559738685),
('user8', 1, 'user 8 rbac', NULL, NULL, 1559738989, 1559738989),
('vamp', 1, 'random role', NULL, NULL, 1559903429, 1559903429);

-- --------------------------------------------------------

--
-- Структура таблиці `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `booking_cph`
--

CREATE TABLE IF NOT EXISTS `booking_cph` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_user` varchar(77) NOT NULL,
  `book_guest` varchar(77) NOT NULL,
  `book_from` varchar(33) NOT NULL,
  `book_to` varchar(33) NOT NULL,
  `book_from_unix` int(11) NOT NULL,
  `book_to_unix` int(11) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Дамп даних таблиці `booking_cph`
--

INSERT INTO `booking_cph` (`book_id`, `book_user`, `book_guest`, `book_from`, `book_to`, `book_from_unix`, `book_to_unix`) VALUES
(55, 'Dima', 'Fil2', '2019-12-27', '2019-12-28', 1577404800, 1577491200),
(56, 'Dima', 'Fil', '2019-09-27', '2019-09-28', 1569542400, 1569628800),
(58, 'Dima', 'Person1', '2019-08-21', '2019-08-27', 1566345600, 1566864000),
(65, 'Dima', 'vvvvv', '2019-08-09', '2019-08-10', 1565308800, 1565395200);

-- --------------------------------------------------------

--
-- Структура таблиці `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1558606480),
('m140506_102106_rbac_init', 1559121378),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1559121378),
('m190523_102536_create_user_table', 1558607193);

-- --------------------------------------------------------

--
-- Структура таблиці `rest_access_tokens`
--

CREATE TABLE IF NOT EXISTS `rest_access_tokens` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `rest_tokens` varchar(88) NOT NULL,
  `r_user` int(11) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп даних таблиці `rest_access_tokens`
--

INSERT INTO `rest_access_tokens` (`r_id`, `rest_tokens`, `r_user`) VALUES
(1, 'gXupcWw8I4u5oiKyFfsMCTVzq_RwWFb-', 4),
(2, '57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b', 4),
(3, 'LmToPxeUjgx0sC6CwFljaE2PLfTQu2Fz', 4),
(4, 'Y3zyIa_Rj___8ZVTSehN2nr1OxdlTFiV', 4);

-- --------------------------------------------------------

--
-- Структура таблиці `test_form`
--

CREATE TABLE IF NOT EXISTS `test_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(66) NOT NULL,
  `name2` varchar(66) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `test_form`
--

INSERT INTO `test_form` (`id`, `name1`, `name2`) VALUES
(1, 'dim', 'f');

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `access_token` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `access_token`) VALUES
(3, 'admin', 'rpWTxyZV1Oaafv60zWyEaMRoDCOs2S_V', '$2y$13$k8vVzc3Jw23l/TQmqkorEeP9n7.IUu1a7Mmmq.LZ.1AdfhE3xtumC', NULL, 'admin@кодер.укр', 10, 1558954790, 1558954790, 0),
(4, 'Dima', 'DwDx9pzGrmDIwtHLNpQkyTOKoaGqw_aF', '$2y$13$B0eZsmSAF7rFvhs9lG8hwuLv53SfqzwOLYtLoNPJX92XUOuKmwqvy', NULL, 'dima@ukr.net', 10, 1558955248, 1558955248, 1111),
(9, 'Dimakk', 'DwDx9pzGrmDIwtHLNpQkyTOKoaGqw_aF', '$2y$13$B0eZsmSAF7rFvhs9lG8hwuLv53SfqzwOLYtLoNPJX92XUOuKmwqvy', NULL, 'dimhhha@ukr.net', 10, 1558955248, 1558955248, 0),
(10, 'olya', '-JR3easZZr4RgmPlPpeS-aPe1R53fOJK', '$2y$13$39aqtKjIbSHJ0UF5RnpzlOS1a9kHa0T.lOn0pnDpQgndtNTq.c7I2', 'TQ54N3hxSR5tAsZ5CA5Y0ykUHgXJcab8_1567766765', 'account931@ukr.net', 10, 1560869824, 1567766766, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
