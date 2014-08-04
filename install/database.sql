CREATE DATABASE  IF NOT EXISTS dashboard /*!40100 DEFAULT CHARACTER SET utf8 */;
USE dashboard;
-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: dashboard_v3
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `pro3x_daily_sales_report`
--

DROP TABLE IF EXISTS `pro3x_daily_sales_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_daily_sales_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_95C280CD584598A3` (`operator_id`),
  KEY `IDX_95C280CDDD842E46` (`position_id`),
  CONSTRAINT `FK_95C280CD584598A3` FOREIGN KEY (`operator_id`) REFERENCES `pro3x_users` (`id`),
  CONSTRAINT `FK_95C280CDDD842E46` FOREIGN KEY (`position_id`) REFERENCES `pro3x_positions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_daily_sales_report`
--

LOCK TABLES `pro3x_daily_sales_report` WRITE;
/*!40000 ALTER TABLE `pro3x_daily_sales_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_daily_sales_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_users`
--

DROP TABLE IF EXISTS `pro3x_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `oib` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1F7FDACBF85E0677` (`username`),
  UNIQUE KEY `UNIQ_1F7FDACBE7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_users`
--

LOCK TABLES `pro3x_users` WRITE;
/*!40000 ALTER TABLE `pro3x_users` DISABLE KEYS */;
INSERT INTO `pro3x_users` VALUES (8,'Administrator',NULL,'0d3780e1f41827fd3147bf11a64a8d0e4369b4e6','c809deaec167cc814cc2cf7b50006735','admin','admin@pro3x.com',1,NULL);
/*!40000 ALTER TABLE `pro3x_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_groups`
--

DROP TABLE IF EXISTS `pro3x_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_250684EB57698A6A` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_groups`
--

LOCK TABLES `pro3x_groups` WRITE;
/*!40000 ALTER TABLE `pro3x_groups` DISABLE KEYS */;
INSERT INTO `pro3x_groups` VALUES (1,'Administrators','ROLE_ADMIN'),(2,'Users','ROLE_USER');
/*!40000 ALTER TABLE `pro3x_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_invoices`
--

DROP TABLE IF EXISTS `pro3x_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `tenderDate` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `tenderSequence` int(11) DEFAULT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `companyTaxNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqueInvoiceNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `originalInvoiceNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoiceTotal` decimal(10,2) NOT NULL,
  `companySecureCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isFiscalTransaction` tinyint(1) NOT NULL,
  `tenderTemplate_id` int(11) DEFAULT NULL,
  `dailyReport_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4D4A661E9395C3F3` (`customer_id`),
  KEY `IDX_4D4A661EA76ED395` (`user_id`),
  KEY `IDX_4D4A661EDD842E46` (`position_id`),
  KEY `IDX_4D4A661E5DA0FB8` (`template_id`),
  KEY `IDX_4D4A661E941B5291` (`tenderTemplate_id`),
  KEY `IDX_4D4A661E5DECB4D7` (`dailyReport_id`),
  CONSTRAINT `FK_4D4A661E5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `pro3x_templates` (`id`),
  CONSTRAINT `FK_4D4A661E5DECB4D7` FOREIGN KEY (`dailyReport_id`) REFERENCES `pro3x_daily_sales_report` (`id`),
  CONSTRAINT `FK_4D4A661E9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `pro3x_clients` (`id`),
  CONSTRAINT `FK_4D4A661E941B5291` FOREIGN KEY (`tenderTemplate_id`) REFERENCES `pro3x_templates` (`id`),
  CONSTRAINT `FK_4D4A661EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `pro3x_users` (`id`),
  CONSTRAINT `FK_4D4A661EDD842E46` FOREIGN KEY (`position_id`) REFERENCES `pro3x_positions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_invoices`
--

LOCK TABLES `pro3x_invoices` WRITE;
/*!40000 ALTER TABLE `pro3x_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_invoice_items`
--

DROP TABLE IF EXISTS `pro3x_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `taxedPrice` decimal(10,2) NOT NULL,
  `totalTaxedPrice` decimal(10,2) NOT NULL,
  `dicountPrice` decimal(10,2) NOT NULL,
  `discountAmount` decimal(10,2) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `taxAmount` decimal(10,2) NOT NULL,
  `totalTaxAmount` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_683794622989F1FD` (`invoice_id`),
  CONSTRAINT `FK_683794622989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `pro3x_invoices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_invoice_items`
--

LOCK TABLES `pro3x_invoice_items` WRITE;
/*!40000 ALTER TABLE `pro3x_invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_products`
--

DROP TABLE IF EXISTS `pro3x_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unitPrice` decimal(14,6) NOT NULL,
  `unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inputPrice` decimal(14,6) NOT NULL,
  `taxedInputPrice` decimal(14,2) NOT NULL,
  `inputTaxRate_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_94DF13D18625605E` (`inputTaxRate_id`),
  CONSTRAINT `FK_94DF13D18625605E` FOREIGN KEY (`inputTaxRate_id`) REFERENCES `pro3x_tax_rates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_products`
--

LOCK TABLES `pro3x_products` WRITE;
/*!40000 ALTER TABLE `pro3x_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipt`
--

DROP TABLE IF EXISTS `receipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receipt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9C248FD92ADD6D8C` (`supplier_id`),
  CONSTRAINT `FK_9C248FD92ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `pro3x_clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipt`
--

LOCK TABLES `receipt` WRITE;
/*!40000 ALTER TABLE `receipt` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_positions`
--

DROP TABLE IF EXISTS `pro3x_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `sequence` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenderSequence` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ACD2DA9D64D218E` (`location_id`),
  CONSTRAINT `FK_ACD2DA9D64D218E` FOREIGN KEY (`location_id`) REFERENCES `pro3x_locations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_positions`
--

LOCK TABLES `pro3x_positions` WRITE;
/*!40000 ALTER TABLE `pro3x_positions` DISABLE KEYS */;
INSERT INTO `pro3x_positions` VALUES (1,1,1,'Promijenite naziv blagajne',1);
/*!40000 ALTER TABLE `pro3x_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_templates`
--

DROP TABLE IF EXISTS `pro3x_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transactionType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `useGoogleCloud` tinyint(1) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1565426F64D218E` (`location_id`),
  CONSTRAINT `FK_1565426F64D218E` FOREIGN KEY (`location_id`) REFERENCES `pro3x_locations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_templates`
--

LOCK TABLES `pro3x_templates` WRITE;
/*!40000 ALTER TABLE `pro3x_templates` DISABLE KEYS */;
INSERT INTO `pro3x_templates` VALUES (1,1,'Gotovina','gotovina','G',0,500),(2,1,'Ponuda','tender','P',0,100);
/*!40000 ALTER TABLE `pro3x_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_clients`
--

DROP TABLE IF EXISTS `pro3x_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cellPhone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accomodation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherAccomodation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ownership` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otherOwnership` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8_unicode_ci,
  `warning` longtext COLLATE utf8_unicode_ci,
  `birthday` datetime DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_clients`
--

LOCK TABLES `pro3x_clients` WRITE;
/*!40000 ALTER TABLE `pro3x_clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrationkey`
--

DROP TABLE IF EXISTS `registrationkey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrationkey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `validFrom` datetime NOT NULL,
  `validTo` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DF6499A44584665A` (`product_id`),
  KEY `IDX_DF6499A49395C3F3` (`customer_id`),
  CONSTRAINT `FK_DF6499A44584665A` FOREIGN KEY (`product_id`) REFERENCES `pro3x_products` (`id`),
  CONSTRAINT `FK_DF6499A49395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `pro3x_clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrationkey`
--

LOCK TABLES `registrationkey` WRITE;
/*!40000 ALTER TABLE `registrationkey` DISABLE KEYS */;
/*!40000 ALTER TABLE `registrationkey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_tax_rates`
--

DROP TABLE IF EXISTS `pro3x_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `taxGroup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_tax_rates`
--

LOCK TABLES `pro3x_tax_rates` WRITE;
/*!40000 ALTER TABLE `pro3x_tax_rates` DISABLE KEYS */;
INSERT INTO `pro3x_tax_rates` VALUES (1,'PDV',0.25,'Pdv'),(2,'Porez na potrošnju',0.03,'Pnp');
/*!40000 ALTER TABLE `pro3x_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_locations`
--

DROP TABLE IF EXISTS `pro3x_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companyTaxNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postalCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `houseNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `houseNumberExtension` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `settlement` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workingHours` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `securityKey` longtext COLLATE utf8_unicode_ci,
  `securityCertificate` longtext COLLATE utf8_unicode_ci,
  `taxPayer` tinyint(1) DEFAULT NULL,
  `submited` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_locations`
--

LOCK TABLES `pro3x_locations` WRITE;
/*!40000 ALTER TABLE `pro3x_locations` DISABLE KEYS */;
INSERT INTO `pro3x_locations` VALUES (1,'Promijenite naziv lokacije',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0);
/*!40000 ALTER TABLE `pro3x_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_customer_notes`
--

DROP TABLE IF EXISTS `pro3x_customer_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_customer_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `noteType` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DF405AC03174800F` (`createdBy_id`),
  KEY `IDX_DF405AC09395C3F3` (`customer_id`),
  CONSTRAINT `FK_DF405AC03174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `pro3x_users` (`id`),
  CONSTRAINT `FK_DF405AC09395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `pro3x_clients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_customer_notes`
--

LOCK TABLES `pro3x_customer_notes` WRITE;
/*!40000 ALTER TABLE `pro3x_customer_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_customer_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_invoice_item_taxes`
--

DROP TABLE IF EXISTS `pro3x_invoice_item_taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_invoice_item_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `taxId` int(11) NOT NULL,
  `taxDescription` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `taxRate` decimal(10,2) NOT NULL,
  `taxGroup` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EF3022D1126F525E` (`item_id`),
  CONSTRAINT `FK_EF3022D1126F525E` FOREIGN KEY (`item_id`) REFERENCES `pro3x_invoice_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_invoice_item_taxes`
--

LOCK TABLES `pro3x_invoice_item_taxes` WRITE;
/*!40000 ALTER TABLE `pro3x_invoice_item_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_invoice_item_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_customer_relations`
--

DROP TABLE IF EXISTS `pro3x_customer_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_customer_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `relationType_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4154A9057E3C61F9` (`owner_id`),
  KEY `IDX_4154A905E4E6521A` (`relationType_id`),
  KEY `IDX_4154A9054162C001` (`related_id`),
  CONSTRAINT `FK_4154A9054162C001` FOREIGN KEY (`related_id`) REFERENCES `pro3x_clients` (`id`),
  CONSTRAINT `FK_4154A9057E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `pro3x_clients` (`id`),
  CONSTRAINT `FK_4154A905E4E6521A` FOREIGN KEY (`relationType_id`) REFERENCES `pro3x_relation_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_customer_relations`
--

LOCK TABLES `pro3x_customer_relations` WRITE;
/*!40000 ALTER TABLE `pro3x_customer_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_customer_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receiptitem`
--

DROP TABLE IF EXISTS `receiptitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receiptitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `taxedInputPrice` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `totalTaxedPrice` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `discountAmount` decimal(14,2) NOT NULL,
  `totalTaxedDiscountPrice` decimal(14,2) NOT NULL,
  `totalTaxAmount` decimal(14,2) NOT NULL,
  `totalDiscountPrice` decimal(14,2) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inputTaxRate_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64D1E472B5CA896` (`receipt_id`),
  KEY `IDX_64D1E478625605E` (`inputTaxRate_id`),
  KEY `IDX_64D1E474584665A` (`product_id`),
  CONSTRAINT `FK_64D1E472B5CA896` FOREIGN KEY (`receipt_id`) REFERENCES `receipt` (`id`),
  CONSTRAINT `FK_64D1E474584665A` FOREIGN KEY (`product_id`) REFERENCES `pro3x_products` (`id`),
  CONSTRAINT `FK_64D1E478625605E` FOREIGN KEY (`inputTaxRate_id`) REFERENCES `pro3x_tax_rates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receiptitem`
--

LOCK TABLES `receiptitem` WRITE;
/*!40000 ALTER TABLE `receiptitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `receiptitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_product_tax_rates`
--

DROP TABLE IF EXISTS `pro3x_product_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_product_tax_rates` (
  `product_id` int(11) NOT NULL,
  `taxrate_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`taxrate_id`),
  KEY `IDX_88FBF9084584665A` (`product_id`),
  KEY `IDX_88FBF90897012B7` (`taxrate_id`),
  CONSTRAINT `FK_88FBF9084584665A` FOREIGN KEY (`product_id`) REFERENCES `pro3x_products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_88FBF90897012B7` FOREIGN KEY (`taxrate_id`) REFERENCES `pro3x_tax_rates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_product_tax_rates`
--

LOCK TABLES `pro3x_product_tax_rates` WRITE;
/*!40000 ALTER TABLE `pro3x_product_tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_product_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_users_groups`
--

DROP TABLE IF EXISTS `pro3x_users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_users_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_7191A1CA76ED395` (`user_id`),
  KEY `IDX_7191A1CFE54D947` (`group_id`),
  CONSTRAINT `FK_7191A1CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `pro3x_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7191A1CFE54D947` FOREIGN KEY (`group_id`) REFERENCES `pro3x_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_users_groups`
--

LOCK TABLES `pro3x_users_groups` WRITE;
/*!40000 ALTER TABLE `pro3x_users_groups` DISABLE KEYS */;
INSERT INTO `pro3x_users_groups` VALUES (8,1);
/*!40000 ALTER TABLE `pro3x_users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pro3x_relation_type`
--

DROP TABLE IF EXISTS `pro3x_relation_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pro3x_relation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pro3x_relation_type`
--

LOCK TABLES `pro3x_relation_type` WRITE;
/*!40000 ALTER TABLE `pro3x_relation_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `pro3x_relation_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-31 17:44:20
