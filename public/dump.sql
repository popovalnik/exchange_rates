-- --------------------------------------------------------
-- Хост:                         popovalnik.myjino.ru
-- Версия сервера:               10.3.27-MariaDB-log - MariaDB Server
-- Операционная система:         Linux
-- HeidiSQL Версия:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных popovalnik_ex
CREATE DATABASE IF NOT EXISTS `popovalnik_ex` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `popovalnik_ex`;

-- Дамп структуры для таблица popovalnik_ex.sp_rates
CREATE TABLE IF NOT EXISTS `sp_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `engname` varchar(255) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.

-- Дамп структуры для таблица popovalnik_ex.valutes
CREATE TABLE IF NOT EXISTS `valutes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d` date DEFAULT NULL,
  `rate` varchar(50) DEFAULT NULL,
  `charcode` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `vr` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `d_rate` (`d`,`rate`)
) ENGINE=InnoDB AUTO_INCREMENT=3226 DEFAULT CHARSET=utf8;

-- Экспортируемые данные не выделены.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
