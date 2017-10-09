/*
SQLyog Ultimate v9.63 
MySQL - 5.5.43-0ubuntu0.14.04.1 : Database - block-explorer
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`blockchain` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `blockchain`;

/*Table structure for table `address_tags` */

DROP TABLE IF EXISTS `address_tags`;

CREATE TABLE `address_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(64) NOT NULL,
  `tag` varchar(64) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `time_created` int(11) DEFAULT NUlL,
  `time_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallet` (`address`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `addresses` */

DROP TABLE IF EXISTS `addresses`;

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(64) DEFAULT NULL,
  `value` decimal(18,8) DEFAULT NULL,
  `balance` decimal(18,8) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `txid` varchar(64) DEFAULT NULL,
  `block_height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address` (`address`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `blocks` */

DROP TABLE IF EXISTS `blocks`;

CREATE TABLE `blocks` (
  `height` int(11) NOT NULL,
  `hash` varchar(64) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `merkleroot` varchar(64) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `nonce` bigint(17) DEFAULT NULL,
  `bits` varchar(16) DEFAULT NULL,
  `difficulty` decimal(18,8) DEFAULT NULL,
  `mint` decimal(18,8) DEFAULT NULL,
  `previousblockhash` varchar(64) DEFAULT NULL,
  `nextblockhash` varchar(64) DEFAULT NULL,
  `flags` varchar(64) DEFAULT NULL,
  `proofhash` varchar(64) DEFAULT NULL,
  `entropybit` int(1) DEFAULT NULL,
  `modifier` varchar(16) DEFAULT NULL,
  `modifierchecksum` varchar(8) DEFAULT NULL,
  `raw` mediumtext,
  `transactions` int(11) DEFAULT NULL,
  `valueout` decimal(18,8) DEFAULT NULL,
  `valuein` decimal(18,8) DEFAULT NULL,
  `outstanding` decimal(18,8) DEFAULT NULL,
  `txFees` decimal(18,8) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`height`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `richlist` */

DROP TABLE IF EXISTS `richlist`;

CREATE TABLE `richlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` int(11) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `balance` double(18,8) DEFAULT NULL,
  `block_height` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `percent` double(4,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `address` (`address`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_height` int(11) DEFAULT NULL,
  `txid` varchar(64) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `locktime` int(11) DEFAULT NULL,
  `raw` longtext,
  `txFee` decimal(18,8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `txid` (`txid`),
  KEY `block_height` (`block_height`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `transactions_in` */

DROP TABLE IF EXISTS `transactions_in`;

CREATE TABLE `transactions_in` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txidp` varchar(64) DEFAULT NULL,
  `txid` varchar(64) DEFAULT NULL,
  `coinbase` varchar(32) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `vout` int(1) DEFAULT NULL,
  `asm` varchar(255) DEFAULT NULL,
  `hex` varchar(255) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `value` decimal(18,8) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `txid` (`txid`),
  KEY `txidp` (`txidp`),
  KEY `address` (`address`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `transactions_out` */

DROP TABLE IF EXISTS `transactions_out`;

CREATE TABLE `transactions_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txidp` varchar(64) DEFAULT NULL,
  `value` decimal(18,8) DEFAULT NULL,
  `n` int(11) DEFAULT NULL,
  `asm` varchar(255) DEFAULT NULL,
  `hex` varchar(64) DEFAULT NULL,
  `reqSigs` varchar(64) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address` (`address`),
  KEY `txidp` (`txidp`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `netwok` */

DROP TABLE IF EXISTS `network`;

CREATE TABLE `network`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `addr` VARCHAR(24) DEFAULT NULL,
  `services` VARCHAR(20) DEFAULT NULL,
  `lastsend` INT(11) DEFAULT NULL,
  `lastrecv` INT(11) DEFAULT NULL,
  `conntime` INT(11) DEFAULT NULL,
  `version` INT(11) DEFAULT NULL,
  `subver` VARCHAR(100) DEFAULT NULL,
  `inbound` INT(11) DEFAULT NULL,
  `releasetime` INT(11) DEFAULT NULL,
  `height` INT(11) DEFAULT NULL,
  `banscore` DOUBLE(4,2) DEFAULT NULL,
  `country_code` VARCHAR(10) DEFAULT NULL,
  `country_name` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state`VARCHAR(100) DEFAULT NULL,
  `connections` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `addr` (`addr`),
  KEY `height` (`height`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
