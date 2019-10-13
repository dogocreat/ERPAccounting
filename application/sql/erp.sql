-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3306
-- 產生時間： 2019-10-13 15:34:44
-- 伺服器版本： 5.7.26
-- PHP 版本： 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `erp`
--
CREATE DATABASE IF NOT EXISTS `erp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp`;

-- --------------------------------------------------------

--
-- 資料表結構 `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_address` text COLLATE utf8mb4_unicode_ci,
  `send_address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tick_title` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tick_address` text COLLATE utf8mb4_unicode_ci,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客戶表';

-- --------------------------------------------------------

--
-- 資料表結構 `instock`
--

DROP TABLE IF EXISTS `instock`;
CREATE TABLE IF NOT EXISTS `instock` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `instock_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int(10) NOT NULL,
  `instock_price` double(32,2) DEFAULT '0.00',
  `tax_price` double(32,2) DEFAULT '0.00',
  `back_price` double(32,2) DEFAULT '0.00',
  `payable_price` double(32,2) DEFAULT '0.00',
  `payabled_price` double(32,2) DEFAULT '0.00',
  `unpayable_price` double(32,2) DEFAULT '0.00',
  `instock_date` date NOT NULL DEFAULT '1970-01-01',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `instock_id` (`instock_id`) USING BTREE,
  KEY `instock_date` (`instock_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='進貨單主表';

-- --------------------------------------------------------

--
-- 資料表結構 `instock_detail`
--

DROP TABLE IF EXISTS `instock_detail`;
CREATE TABLE IF NOT EXISTS `instock_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `instock_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_id` int(10) NOT NULL,
  `qty` double(32,2) NOT NULL,
  `price` double(32,2) NOT NULL,
  `unit` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `instock_id` (`instock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='進貨明細表';

-- --------------------------------------------------------

--
-- 資料表結構 `outstock`
--

DROP TABLE IF EXISTS `outstock`;
CREATE TABLE IF NOT EXISTS `outstock` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `outstock_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(10) NOT NULL,
  `outstock_price` double(32,2) DEFAULT '0.00',
  `tax_price` double(32,2) DEFAULT '0.00',
  `back_price` double(32,2) DEFAULT '0.00',
  `receivable_price` double(32,2) DEFAULT '0.00',
  `receivabled_price` double(32,2) DEFAULT '0.00',
  `unreceivable_price` double(32,2) DEFAULT '0.00',
  `outstock_date` date NOT NULL DEFAULT '1970-01-01',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `outstock_id` (`outstock_id`) USING BTREE,
  KEY `outstock_date` (`outstock_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='銷貨單主表';

-- --------------------------------------------------------

--
-- 資料表結構 `outstock_detail`
--

DROP TABLE IF EXISTS `outstock_detail`;
CREATE TABLE IF NOT EXISTS `outstock_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `outstock_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_id` int(10) NOT NULL,
  `qty` double(32,2) NOT NULL,
  `price` double(32,2) NOT NULL,
  `unit` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `outstock_id` (`outstock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='銷貨明細表';

-- --------------------------------------------------------

--
-- 資料表結構 `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `now_stock` double(32,2) DEFAULT '0.00',
  `safe_stock` double(32,2) DEFAULT '0.00',
  `total_stock` double(128,2) DEFAULT '0.00',
  `avg_price` double(64,2) DEFAULT '0.00',
  `sale_price` double(32,2) DEFAULT '0.00',
  `create_time` datetime NOT NULL,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='庫存表';

-- --------------------------------------------------------

--
-- 資料表結構 `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci,
  `company_phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_email` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='廠商表';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
