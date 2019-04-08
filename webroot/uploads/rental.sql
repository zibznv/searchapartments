-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.38-log - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных rental
CREATE DATABASE IF NOT EXISTS `rental` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `rental`;

-- Дамп структуры для таблица rental.apartment
CREATE TABLE IF NOT EXISTS `apartment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numberOnDoor` char(10) NOT NULL DEFAULT '0' COMMENT 'Номер на входной двери',
  `nameСomplexORBI` char(50) NOT NULL DEFAULT '0' COMMENT 'Название комплекса ORBI',
  `typeApartment` char(30) NOT NULL DEFAULT '0' COMMENT 'Тип апартаментов',
  `townLocation` char(30) NOT NULL DEFAULT '0' COMMENT 'Город расположения',
  `addressComplex` char(50) NOT NULL DEFAULT '0' COMMENT 'Адрес комплекса',
  `squareApartment` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Площадь номера м2',
  `countRooms` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Количество комнат',
  `ownerID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID владельца номера',
  `statusApartment` char(20) NOT NULL DEFAULT '0' COMMENT 'Рабочий статус номера',
  `numberSeats` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Количество мест в номере',
  `coordinates` char(50) DEFAULT '0' COMMENT 'GPS координаты комплекса',
  `pathFoto` char(255) NOT NULL DEFAULT '0' COMMENT 'Путь к фотокаталогу',
  `balancePayments` float NOT NULL DEFAULT '0' COMMENT 'Баланс оплат по номеру',
  `commentApartment` char(255) DEFAULT '0' COMMENT 'Комментарии и пожелания',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numberOnDoor_nameСomplexORBI` (`numberOnDoor`,`nameСomplexORBI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Описание номера';

-- Дамп данных таблицы rental.apartment: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `apartment` DISABLE KEYS */;
/*!40000 ALTER TABLE `apartment` ENABLE KEYS */;

-- Дамп структуры для таблица rental.costApartment
CREATE TABLE IF NOT EXISTS `costApartment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apartmentID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID апартаментов в базе данных',
  `januaryCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи январь',
  `februaryCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи февраль',
  `marchCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи март',
  `aprilCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи апрель',
  `mayCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи май',
  `juneCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи июнь',
  `julyCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи июль',
  `augustCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи август',
  `septemberCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи сентябрь',
  `octoberCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи октябрь',
  `novemberCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи ноябрь',
  `decemberCost` float unsigned DEFAULT '0' COMMENT 'Стоимость сдачи декабрь',
  PRIMARY KEY (`id`),
  UNIQUE KEY `apartmentID` (`apartmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Стоимость апартаментов по месяцам';

-- Дамп данных таблицы rental.costApartment: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `costApartment` DISABLE KEYS */;
/*!40000 ALTER TABLE `costApartment` ENABLE KEYS */;

-- Дамп структуры для таблица rental.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userCustomer` char(30) NOT NULL DEFAULT '0' COMMENT 'Юзер клиента',
  `passwordCustomer` char(30) NOT NULL DEFAULT '0' COMMENT 'Пароль клиента',
  `nameCustomer` char(50) NOT NULL DEFAULT '0' COMMENT 'Имя клиента',
  `surnameCustomer` char(50) NOT NULL DEFAULT '0' COMMENT 'Фамилия клиента',
  `statusCustomer` char(30) DEFAULT '0' COMMENT 'Статус клиента',
  `typeCustomer` char(30) DEFAULT '0' COMMENT 'Тип клиента',
  `phoneCustomer` char(50) NOT NULL DEFAULT '0' COMMENT 'Телефон клиента',
  `emailCustomer` char(50) NOT NULL DEFAULT '0' COMMENT 'Электронная почта клиента',
  `bankcardNumber` char(50) DEFAULT '0' COMMENT 'Номер банковской карты',
  `accountBalance` float NOT NULL DEFAULT '0' COMMENT 'Бухгалтерский баланс',
  `genderCustomer` char(10) NOT NULL DEFAULT '0' COMMENT 'Пол клиента',
  `birthDate` date DEFAULT NULL COMMENT 'Дата рождения клиента',
  `photoCustomer` char(100) DEFAULT NULL COMMENT 'Имя файла фото',
  `commentCustomer` char(255) DEFAULT NULL COMMENT 'Комментарии по клиенту',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phoneCustomer_emailCustomer_bankcardNumber` (`phoneCustomer`,`emailCustomer`,`bankcardNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица клиентов и партнеров';

-- Дамп данных таблицы rental.customer: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Дамп структуры для таблица rental.order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateOrder` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время отправки заказа',
  `town` char(20) NOT NULL DEFAULT 'Batumi' COMMENT 'Город заказа',
  `dateArrival` date NOT NULL COMMENT 'Дата прибытия',
  `dateDepartures` date NOT NULL COMMENT 'Дата выезда',
  `seatCount` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT 'Необходимое количество мест',
  `roomCount` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Необходимое количество комнат',
  `typeApartment` char(30) NOT NULL DEFAULT 'Standart' COMMENT 'Необходимый тип номера',
  `idCustomer` int(10) unsigned DEFAULT NULL COMMENT 'ID клиента после регистрации или авторизации',
  `idPartner` int(10) unsigned DEFAULT NULL COMMENT 'ID партнера после согласования заказа',
  `textAlertSent` char(255) DEFAULT NULL COMMENT 'Текст посланного партнеру сообщения',
  `dateConfirmed` timestamp NULL DEFAULT NULL COMMENT 'Дата подтверждения заявки на аренду',
  `servicesAmount` float unsigned DEFAULT NULL COMMENT 'Сумма оплаты от партнера',
  `paymentInformation` char(255) DEFAULT NULL COMMENT 'Информация по оплате',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица запросов на поиск вариантов от клиентов';

-- Дамп данных таблицы rental.order: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
