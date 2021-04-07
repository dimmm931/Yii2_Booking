-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 03 2020 г., 14:15
-- Версия сервера: 5.5.28-log
-- Версия PHP: 5.4.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `yii2_rest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `auth_assignment_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('adminX', '4', 1559130168),
('user', '9', 1560858002);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
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
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('adminX', 1, 'core RBAC role', NULL, NULL, 1559129811, 1559129811),
('user', 1, 'ground user role', NULL, NULL, 1559122354, 1559122354),
('user2', 1, 'some DB description', NULL, NULL, 1559738685, 1559738685),
('user8', 1, 'user 8 rbac', NULL, NULL, 1559738989, 1559738989),
('vamp', 1, 'random role', NULL, NULL, 1559903429, 1559903429);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
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
-- Структура таблицы `booking_cph`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

--
-- Дамп данных таблицы `booking_cph`
--

INSERT INTO `booking_cph` (`book_id`, `book_user`, `book_guest`, `book_from`, `book_to`, `book_from_unix`, `book_to_unix`) VALUES
(55, 'Dima', 'Fil2', '2019-12-27', '2019-12-28', 1577404800, 1577491200),
(56, 'Dima', 'Fil', '2019-09-27', '2019-09-28', 1569542400, 1569628800),
(58, 'Dima', 'Person1', '2019-08-21', '2019-08-27', 1566345600, 1566864000),
(65, 'Dima', 'vvvvv', '2019-08-09', '2019-08-10', 1565308800, 1565395200),
(66, 'Dima', 'Dima', '2020-01-02', '2020-01-10', 1577923200, 1578614400),
(67, 'Dima', 'Dima F', '2020-03-08', '2020-03-18', 1583625600, 1584489600),
(68, 'Dima', 'Dima', '2020-08-12', '2020-08-21', 1597190400, 1597968000),
(69, 'Tanya', 'Dima ZX', '2020-04-28', '2020-04-30', 1588032000, 1588204800),
(70, 'Dima', 'Dima F', '2020-04-15', '2020-04-17', 1586908800, 1587081600);

-- --------------------------------------------------------

--
-- Структура таблицы `booking_cph_v2_hotel`
--

CREATE TABLE IF NOT EXISTS `booking_cph_v2_hotel` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `booked_by_user` varchar(77) NOT NULL,
  `booked_guest` varchar(77) NOT NULL,
  `booked_guest_email` varchar(77) NOT NULL,
  `book_from` varchar(33) NOT NULL,
  `book_to` varchar(33) NOT NULL,
  `book_from_unix` int(11) NOT NULL,
  `book_to_unix` int(11) NOT NULL,
  `book_room_id` int(11) NOT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `booking_cph_v2_hotel`
--

