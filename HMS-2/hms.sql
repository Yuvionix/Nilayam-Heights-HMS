-- MySQL dump 10.13  Distrib 9.3.0, for macos14.7 (arm64)
--
-- Host: localhost    Database: hms
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Yuvraj','yuvrajyadav734@gmail.com','$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO','2022-05-12 19:49:24');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement` (
  `announcement_id` int NOT NULL AUTO_INCREMENT,
  `announcement_subject` varchar(250) NOT NULL,
  `announcement_text` text NOT NULL,
  `announcement_status` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement`
--

LOCK TABLES `announcement` WRITE;
/*!40000 ALTER TABLE `announcement` DISABLE KEYS */;
INSERT INTO `announcement` VALUES (1,'Annual Society General Meeting (AGM) 2026','All residents of Nilayam Heights, Satsang Nagar are requested to attend the Annual General Meeting on Sunday, 1st November 2026 at 10:30 AM in the Community Hall. Agenda includes security upgrades, solar maintenance, and annual budget.',0,'2026-10-25 10:00:00'),(2,'Diwali & Chhath Puja Celebration Arrangements','Special lighting and security protocols will be active across Hirna Road and AS College Road entry gates during the festive week. Volunteers for ghat cleanup are requested to contact management.',0,'2026-10-24 11:30:00'),(3,'Solar Rooftop & Inverter Backup Commissioning','The 25kW rooftop solar panels and uninterrupted battery backup system for lifts and common area lighting have been successfully commissioned.',0,'2026-10-20 14:00:00'),(4,'Overhead Water Tank Sanitization & Cleaning','Bi-monthly cleaning and chlorination of overhead tanks A, B, and C will occur this Saturday between 11:00 AM and 3:00 PM. Please store necessary water beforehand.',0,'2026-10-18 09:15:00');
/*!40000 ALTER TABLE `announcement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billing`
--

DROP TABLE IF EXISTS `billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `billing` (
  `id` int NOT NULL,
  `billing_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `member_id` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending,1=paid',
  `total_amount` double NOT NULL,
  `amount_payed` double NOT NULL,
  `amount_change` double NOT NULL,
  `invoice` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billing`
--

LOCK TABLES `billing` WRITE;
/*!40000 ALTER TABLE `billing` DISABLE KEYS */;
INSERT INTO `billing` VALUES (2,'2026-10-01 00:00:00',2,1,4200,4200,0,'INV-2026-1002'),(3,'2026-10-01 00:00:00',3,0,4800,0,0,'INV-2026-1003'),(4,'2026-10-01 00:00:00',4,0,4600,0,0,'INV-2026-1004'),(5,'2026-10-01 00:00:00',5,1,4100,4100,0,'INV-2026-1005'),(6,'2026-10-01 00:00:00',6,0,4750,0,0,'INV-2026-1006'),(7,'2026-09-01 00:00:00',1,1,4400,4400,0,'INV-2026-0901'),(8,'2026-09-01 00:00:00',2,1,4150,4500,350,'INV-2026-0902'),(9,'2026-09-01 00:00:00',3,1,4700,4700,0,'INV-2026-0903'),(10,'2026-09-01 00:00:00',4,1,4550,5000,450,'INV-2026-0904'),(11,'2026-09-01 00:00:00',5,1,4000,4000,0,'INV-2026-0905'),(12,'2026-09-01 00:00:00',6,1,4600,4600,0,'INV-2026-0906');
/*!40000 ALTER TABLE `billing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bills` (
  `id` int NOT NULL,
  `billing_id` int NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1= block rent, 2= electricity,3=water',
  `reading` int NOT NULL,
  `consumption` int NOT NULL,
  `rate` double NOT NULL,
  `previous_reading` int NOT NULL,
  `previous_consumption` int NOT NULL,
  `amount` double NOT NULL,
  `previous_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bills`
--

LOCK TABLES `bills` WRITE;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
INSERT INTO `bills` VALUES (4,2,1,0,0,2500,0,0,2500,2500),(5,2,2,110,90,12,20,85,1080,1020),(6,2,3,31,31,20,0,30,620,600),(7,3,1,0,0,2500,0,0,2500,2500),(8,3,2,140,120,12,20,110,1440,1320),(9,3,3,43,43,20,0,40,860,800),(10,4,1,0,0,2500,0,0,2500,2500),(11,4,2,130,110,12,20,105,1320,1260),(12,4,3,39,39,20,0,37,780,740),(13,5,1,0,0,2500,0,0,2500,2500),(14,5,2,100,85,12,15,80,1020,960),(15,5,3,29,29,20,0,28,580,560),(16,6,1,0,0,2500,0,0,2500,2500),(17,6,2,135,115,12,20,110,1380,1320),(18,6,3,43,43,20,0,40,870,800);
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'Yuvraj Yadav','yuvrajyadav734@gmail.com','$2y$12$W/f/52QjS6ZmAwkTonVFwOPmKFGsnN0CTpqFA4gn9SQTD/7u/TqBy','Flat A-101, Nilayam Heights, Hirna Road, Satsang Nagar, Deoghar, Jharkhand 814142','9835124011','2026-08-01 10:00:00'),(2,'Rajesh Verma','rajesh.verma@gmail.com','$2y$12$1YtyfmMb/MMQSKEkEyIXj.wV/gajnnBh9gbnk03ZTVEy7c/85lk1a','Flat A-102, Nilayam Heights, Hirna Road, Satsang Nagar, Deoghar, Jharkhand 814142','9431182230','2026-08-01 10:15:00'),(4,'Vikramaditya Roy','vikram.roy@gmail.com','password','Flat B-202, Nilayam Heights, Hirna Road, Satsang Nagar, Deoghar, Jharkhand 814142','7004123890','2026-08-10 12:00:00'),(5,'Sunita Pandey','sunita.pandey@gmail.com','password','Flat C-301, Nilayam Heights, AS College Rd, Satsang Nagar, Deoghar, Jharkhand 814116','9122345678','2026-08-15 14:00:00'),(15,'Yuvraj','yuvi734@gamil.com','$2y$12$9PzE2kirR8zDeuSTES5lrO9wxRgmNayeECjhAdAe9EVoineidNvsC','flat 001','8340195572','2026-10-28 01:50:37');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_announcements`
--

DROP TABLE IF EXISTS `pg_announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `importance` enum('normal','urgent') NOT NULL DEFAULT 'normal',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_announcements`
--

LOCK TABLES `pg_announcements` WRITE;
/*!40000 ALTER TABLE `pg_announcements` DISABLE KEYS */;
INSERT INTO `pg_announcements` VALUES (1,'High-Speed Wi-Fi Router Upgraded','New 300 Mbps fiber dual-band routers installed on all 4 floors. Wi-Fi SSID: YadavPG_5G. Password available at Warden reception desk.','normal','2026-10-20 09:00:00'),(2,'Night Main Gate Closing Time (10:00 PM)','All residents must check in before 10:00 PM. In case of late return from coaching/college, please log late entry with Security Guard Ramesh.','urgent','2026-10-22 18:30:00'),(3,'Sunday Special Feast Menu','Sunday lunch will feature Paneer Butter Masala, Butter Naan, Veg Pulao, Gulab Jamun, and seasonal salad.','normal','2026-10-26 11:00:00');
/*!40000 ALTER TABLE `pg_announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_beds`
--

DROP TABLE IF EXISTS `pg_beds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_beds` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `bed_label` varchar(10) NOT NULL,
  `student_id` int DEFAULT NULL,
  `status` enum('vacant','occupied','maintenance') NOT NULL DEFAULT 'vacant',
  PRIMARY KEY (`id`),
  KEY `fk_room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_beds`
--

LOCK TABLES `pg_beds` WRITE;
/*!40000 ALTER TABLE `pg_beds` DISABLE KEYS */;
INSERT INTO `pg_beds` VALUES (1,1,'Bed A',4,'occupied'),(2,2,'Bed A',2,'occupied'),(3,2,'Bed B',NULL,'vacant'),(4,3,'Bed A',NULL,'vacant'),(5,3,'Bed B',NULL,'vacant'),(6,4,'Bed A',3,'occupied'),(7,4,'Bed B',NULL,'vacant'),(8,4,'Bed C',NULL,'vacant'),(9,5,'Bed A',NULL,'vacant'),(10,6,'Bed A',NULL,'vacant'),(11,6,'Bed B',NULL,'vacant'),(12,7,'Bed A',NULL,'vacant'),(13,7,'Bed B',1,'occupied'),(14,8,'Bed A',NULL,'vacant'),(15,8,'Bed B',NULL,'vacant'),(16,8,'Bed C',NULL,'vacant');
/*!40000 ALTER TABLE `pg_beds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_complaints`
--

DROP TABLE IF EXISTS `pg_complaints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_complaints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `title` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `status` enum('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_comp` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_complaints`
--

LOCK TABLES `pg_complaints` WRITE;
/*!40000 ALTER TABLE `pg_complaints` DISABLE KEYS */;
/*!40000 ALTER TABLE `pg_complaints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_kyc`
--

DROP TABLE IF EXISTS `pg_kyc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_kyc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `id_type` varchar(50) NOT NULL DEFAULT 'Aadhaar Card',
  `id_number` varchar(50) NOT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Verified',
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_at` datetime DEFAULT NULL,
  `father_name` varchar(120) DEFAULT NULL,
  `father_mobile` varchar(25) DEFAULT NULL,
  `mother_name` varchar(120) DEFAULT NULL,
  `mother_mobile` varchar(25) DEFAULT NULL,
  `address` text,
  `profile_pic` varchar(255) DEFAULT NULL,
  `address_proof_front` varchar(255) DEFAULT NULL,
  `address_proof_back` varchar(255) DEFAULT NULL,
  `id_proof_front` varchar(255) DEFAULT NULL,
  `id_proof_back` varchar(255) DEFAULT NULL,
  `terms_accepted` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_student_kyc` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_kyc`
--

LOCK TABLES `pg_kyc` WRITE;
/*!40000 ALTER TABLE `pg_kyc` DISABLE KEYS */;
INSERT INTO `pg_kyc` VALUES (1,1,'Aadhaar Card','XXXX-XXXX-8492','aadhaar_sample.pdf','Verified','2026-10-28 03:20:09','2026-07-18 14:00:00','Vinod Yadav','8210075922','Savitri','','Hirna Near Tv relay Center','PIC_1_yuvraj.jpeg','IMG_20260205_005410.jpg',NULL,'IMG_20260205_005358.jpg','IMG_20260205_005410.jpg',1),(2,4,'Aadhaar Card','Pending Upload',NULL,'Pending','2026-10-28 02:44:11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1),(4,6,'Aadhaar Card','Pending Upload',NULL,'Verified','2026-10-28 09:45:13',NULL,'Akash','8340195572','Savi','9506815461','Deoghar/Jharkhand','PIC_6_1793160913_115.jpeg','ADDR_F_6_1793160913_400.pdf',NULL,'ID_F_6_1793160913_311.pdf',NULL,1);
/*!40000 ALTER TABLE `pg_kyc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_payments`
--

DROP TABLE IF EXISTS `pg_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `month_for` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL DEFAULT 'UPI / GPay',
  `transaction_ref` varchar(100) NOT NULL,
  `status` enum('Paid','Pending') NOT NULL DEFAULT 'Paid',
  PRIMARY KEY (`id`),
  KEY `fk_student_pay` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_payments`
--

LOCK TABLES `pg_payments` WRITE;
/*!40000 ALTER TABLE `pg_payments` DISABLE KEYS */;
INSERT INTO `pg_payments` VALUES (1,1,'August 2026',13000.00,'2026-08-03 11:20:00','UPI / GPay','UPI/2608031120/KIO','Paid'),(2,1,'September 2026',13000.00,'2026-09-02 16:45:00','UPI / PhonePe','UPI/2609021645/KIO','Paid'),(3,1,'October 2026',13000.00,'2026-10-04 10:15:00','UPI / GPay','UPI/2610041015/KIO','Paid');
/*!40000 ALTER TABLE `pg_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_rooms`
--

DROP TABLE IF EXISTS `pg_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_number` varchar(50) NOT NULL,
  `floor` varchar(50) NOT NULL,
  `occupancy_type` enum('single','double','triple') NOT NULL DEFAULT 'double',
  `total_beds` int NOT NULL DEFAULT '2',
  `monthly_rent` decimal(10,2) NOT NULL DEFAULT '10000.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_room` (`room_number`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_rooms`
--

LOCK TABLES `pg_rooms` WRITE;
/*!40000 ALTER TABLE `pg_rooms` DISABLE KEYS */;
INSERT INTO `pg_rooms` VALUES (1,'R-101','First Floor [Yadav PG for Stay]','single',1,16000.00,'2026-10-28 02:02:14'),(2,'R-102','First Floor [Yadav PG for Stay]','double',2,12500.00,'2026-10-28 02:02:14'),(3,'R-201','Second Floor [Yadav PG for Stay]','double',2,12500.00,'2026-10-28 02:02:14'),(4,'R-202','Second Floor [Yadav PG for Stay]','triple',3,9500.00,'2026-10-28 02:02:14'),(5,'R-301','Third Floor [Yadav PG for Stay]','single',1,16000.00,'2026-10-28 02:02:14'),(6,'R-302','Third Floor [Yadav PG for Stay]','double',2,13000.00,'2026-10-28 02:02:14'),(7,'R-406','Four Floor [Yadav PG for Stay]','double',2,13000.00,'2026-10-28 02:02:14'),(8,'R-407','Four Floor [Yadav PG for Stay]','triple',3,9500.00,'2026-10-28 02:02:14');
/*!40000 ALTER TABLE `pg_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_students`
--

DROP TABLE IF EXISTS `pg_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_students` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `college` varchar(150) DEFAULT 'AS College Deoghar',
  `room_id` int DEFAULT NULL,
  `bed_id` int DEFAULT NULL,
  `rent_amount` decimal(10,2) NOT NULL DEFAULT '13000.00',
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT '13000.00',
  `joined_date` date NOT NULL,
  `status` enum('active','vacating','vacated') NOT NULL DEFAULT 'active',
  `created_by` varchar(100) NOT NULL DEFAULT 'Sumit Yadav',
  `location` varchar(255) NOT NULL DEFAULT 'Hirna Near Tv relay Centre B. Deoghar Jharkhand 814112',
  `photo` varchar(255) DEFAULT 'default_student.png',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_student_email` (`email`),
  UNIQUE KEY `uk_student_uname` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_students`
--

LOCK TABLES `pg_students` WRITE;
/*!40000 ALTER TABLE `pg_students` DISABLE KEYS */;
INSERT INTO `pg_students` VALUES (1,'Yuvraj Yadav','yuvrajyadav734','yuvrajyadav734@gmail.com','$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO','8340195572','AI & Software Engineering Institute',7,13,13000.00,13000.00,'2026-07-18','active','Sumit Yadav','Hirna Near Tv relay Center','PIC_1_yuvraj.jpeg','2026-10-28 10:30:08','2026-10-28 02:02:14'),(2,'Aman Sharma','amansharma','aman.sharma@gmail.com','$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO','9835123450','AS College Deoghar',2,2,12500.00,12500.00,'2026-08-01','active','Sumit Yadav','Hirna Road Deoghar','default_student.png',NULL,'2026-10-28 02:02:14'),(3,'Rohan Verma','rohanverma','rohan.verma@gmail.com','$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO','9431188776','BIT Mesra Extension',4,6,9500.00,9500.00,'2026-08-15','active','Sumit Yadav','Hirna Road Deoghar','default_student.png',NULL,'2026-10-28 02:02:14'),(4,'Aarav Kumar','aaravkumaryadav87','aaravkumar_yadav@gmail.com','$2y$12$7InvEQS64kT5kYusKXvAruicy.j/pbdecX7fzfwSPTRbBdD2Kvm7q','9876543210','IIT ISM',1,1,16000.00,16000.00,'2026-10-28','active','Self Online Sign Up','Patna','default_student.png','2026-10-28 02:44:10','2026-10-28 02:44:10'),(6,'Amit Sharma','amit21','amit@gmail.com','$2y$12$FQH8YGFf9nlrPumULsJ5a.3TcaUuFEXE3CoXizB177UPkVZmdMVSK','8340195572','AS College Deoghar',NULL,NULL,0.00,0.00,'2026-10-28','active','Self Online Sign Up','Deoghar/Jharkhand','PIC_6_1793160913_115.jpeg','2026-10-28 09:42:08','2026-10-28 09:42:08');
/*!40000 ALTER TABLE `pg_students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_vacate_intimations`
--

DROP TABLE IF EXISTS `pg_vacate_intimations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pg_vacate_intimations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `intimated_on` date NOT NULL,
  `vacating_on` date NOT NULL,
  `rent_pending` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comment` text NOT NULL,
  `status` enum('Pending Review','Approved','Settled') NOT NULL DEFAULT 'Pending Review',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_student_vac` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_vacate_intimations`
--

LOCK TABLES `pg_vacate_intimations` WRITE;
/*!40000 ALTER TABLE `pg_vacate_intimations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pg_vacate_intimations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registry`
--

DROP TABLE IF EXISTS `registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registry` (
  `id` int NOT NULL AUTO_INCREMENT,
  `person_name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_name` (`person_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registry`
--

LOCK TABLES `registry` WRITE;
/*!40000 ALTER TABLE `registry` DISABLE KEYS */;
INSERT INTO `registry` VALUES (1,'Santosh Jha (Amazon Delivery - Flat A-101)','2026-10-26 10:15:20'),(2,'Dr. S. K. Roy (Visitor - Flat B-202)','2026-10-26 09:40:00'),(3,'Ravi Shankar (Guest - Flat A-102)','2026-10-25 18:30:10'),(4,'Binod Mistri (Solar Maintenance Tech)','2026-10-25 14:15:45'),(5,'Sunil Mahto (Daily Fresh Supplies)','2026-10-25 08:00:30');
/*!40000 ALTER TABLE `registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `society_kyc`
--

DROP TABLE IF EXISTS `society_kyc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `society_kyc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `flat_no` varchar(50) DEFAULT NULL,
  `resident_type` enum('Owner','Tenant','Family Member') DEFAULT 'Owner',
  `father_or_spouse` varchar(100) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `permanent_address` text,
  `profile_pic` varchar(255) DEFAULT NULL,
  `id_proof_type` varchar(50) DEFAULT 'Aadhaar Card',
  `id_proof_front` varchar(255) DEFAULT NULL,
  `id_proof_back` varchar(255) DEFAULT NULL,
  `address_proof_front` varchar(255) DEFAULT NULL,
  `address_proof_back` varchar(255) DEFAULT NULL,
  `terms_accepted` tinyint(1) DEFAULT '1',
  `status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `submitted_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `reviewed_by` varchar(50) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `society_kyc`
--

LOCK TABLES `society_kyc` WRITE;
/*!40000 ALTER TABLE `society_kyc` DISABLE KEYS */;
INSERT INTO `society_kyc` VALUES (1,1,'Yuvraj Yadav','Flat A-101','Owner','Binod Prasad Yadav','7004936609','Hirna Near Tv relay Centre B. Deoghar Jharkhand 814112','PIC_1_yuvraj.jpeg','Aadhaar Card','IMG_20260205_005358.jpg','IMG_20260205_005410.jpg','ADDR_F_6_1793160913_400.pdf',NULL,1,'Verified','2026-10-28 10:58:45','Admin Yuvraj','2026-10-28 10:58:45',NULL);
/*!40000 ALTER TABLE `society_kyc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Ramesh Kumar','ramesh.guard@gmail.com','$2y$12$mWVIvk4L3rxvRZLOxnER0.WmCmU713O6Y1JbpItkXuuvL1xTIFtjW','2026-07-01 08:00:00'),(2,'Manoj Yadav','manoj.electrician@gmail.com','password','2026-07-01 08:00:00'),(3,'Suresh Mandal','suresh.plumber@gmail.com','password','2026-07-01 08:00:00'),(4,'Deepak Paswan','deepak.facility@gmail.com','$2y$12$dHfbRm2rBafbs.LbJyhm4eIaIQMGHtdr0/af7msg8r/feC/Ly5ime','2026-07-01 08:00:00');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-28 11:07:55
