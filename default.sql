-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 14 2016 г., 13:48
-- Версия сервера: 5.5.53-0+deb8u1
-- Версия PHP: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `admin_pf_ca`
--

-- --------------------------------------------------------

--
-- Структура таблицы `article`
--

CREATE TABLE IF NOT EXISTS `article` (
`id` int(11) NOT NULL,
  `cat_id` int(3) NOT NULL,
  `date` datetime NOT NULL,
  `url_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `text` mediumtext NOT NULL,
  `main_img` varchar(150) NOT NULL,
  `donor` varchar(255) NOT NULL DEFAULT '',
  `donor_id` int(11) NOT NULL DEFAULT '100',
  `scan_url_id` int(11) NOT NULL,
  `author_id` int(6) NOT NULL DEFAULT '0',
  `canonical` varchar(700) NOT NULL,
  `views` int(9) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

--
-- Структура таблицы `articles_donor_url`
--

CREATE TABLE IF NOT EXISTS `articles_donor_url` (
`id` int(11) NOT NULL,
  `cat_id` int(3) NOT NULL,
  `donor_id` int(11) NOT NULL DEFAULT '1',
  `scan_sort` int(11) NOT NULL DEFAULT '1',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `scan_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_like_id`
--

CREATE TABLE IF NOT EXISTS `article_like_id` (
  `article_id` int(11) NOT NULL,
  `like_id` varchar(200) NOT NULL,
  `upd_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `article_like_serp`
--

CREATE TABLE IF NOT EXISTS `article_like_serp` (
  `article_id` int(11) NOT NULL,
  `serp_object` text NOT NULL,
  `serp_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `article_top`
--

CREATE TABLE IF NOT EXISTS `article_top` (
`id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rank` int(3) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE IF NOT EXISTS `author` (
`id` int(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `img` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(3) NOT NULL,
  `parent_id` int(3) NOT NULL,
  `sub_cat_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timeset_tpl_id` int(11) NOT NULL DEFAULT '0',
  `img` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(3) NOT NULL,
  `url_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `full_uri` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category_timeset`
--

CREATE TABLE IF NOT EXISTS `category_timeset` (
`id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `cache_time_main_page_m` int(11) DEFAULT '10',
  `cache_time_top_slider_m` int(11) NOT NULL,
  `cache_time_right_last_news_m` int(11) NOT NULL DEFAULT '5',
  `like_news_day_d` int(11) NOT NULL,
  `like_news_cache_h` int(11) NOT NULL,
  `like_news_cache_for_old_h` int(11) NOT NULL,
  `top_news_time_h` int(11) NOT NULL,
  `right_top_news_time_h` int(11) NOT NULL DEFAULT '24'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `category_timeset`
--

INSERT INTO `category_timeset` (`id`, `name`, `description`, `cache_time_main_page_m`, `cache_time_top_slider_m`, `cache_time_right_last_news_m`, `like_news_day_d`, `like_news_cache_h`, `like_news_cache_for_old_h`, `top_news_time_h`, `right_top_news_time_h`) VALUES
(1, 'Default', 'Default', 10, 20, 10, 10, 1, 1440, 72, 72),
(2, 'auto', 'Новости / Авто', 10, 60, 10, 90, 240, 2400, 2400, 720),
(3, 'news-science', 'Новости / Наука и Технологии', 10, 60, 10, 60, 240, 2400, 720, 72),
(4, 'show-biz', 'Новости / Культура & ШоуБиз', 10, 60, 10, 30, 240, 2400, 240, 72),
(5, 'news-main', 'Новости Главная', 10, 20, 10, 10, 1, 1440, 48, 72),
(6, 'lifestyle-main', 'Life Style - Главная', 60, 60, 30, 90, 720, 2400, 2160, 720),
(7, 'lifestyle-style', 'Life Style - Стиль', 60, 60, 30, 30, 240, 2400, 720, 720),
(8, 'finance-main', 'Финансы - Главная', 30, 30, 10, 15, 10, 1440, 72, 72),
(9, 'sport', 'Новости - Спорт', 10, 30, 10, 15, 3, 1440, 72, 72);

-- --------------------------------------------------------

--
-- Структура таблицы `donor`
--

CREATE TABLE IF NOT EXISTS `donor` (
`id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `donor`
--

INSERT INTO `donor` (`id`, `name`, `host`, `img`, `upd`) VALUES
(1, 'MSN', 'www.msn.com', 'www.msn.com.png', '2016-11-13 22:49:38');

-- --------------------------------------------------------

--
-- Структура таблицы `donor_domain`
--

CREATE TABLE IF NOT EXISTS `donor_domain` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cy` int(11) NOT NULL,
  `pr` int(1) NOT NULL DEFAULT '0',
  `pr_check` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `work` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `donor_redirect`
--

CREATE TABLE IF NOT EXISTS `donor_redirect` (
  `url` varchar(255) NOT NULL,
  `url_redirect` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `donor_subdomain`
--

CREATE TABLE IF NOT EXISTS `donor_subdomain` (
`id` int(11) NOT NULL,
  `donor_domain_id` int(11) NOT NULL,
  `subname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hosting` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
`id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `scan_url`
--

CREATE TABLE IF NOT EXISTS `scan_url` (
`id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` varchar(700) NOT NULL,
  `main_img_url` varchar(700) NOT NULL,
  `donor_url_id` varchar(50) NOT NULL DEFAULT '',
  `cat_id` int(3) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `scan` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `shingles`
--

CREATE TABLE IF NOT EXISTS `shingles` (
`id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `youtube_like`
--

CREATE TABLE IF NOT EXISTS `youtube_like` (
`id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `video_id` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `article`
--
ALTER TABLE `article`
 ADD PRIMARY KEY (`id`), ADD KEY `cat_id` (`cat_id`), ADD KEY `date` (`date`), ADD KEY `main_img` (`main_img`), ADD FULLTEXT KEY `title` (`title`,`text`);

--
-- Индексы таблицы `articles_donor_url`
--
ALTER TABLE `articles_donor_url`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `article_like_id`
--
ALTER TABLE `article_like_id`
 ADD PRIMARY KEY (`article_id`);

--
-- Индексы таблицы `article_like_serp`
--
ALTER TABLE `article_like_serp`
 ADD PRIMARY KEY (`article_id`), ADD KEY `serp_update` (`serp_update`);

--
-- Индексы таблицы `article_top`
--
ALTER TABLE `article_top`
 ADD PRIMARY KEY (`id`), ADD KEY `ip_artid` (`ip`,`article_id`);

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category_timeset`
--
ALTER TABLE `category_timeset`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `donor`
--
ALTER TABLE `donor`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `donor_domain`
--
ALTER TABLE `donor_domain`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `donor_redirect`
--
ALTER TABLE `donor_redirect`
 ADD PRIMARY KEY (`url`);

--
-- Индексы таблицы `donor_subdomain`
--
ALTER TABLE `donor_subdomain`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `scan_url`
--
ALTER TABLE `scan_url`
 ADD PRIMARY KEY (`id`), ADD KEY `url` (`url`(333));

--
-- Индексы таблицы `shingles`
--
ALTER TABLE `shingles`
 ADD PRIMARY KEY (`id`), ADD KEY `hash` (`hash`), ADD KEY `article_id` (`article_id`);

--
-- Индексы таблицы `youtube_like`
--
ALTER TABLE `youtube_like`
 ADD PRIMARY KEY (`id`), ADD KEY `article_id` (`article_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `article`
--
ALTER TABLE `article`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `articles_donor_url`
--
ALTER TABLE `articles_donor_url`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `article_top`
--
ALTER TABLE `article_top`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `category_timeset`
--
ALTER TABLE `category_timeset`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `donor`
--
ALTER TABLE `donor`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `donor_domain`
--
ALTER TABLE `donor_domain`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `donor_subdomain`
--
ALTER TABLE `donor_subdomain`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `scan_url`
--
ALTER TABLE `scan_url`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `shingles`
--
ALTER TABLE `shingles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `youtube_like`
--
ALTER TABLE `youtube_like`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