INSERT INTO `booking_cph_v2_hotel` (`book_id`, `booked_by_user`, `booked_guest`, `booked_guest_email`, `book_from`, `book_to`, `book_from_unix`, `book_to_unix`, `book_room_id`) VALUES
(2, 'gg', 'gg', '', '2020-01-08', '2020-01-16', 1578441600, 1579132800, 1),
(3, 'vv', 'vv', '', '2020-03-08', '2020-03-18', 1583625600, 1584489600, 1),
(6, 'Dima', 'Dima', 'dim@gmail.com', '2020-07-01', '2020-07-03', 1593561600, 1593734400, 5),
(7, 'Dima', 'Dima', 'dim@gmail.com', '2020-05-07', '2020-05-09', 1588809600, 1588982400, 5),
(8, 'Dima', 'Dima', 'dim@gmail.com', '2020-01-22', '2020-01-25', 1579651200, 1579910400, 4),
(9, 'Dima', 'Dima', 'dim@gmail.com', '2019-12-20', '2019-12-27', 1576800000, 1577404800, 2),
(10, 'Dima', 'Dima', 'dim@gmail.com', '2020-01-01', '2020-01-05', 1577836800, 1578182400, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `bot`
--

CREATE TABLE IF NOT EXISTS `bot` (
  `b_id` int(11) NOT NULL AUTO_INCREMENT,
  `b_category` varchar(77) NOT NULL,
  `b_autocomplete` text NOT NULL,
  `b_key` text NOT NULL,
  `b_reply` text NOT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `bot`
--

INSERT INTO `bot` (`b_id`, `b_category`, `b_autocomplete`, `b_key`, `b_reply`) VALUES
(1, 'Statements', 'My name is //', '// name //names //', 'So delighted to meeet you // Nice to  to get acquainted // My pleasure to getin touch with you'),
(3, 'Questions', 'What is your name? // Who are you?', 'What is your name? // Who are you? // name?  // names? //surename //surenames?', 'You can call me Yii2 Bot // I am not too much about names // My name is Public Function actionAjaxReply() // Isn''t it too quick to get acquainted?'),
(4, 'Statements', 'Hello // Hi ', 'Hello // Hi // How do you do? // Good morning', 'Hello // Hi // How do you do? // Good morning // Good everning // Greetings // Hey, brother'),
(6, 'Statements', 'Let us talk about Copenhagen ', 'Let us talk about Copenhagen // Cph // Denmark', 'I like CPH // Copenhagen is a Scandinavian city // I like Rafshaleoen mostly // Tegneholmen is the pure waterfront experience // København was a Danish five-masted barque used as a naval training vessel until its disappearance after December 22, 1928. // København  is the capital and most populous city of Denmark. As of July 2018, the city has a population of 777,218 //  Copenhagen is situated on the eastern coast of the island of Zealand; another small portion of the city is located on Amager, and it is separated from Malmö, Sweden, by the strait of Øresund. The Øresund Bridge connects the two cities by rail and road. // Since the turn of the 21st century, Copenhagen has seen strong urban and cultural development, facilitated by investment in its institutions and infrastructure. The city is the cultural, economic and governmental centre of Denmark'),
(7, 'Statements', 'Dnb music', 'Dnb // drum''n''bass //Dnb music', 'Calyx and Teebee are remarkable producers'),
(8, 'Questions', 'Where do you live? ', 'Where do you live? // Where is your native place?', 'I live on Earth // I maintaine a residence nearby // I am bouncing around a lot // I am cosmopolitan // I used to shift many places'),
(9, 'Questions', 'Android or IOS?', 'Android or IOS?', 'Android rocks!!! // Of course Android // IOS sucks // Choose Android anyway'),
(10, 'Statements', 'Want to say goodbye', 'Want to say goodbye // good-bye // bye // see you later', 'Bye // See you later // Bye, come back later // Will be waiting for you to come back // Adieu // I will be looking forward to seeing you agian'),
(12, 'Statements', 'I am kind of tired', 'I am kind of tired //bored // sad', 'Cheers up // Grab some food // Some sleep will fix it // Some dance? // Some beer? // Take a walk to the sea front'),
(13, 'Questions', 'Plans for tomorrow?', 'Plans for tomorrow? //What are going to do?', 'Hit the city // Going to the countryside // Cycling around // Doing the gym // Improving the language'),
(16, 'Questions', 'Best slogan for today?', 'Best slogan for today? // Motto // Idea // Slogan  Mottos // Ideas // Slogans  Motto? // Idea? // Slogan?  Mottos? // Ideas? // Slogans?', 'Don''t let the perfect be the enemy of the good.//\r\nI’ve done it before and I can do it again.//\r\nA journey of a thousand miles begins with a single step. //\r\nHealth first.//\r\nExercise—stay stronger longer.//\r\nWhere there’s a will, there’s a way.//\r\nHe who has a why can endure any how.//\r\nMake the right thing to do the easy thing to do.//\r\nSmoke-free—a healthy me'),
(17, 'Questions', 'Tell me any useful proverb in English, will you?', 'Tell me any useful proverb in English, will you? // What is the best proverb in English? // proverb proverbs proverb? proverbs?', 'When in Rome, do as the Romans. //The squeaky wheel gets the grease. // When the going gets tough, the tough get going //No man is an island // Never look a gift horse in the mouth// You can''t make an omelet without breaking a few eggs //God helps those who help themselves// If it ain''t broke, don''t fix it // Too many cooks spoil the broth // Many hands make light work. // Many hands make light work. // Honesty is the best policy // Don’t make a mountain out of an anthill // Better late than never. // It’s better to be safe than sorry.'),
(18, 'Script_Processed', 'What time is it?', 'What time is it? // time // time? now //', 'Now it is '),
(19, 'Script_Processed', 'What day is today?', 'What day is today? // What day is today //  today // today?', 'Today is '),
(20, 'Script_Processed', 'Tell me the recent news', 'Tell me the recent news // news //news', 'News is: '),
(21, 'Script_Processed', 'What is the weather like now?', 'What is the weather like now? // What about weather? // What is the weather like now in EU? // forecast //rain // warm ', 'THIS ANSWERS ARE NOT USED, ANSWER IS CALCULATED FROM OPEN WEATHER API. THOUGH YOU CAN USE THESE ANSWERS, LIKE WE DO IN time/date(1d;18,19) // It is great weather today // It is windy today // it is raining // I like the weather we have today // It is sunny // It is stormy'),
(22, 'Script_Processed', 'Give me tomorrow weather forecast', 'Give me tomorrow weather forecast //  tomorrow weather forecast', 'THIS ANSWERS ARE NOT USED, ANSWER IS CALCULATED FROM OPEN WEATHER API. THOUGH YOU CAN USE THESE ANSWERS, LIKE WE DO IN time/date(1d;18,19)  //');

-- --------------------------------------------------------

--
-- Структура таблицы `liqpay_shop_simple`
--

CREATE TABLE IF NOT EXISTS `liqpay_shop_simple` (
  `l_id` int(11) NOT NULL AUTO_INCREMENT,
  `l_name` varchar(77) NOT NULL,
  `l_image` varchar(77) NOT NULL,
  `l_price` decimal(6,2) NOT NULL,
  `l_descript` text NOT NULL,
  PRIMARY KEY (`l_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `liqpay_shop_simple`
--

INSERT INTO `liqpay_shop_simple` (`l_id`, `l_name`, `l_image`, `l_price`, `l_descript`) VALUES
(0, 'Canon camera', 'canon.jpg', '16.64', '30 Mpx, 5kg'),
(1, 'HP notebook', 'hp.jpg', '35.31', '8Gb Ram, 500Gb SSD'),
(2, 'Iphone 3', 'iphone_3.jpg', '75.55', 'TFT capacitive touchscreen, 3.5 inches, 16M colors, 2 Mpx'),
(3, 'Iphone 5', 'iphone_5.jpg', '45.00', 'Iphone 5 description......'),
(4, 'Ipod', 'ipod_classic_3.jpg', '2.66', 'Ipod description....'),
(5, 'Samsung Sync', 'samsung_sync.jpg', '18.96', 'Samsung Sync description...');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1558606480),
('m140506_102106_rbac_init', 1559121378),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1559121378),
('m190523_102536_create_user_table', 1558607193);

-- --------------------------------------------------------

--
-- Структура таблицы `rest_access_tokens`
--

CREATE TABLE IF NOT EXISTS `rest_access_tokens` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `rest_tokens` varchar(88) NOT NULL,
  `r_user` int(11) NOT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `rest_access_tokens`
--

INSERT INTO `rest_access_tokens` (`r_id`, `rest_tokens`, `r_user`) VALUES
(1, 'gXupcWw8I4u5oiKyFfsMCTVzq_RwWFb-', 4),
(2, '57Wpa-dlg-EonG6kB3myfsEjpo7v8R5b', 4),
(3, 'LmToPxeUjgx0sC6CwFljaE2PLfTQu2Fz', 4),
(4, 'Y3zyIa_Rj___8ZVTSehN2nr1OxdlTFiV', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `test_form`
--

CREATE TABLE IF NOT EXISTS `test_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name1` varchar(66) NOT NULL,
  `name2` varchar(66) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `test_form`
--

INSERT INTO `test_form` (`id`, `name1`, `name2`) VALUES
(1, 'dim', 'f'),
(2, 'name1', 'f');

-- --------------------------------------------------------

--
-- Структура таблицы `test_for_middle_regist_token`
--

CREATE TABLE IF NOT EXISTS `test_for_middle_regist_token` (
  `test_middle_id` int(11) NOT NULL AUTO_INCREMENT,
  `test_middle_email` varchar(77) NOT NULL,
  `test_middle_regist_token` varchar(77) NOT NULL,
  PRIMARY KEY (`test_middle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `access_token`) VALUES
(3, 'admin', 'rpWTxyZV1Oaafv60zWyEaMRoDCOs2S_V', '$2y$13$k8vVzc3Jw23l/TQmqkorEeP9n7.IUu1a7Mmmq.LZ.1AdfhE3xtumC', NULL, 'admin@кодер.укр', 10, 1558954790, 1558954790, 0),
(4, 'Dima', 'DwDx9pzGrmDIwtHLNpQkyTOKoaGqw_aF', '$2y$13$B0eZsmSAF7rFvhs9lG8hwuLv53SfqzwOLYtLoNPJX92XUOuKmwqvy', NULL, 'dima@ukr.net', 10, 1558955248, 1558955248, 1111),
(9, 'Dimakk', 'DwDx9pzGrmDIwtHLNpQkyTOKoaGqw_aF', '$2y$13$B0eZsmSAF7rFvhs9lG8hwuLv53SfqzwOLYtLoNPJX92XUOuKmwqvy', NULL, 'dimhhha@ukr.net', 10, 1558955248, 1558955248, 0),
(10, 'olya', '-JR3easZZr4RgmPlPpeS-aPe1R53fOJK', '$2y$13$39aqtKjIbSHJ0UF5RnpzlOS1a9kHa0T.lOn0pnDpQgndtNTq.c7I2', 'TQ54N3hxSR5tAsZ5CA5Y0ykUHgXJcab8_1567766765', 'accou@ukr.net', 10, 1560869824, 1567766766, 0),
(14, 'dddd', 'YXpxw30KQjxvTf5-cxuvU1NEGDK3348-', '$2y$13$jBwyNBU.QjCd8NGBvyDe4eAuGyrAkTzRLDxdK7EVEdl4RYTfcguq6', NULL, 'dima@ukr.netf', 10, 1576508843, 1576508843, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `wpress_blog_post`
--

CREATE TABLE IF NOT EXISTS `wpress_blog_post` (
  `wpBlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `wpBlog_title` varchar(222) NOT NULL,
  `wpBlog_text` text NOT NULL,
  `wpBlog_author` int(11) NOT NULL,
  `wpBlog_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wpBlog_category` int(11) NOT NULL,
  `wpBlog_status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`wpBlog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `wpress_blog_post`
--

INSERT INTO `wpress_blog_post` (`wpBlog_id`, `wpBlog_title`, `wpBlog_text`, `wpBlog_author`, `wpBlog_created_at`, `wpBlog_category`, `wpBlog_status`) VALUES
(1, 'Setting  Enum in PhpMyAdmin', 'Setting  Enum in SQL\r\nunder your phpmyadmin\r\n\r\nchoose enum\r\n\r\nin Length/Values column put there : ''0'' ,''1''\r\n\r\nand your done', 4, '2019-11-06 10:36:29', 1, '1'),
(2, 'Milgram experiment', 'The Milgram experiment on obedience to authority figures was a series of social psychology experiments conducted by Yale University psychologist Stanley Milgram. They measured the willingness of study participants, men from a diverse range of occupations with varying levels of education, to obey an authority figure who instructed them to perform acts conflicting with their personal conscience. Participants were led to believe that they were assisting an unrelated experiment, in which they had to administer electric shocks to a "learner." These fake electric shocks gradually increased to levels that would have been fatal had they been real.[2]', 4, '2019-11-06 10:37:52', 3, '1'),
(3, 'Milgram results', 'The extreme willingness of adults to go to almost any lengths on the command of an authority constitutes the chief finding of the study and the fact most urgently demanding explanation.\r\n\r\nOrdinary people, simply doing their jobs, and without any particular hostility on their part, can become agents in a terrible destructive process. Moreover, even when the destructive effects of their work become patently clear, and they are asked to carry out actions incompatible with fundamental standards of morality, relatively few people have the resources needed to resist authority.[10]', 4, '2019-11-06 10:39:18', 2, '1'),
(4, 'Как вывести результаты связи HasMany', 'Получаю ошибку "Trying to get property of non-object."<br>\r\nКак теперь правильно выводить\r\nполучить объект для начала\r\n<br>\r\nЯ же все это вроде описывал, ну да ладно:<br>\r\n1. Переделайте реляцию под более правильно название:\r\n<br>\r\n1\r\npublic function getTimes();\r\n<br>\r\n2. Осознаем то, что тут перебираем массив тасков, но в реляции тоже лежит массив!\r\n\r\n<br>\r\n<code>\r\nforeach ($tasks as $task): \r\n<br>\r\n  <tr>\r\n \r\n    <td height="40" class="tskdetails"> Начало выполнения : \r\n<br><?php   echo "<br>".$task->time->start; ?> </td>\r\n        </tr>\r\n\r\n</code>\r\n<br>\r\n3. Когда мы все это осознали, исправляем косяки\r\n<br>\r\n\r\n<code>\r\n<?php foreach ($tasks as $task): ?>\r\n<br>\r\n  <tr>\r\n       <?php foreach ($task->times as $time): ?> //loop array times\r\n<br>\r\n            <td height="40" class="tskdetails"> Начало выполнения : <?= ''<br>'', $time->start; ?> </td>\r\n        </tr>\r\n</code>\r\n<br>\r\n<a href="http://www.cyberforum.ru/php-yii/thread2313064.html">Source link</a>', 4, '2019-11-06 13:39:18', 3, '1');

-- --------------------------------------------------------

--
-- Структура таблицы `wpress_category`
--

CREATE TABLE IF NOT EXISTS `wpress_category` (
  `wpCategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `wpCategory_name` varchar(77) NOT NULL,
  PRIMARY KEY (`wpCategory_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `wpress_category`
--

INSERT INTO `wpress_category` (`wpCategory_id`, `wpCategory_name`) VALUES
(1, 'General'),
(2, 'Science'),
(3, 'Tips and Tricks');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
