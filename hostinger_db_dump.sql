/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: u747826271_eurotaxi
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(191) DEFAULT NULL,
  `user_role` varchar(191) DEFAULT NULL,
  `module` varchar(191) DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `details` text DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(191) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `activity_logs_module_index` (`module`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `admin_activity_logs`
--

DROP TABLE IF EXISTS `admin_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `action` varchar(191) NOT NULL,
  `module` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `target_id` varchar(191) DEFAULT NULL,
  `target_name` varchar(191) DEFAULT NULL,
  `ip_address` varchar(191) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_activity_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admin_activity_logs` WRITE;
/*!40000 ALTER TABLE `admin_activity_logs` DISABLE KEYS */;
INSERT INTO `admin_activity_logs` VALUES
(1,125,'create','expense','Created new Office Expense: VRDFDDD',NULL,'VRDFDDD','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"1bIvxAy0VbjRQd4h3gghDudYDioJKb5wxP6uLZY3\",\"_method\":\"POST\",\"date\":\"2026-05-04\",\"amount\":\"4\",\"category\":\"Water (Maynilad)\",\"spare_part_id\":null,\"new_part_name\":null,\"quantity\":null,\"unit_price\":null,\"vendor_name\":\"GSFSDX\",\"update_master\":\"0\",\"franchise_case_id\":null,\"new_expiry_date\":null,\"custom_category\":null,\"description\":\"VRDFDDD\",\"reference_number\":\"VDFSFSNVJFNJNKJDNKKDNVFNFNVJFN\",\"payment_method\":\"Cash\"}','2026-05-04 02:38:52','2026-05-04 02:38:52'),
(2,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"1bIvxAy0VbjRQd4h3gghDudYDioJKb5wxP6uLZY3\",\"unit_id\":\"1\",\"driver_id\":\"107\",\"incident_type\":\"Late Remittance\",\"severity\":\"high\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":null,\"description\":\"JK\",\"days_missing\":null,\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"is_driver_fault\":\"1\",\"cause_of_incident\":null}','2026-05-04 02:40:17','2026-05-04 02:40:17'),
(3,125,'create','salary','Created new Salary Payment: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"1bIvxAy0VbjRQd4h3gghDudYDioJKb5wxP6uLZY3\",\"_method\":\"POST\",\"employee_raw\":\"staff_6\",\"employee_type\":\"Mechanic\",\"basic_salary\":\"98\",\"overtime_pay\":\"89999\",\"holiday_pay\":\"88888\",\"night_differential\":\"88888\",\"allowance\":\"88888\",\"pay_date\":\"2027-08-25\",\"month\":\"8\",\"year\":\"2027\"}','2026-05-04 02:41:33','2026-05-04 02:41:33'),
(4,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\",\"debt_id\":\"57\",\"payment_amount\":\"21110\"}','2026-05-04 02:44:00','2026-05-04 02:44:00'),
(5,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\",\"debt_id\":\"56\",\"payment_amount\":\"650\"}','2026-05-04 02:44:09','2026-05-04 02:44:09'),
(6,125,'create','boundary','Created new Boundary Collection: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\",\"action\":\"add_boundary\",\"id\":null,\"unit_id\":\"112\",\"driver_id\":\"2\",\"date\":\"2026-05-04\",\"boundary_amount\":\"550.00\",\"actual_boundary\":\"550.00\",\"damage_payment\":\"0\",\"notes\":null,\"hours_driven\":null}','2026-05-04 02:57:33','2026-05-04 02:57:33'),
(7,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"139808\"}','2026-05-04 03:21:08','2026-05-04 03:21:08'),
(8,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\",\"unit_id\":\"160\",\"driver_id\":\"86\",\"incident_type\":\"The vehicle unit was taken\\/stolen\",\"severity\":\"critical\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":null,\"description\":\"wadw\",\"days_missing\":null,\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"is_driver_fault\":\"1\",\"cause_of_incident\":null}','2026-05-04 03:38:44','2026-05-04 03:38:44'),
(9,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\",\"unit_id\":\"160\",\"driver_id\":\"73\",\"incident_type\":\"The vehicle unit was taken\\/stolen\",\"severity\":\"critical\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":null,\"description\":\"aw\",\"days_missing\":\"100\",\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"is_driver_fault\":\"1\",\"cause_of_incident\":null}','2026-05-04 03:39:33','2026-05-04 03:39:33'),
(10,130,'create','Force-change-password','Created new Force-change-password: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"current_password\":\"@ReaManager2026\",\"new_password\":\"@ReaManager2026\",\"new_password_confirmation\":\"@ReaManager2026\"}','2026-05-04 03:49:00','2026-05-04 03:49:00'),
(11,125,'create','unit','Created new Vehicle Unit: ID: 122','122','ID: 122','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\"}','2026-05-04 03:53:10','2026-05-04 03:53:10'),
(12,125,'create','unit','Created new Vehicle Unit: ID: 139','139','ID: 139','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"HEVicObF7tbSeefbN3BvuRqXpj1DhMSeM8znTAQT\"}','2026-05-04 03:53:51','2026-05-04 03:53:51'),
(13,125,'create','archive','Created new Archive Record: ID: 37','37','ID: 37','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"7UNwbUmtdvwCP9VabBuPg8bysHxaiLChnL3kEf83\"}','2026-05-04 04:02:18','2026-05-04 04:02:18'),
(14,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":false}','2026-05-04 04:22:25','2026-05-04 04:22:25'),
(15,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":true}','2026-05-04 07:05:56','2026-05-04 07:05:56'),
(16,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":true}','2026-05-04 07:14:07','2026-05-04 07:14:07'),
(17,130,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"592014\"}','2026-05-04 07:46:38','2026-05-04 07:46:38'),
(18,125,'create','maintenance','Created new Maintenance Record: ID: 17','17','ID: 17','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"7UNwbUmtdvwCP9VabBuPg8bysHxaiLChnL3kEf83\"}','2026-05-04 07:50:52','2026-05-04 07:50:52'),
(19,125,'create','maintenance','Created new Maintenance Record: ID: 17','17','ID: 17','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"MnA9ScewTRSm2loEn6FyPU0jnNgMoTfSs4oOcB58\"}','2026-05-04 07:51:08','2026-05-04 07:51:08'),
(20,125,'create','maintenance','Created new Maintenance Record: ID: 18','18','ID: 18','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"MnA9ScewTRSm2loEn6FyPU0jnNgMoTfSs4oOcB58\"}','2026-05-04 07:51:11','2026-05-04 07:51:11'),
(21,125,'create','maintenance','Created new Maintenance Record: ID: 18','18','ID: 18','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"MnA9ScewTRSm2loEn6FyPU0jnNgMoTfSs4oOcB58\"}','2026-05-04 07:52:54','2026-05-04 07:52:54'),
(22,129,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"117713\"}','2026-05-04 08:03:50','2026-05-04 08:03:50'),
(23,125,'create','inventory','Created new Inventory Item: Air Filter (Toyota Vios/Hiace)','2','Air Filter (Toyota Vios/Hiace)','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":\"2\",\"name\":\"Air Filter (Toyota Vios\\/Hiace)\",\"price\":\"850\",\"qty_to_add\":1,\"supplier\":\"A. BONIFACIO AUTO\"}','2026-05-04 08:04:12','2026-05-04 08:04:12'),
(24,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":false}','2026-05-04 08:06:03','2026-05-04 08:06:03'),
(25,125,'create','inventory','Created new Inventory Item: we','19','we','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":\"19\",\"name\":\"we\",\"contact_person\":null,\"phone_number\":null}','2026-05-04 08:06:46','2026-05-04 08:06:46'),
(26,125,'create','inventory','Created new Inventory Item: qweqwewq',NULL,'qweqwewq','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":null,\"name\":\"qweqwewq\",\"contact_person\":null,\"phone_number\":null}','2026-05-04 08:06:57','2026-05-04 08:06:57'),
(27,125,'create','inventory','Created new Inventory Item: qwe',NULL,'qwe','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":null,\"name\":\"qwe\",\"contact_person\":\"qweqwe\",\"phone_number\":\"09123231231\"}','2026-05-04 08:07:42','2026-05-04 08:07:42'),
(28,125,'create','inventory','Created new Inventory Item: 213',NULL,'213','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":null,\"name\":\"213\",\"contact_person\":\"edqewa\",\"phone_number\":\"12321312\",\"address\":\"213\",\"_token\":\"MnA9ScewTRSm2loEn6FyPU0jnNgMoTfSs4oOcB58\"}','2026-05-04 08:08:38','2026-05-04 08:08:38'),
(29,125,'create','user','Created new User Account: Ria JANE',NULL,'Ria JANE','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"first_name\":\"Ria JANE\",\"last_name\":\"PEROCHO\",\"email\":\"perochoriajane4@gmail.com\",\"phone_number\":\"09814444055\",\"role\":\"manager\",\"address\":\"0049 Liwag st. Brgy Cabanbanan Pagsanjan Laguna\"}','2026-05-04 08:08:50','2026-05-04 08:08:50'),
(30,125,'create','inventory','Created new Inventory Item: 213',NULL,'213','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":null,\"name\":\"213\",\"contact_person\":\"edqewa\",\"phone_number\":\"09213123123\",\"address\":\"213\",\"_token\":\"MnA9ScewTRSm2loEn6FyPU0jnNgMoTfSs4oOcB58\"}','2026-05-04 08:09:07','2026-05-04 08:09:07'),
(31,125,'create','user','Created new User Account: Ria JANE',NULL,'Ria JANE','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"first_name\":\"Ria JANE\",\"last_name\":\"PEROCHO\",\"email\":\"haha@gmail.com\",\"phone_number\":\"09814444055\",\"role\":\"manager\",\"address\":\"0049 Liwag st. Brgy Cabanbanan Pagsanjan Laguna\"}','2026-05-04 08:10:09','2026-05-04 08:10:09'),
(32,125,'create','user','Created new User Account: PEPITO',NULL,'PEPITO','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"first_name\":\"PEPITO\",\"last_name\":\"PEPITO\",\"email\":\"HA@GMAIL.COM\",\"phone_number\":\"09814444055\",\"role\":\"dispatcher\",\"address\":\"0049 Liwag st\"}','2026-05-04 08:11:50','2026-05-04 08:11:50'),
(33,125,'create','inventory','Created new Inventory Item: ATF / CVT Transmission Fluid (1L)','14','ATF / CVT Transmission Fluid (1L)','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"id\":\"14\",\"name\":\"ATF \\/ CVT Transmission Fluid (1L)\",\"price\":\"650\",\"qty_to_add\":1,\"supplier\":null}','2026-05-04 08:12:54','2026-05-04 08:12:54'),
(34,125,'create','user','Created new User Account: ri',NULL,'ri','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"first_name\":\"ri\",\"last_name\":\"po\",\"email\":\"pepito@gmail.com\",\"phone_number\":\"09814444055\",\"role\":\"secretary\",\"address\":\"0049 Liwag st\"}','2026-05-04 08:14:13','2026-05-04 08:14:13'),
(35,125,'create','user','Created new User Account: ID: 130','130','ID: 130','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"pages\":[\"dashboard\",\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"live-tracking.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"salary.*\",\"boundary-rules.*\",\"decision-management.*\",\"staff.*\",\"archive.*\",\"analytics.*\"]}','2026-05-04 08:16:55','2026-05-04 08:16:55'),
(36,125,'delete','user','Deleted User Account: ID: 136','136','ID: 136','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:16:59','2026-05-04 08:16:59'),
(37,125,'create','user','Created new User Account: ID: 141','141','ID: 141','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"pages\":[]}','2026-05-04 08:18:33','2026-05-04 08:18:33'),
(38,125,'create','user','Created new User Account: ID: 140','140','ID: 140','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"pages\":[]}','2026-05-04 08:18:55','2026-05-04 08:18:55'),
(39,125,'create','maintenance','Created new Maintenance Record: ID: 17','17','ID: 17','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"7UNwbUmtdvwCP9VabBuPg8bysHxaiLChnL3kEf83\"}','2026-05-04 08:25:39','2026-05-04 08:25:39'),
(40,125,'delete','unit','Deleted Vehicle Unit: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:30:25','2026-05-04 08:30:25'),
(41,125,'delete','unit','Deleted Vehicle Unit: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:30:37','2026-05-04 08:30:37'),
(42,125,'create','archive','Created new Archive Record: ID: 1','1','ID: 1','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\"}','2026-05-04 08:30:46','2026-05-04 08:30:46'),
(43,125,'create','archive','Created new Archive Record: ID: 112','112','ID: 112','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\"}','2026-05-04 08:30:48','2026-05-04 08:30:48'),
(44,125,'create','unit','Created new Vehicle Unit: Unit ABC2425',NULL,'Unit ABC2425','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"plate_number\":\"ABC2425\",\"make\":\"LAMBORGINI\",\"model\":\"HAKDOG\",\"year\":\"2026\",\"motor_no\":\"2NR3456777\",\"chassis_no\":\"NCP4D\",\"boundary_rate\":\"1100.00\",\"purchase_cost\":\"34568.00\",\"purchase_date\":\"2026-05-03\",\"driver_id\":\"2\",\"secondary_driver_id\":null,\"coding_day\":\"Wednesday\",\"imei\":null}','2026-05-04 08:34:01','2026-05-04 08:34:01'),
(45,125,'create','unit','Created new Vehicle Unit: Unit ABCC 123',NULL,'Unit ABCC 123','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"plate_number\":\"ABCC 123\",\"make\":\"LAMBORGINI\",\"model\":\"WOWERS\",\"year\":\"2026\",\"motor_no\":\"2P5555\",\"chassis_no\":\"NCT77777\",\"boundary_rate\":\"1100.00\",\"purchase_cost\":\"2545.00\",\"purchase_date\":\"2026-05-04\",\"driver_id\":null,\"secondary_driver_id\":null,\"coding_day\":\"Tuesday\",\"imei\":null}','2026-05-04 08:37:31','2026-05-04 08:37:31'),
(46,125,'update','unit','Updated Vehicle Unit: Unit ABCC 123',NULL,'Unit ABCC 123','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"_method\":\"PUT\",\"plate_number\":\"ABCC 123\",\"make\":\"LAMBORGINI\",\"model\":\"WOWERS\",\"year\":\"2026\",\"motor_no\":\"2P5555\",\"chassis_no\":\"NCT77777\",\"status\":\"active\",\"unit_type\":\"new\",\"boundary_rate\":\"1100.00\",\"purchase_cost\":\"2545.00\",\"purchase_date\":\"2026-05-04\",\"driver_id\":null,\"secondary_driver_id\":null,\"coding_day\":\"Tuesday\",\"imei\":null}','2026-05-04 08:39:28','2026-05-04 08:39:28'),
(47,125,'delete','unit','Deleted Vehicle Unit: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:41:37','2026-05-04 08:41:37'),
(48,125,'delete','unit','Deleted Vehicle Unit: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:41:55','2026-05-04 08:41:55'),
(49,125,'delete','driver','Deleted Driver: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:42:10','2026-05-04 08:42:10'),
(50,125,'create','maintenance','Created new Maintenance Record: ID: 17','17','ID: 17','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"7UNwbUmtdvwCP9VabBuPg8bysHxaiLChnL3kEf83\"}','2026-05-04 08:43:56','2026-05-04 08:43:56'),
(51,125,'create','driver','Created new Driver: RI RO',NULL,'RI RO','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"_method\":\"POST\",\"driver_id\":null,\"first_name\":\"RI\",\"last_name\":\"RO\",\"contact_number\":\"09814444055\",\"is_active\":\"1\",\"address\":\"0049 Liwag st\",\"license_number\":\"A01-22-245677\",\"license_expiry\":\"2026-05-03\",\"hire_date\":\"2026-05-04\",\"daily_boundary_target\":null,\"emergency_contact\":\"RONIE JOLLIBEE\",\"emergency_phone\":\"09814444055\"}','2026-05-04 08:45:22','2026-05-04 08:45:22'),
(52,125,'create','driver','Created new Driver: RI RO',NULL,'RI RO','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"_method\":\"POST\",\"driver_id\":null,\"first_name\":\"RI\",\"last_name\":\"RO\",\"contact_number\":\"09814444055\",\"is_active\":\"1\",\"address\":\"0049 Liwag st\",\"license_number\":\"A01-22-245677\",\"license_expiry\":\"2026-05-03\",\"hire_date\":\"2026-05-04\",\"daily_boundary_target\":null,\"emergency_contact\":\"RONIE JOLLIBEE\",\"emergency_phone\":\"09814444055\"}','2026-05-04 08:47:07','2026-05-04 08:47:07'),
(53,125,'create','driver','Created new Driver: Ria Jane Perocho',NULL,'Ria Jane Perocho','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"_method\":\"POST\",\"driver_id\":null,\"first_name\":\"Ria Jane\",\"last_name\":\"Perocho\",\"contact_number\":\"09814444055\",\"is_active\":\"1\",\"address\":\"0049 Liwag st\",\"license_number\":\"A03-45-666666\",\"license_expiry\":\"2026-04-02\",\"hire_date\":\"2026-05-04\",\"daily_boundary_target\":null,\"emergency_contact\":\"RONIE JOLLIBEE\",\"emergency_phone\":\"09814444055\"}','2026-05-04 08:48:47','2026-05-04 08:48:47'),
(54,125,'create','boundary','Created new Boundary Collection: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"action\":\"add_boundary\",\"id\":null,\"unit_id\":\"112\",\"driver_id\":null,\"date\":\"2026-05-04\",\"boundary_amount\":\"135.91\",\"actual_boundary\":\"135.91\",\"damage_payment\":null,\"notes\":null,\"is_absent\":\"1\",\"needs_maintenance_half\":\"1\",\"hours_driven\":\"5.93\"}','2026-05-04 08:53:25','2026-05-04 08:53:25'),
(55,125,'create','franchise','Created new Franchise Case: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"action\":\"delete_case\",\"case_id\":\"31\"}','2026-05-04 08:54:21','2026-05-04 08:54:21'),
(56,125,'create','maintenance','Created new Maintenance Record: preventive',NULL,'preventive','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"unit_id\":\"122\",\"driver_id\":\"11\",\"maintenance_type\":\"preventive\",\"status\":\"pending\",\"description\":\"bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb\",\"date_started\":\"2026-05-04\",\"date_completed\":\"2026-05-05\",\"mechanic_name\":[\"Abran A. Oracion\",null,null,null,null],\"parts_data\":\"{\\\"parts\\\":[{\\\"id\\\":13,\\\"name\\\":\\\"Brake Fluid (500ml)\\\",\\\"price\\\":350,\\\"maxQty\\\":1,\\\"qty\\\":1},{\\\"id\\\":2,\\\"name\\\":\\\"Air Filter (Toyota Vios\\/Hiace)\\\",\\\"price\\\":850,\\\"maxQty\\\":10156,\\\"qty\\\":1}],\\\"others\\\":[]}\",\"cost\":\"1200.00\"}','2026-05-04 08:56:17','2026-05-04 08:56:17'),
(57,125,'delete','maintenance','Deleted Maintenance Record: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,NULL,'2026-05-04 08:57:12','2026-05-04 08:57:12'),
(58,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"imLH9NP1JQpatGE8b6vCRRZ6HcDJczvXO06wmd95\",\"unit_id\":\"12\",\"driver_id\":\"61\",\"incident_type\":\"Speeding\",\"severity\":\"high\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":\"2000\",\"description\":\"hhhah\",\"days_missing\":null,\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"cause_of_incident\":null}','2026-05-04 08:58:14','2026-05-04 08:58:14'),
(59,125,'create','driver','Created new Driver: ID: 87','87','ID: 87','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'[]','2026-05-04 08:58:34','2026-05-04 08:58:34'),
(60,125,'create','archive','Created new Archive Record: ID: 52','52','ID: 52','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"7UNwbUmtdvwCP9VabBuPg8bysHxaiLChnL3kEf83\"}','2026-05-04 11:14:48','2026-05-04 11:14:48'),
(61,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','175.176.52.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":false}','2026-05-04 11:56:07','2026-05-04 11:56:07'),
(62,129,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"939101\"}','2026-05-04 13:30:08','2026-05-04 13:30:08'),
(63,131,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"300854\"}','2026-05-04 13:31:36','2026-05-04 13:31:36'),
(64,125,'create','boundary','Created new Boundary Collection: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\",\"action\":\"add_boundary\",\"id\":null,\"unit_id\":\"2\",\"driver_id\":\"2\",\"date\":\"2026-05-04\",\"boundary_amount\":\"900.00\",\"actual_boundary\":\"900.00\",\"damage_payment\":\"0\",\"notes\":\"s\",\"past_cutoff\":\"1\",\"hours_driven\":null}','2026-05-04 13:47:42','2026-05-04 13:47:42'),
(65,125,'create','user','Created new User Account: ID: 130','130','ID: 130','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"pages\":[\"dashboard\",\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"live-tracking.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"salary.*\",\"boundary-rules.*\",\"decision-management.*\",\"staff.*\",\"archive.*\",\"analytics.*\",\"unit-profitability.*\"]}','2026-05-04 13:51:09','2026-05-04 13:51:09'),
(66,129,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"shiellamarie.sec@gmail.com\",\"password\":\"@ShiellaSec2026\",\"remember\":false}','2026-05-04 14:03:49','2026-05-04 14:03:49'),
(67,125,'create','user','Created new User Account: ID: 129','129','ID: 129','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"pages\":[\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"boundary-rules.*\",\"staff.*\",\"archive.*\"]}','2026-05-04 14:06:46','2026-05-04 14:06:46'),
(68,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"otp\":\"949902\"}','2026-05-04 14:15:03','2026-05-04 14:15:03'),
(69,125,'create','franchise','Created new Franchise Case: ewqeqw',NULL,'ewqeqw','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\",\"action\":\"save_case\",\"case_id\":\"0\",\"applicant_name\":\"AHTDOG\",\"case_no\":\"ewqeqw\",\"type_of_application\":\"qewqqwqweqew\",\"denomination\":\"qweewq\",\"date_filed\":\"2026-05-04\",\"expiry_date\":\"2026-05-03\",\"units\":[{\"make\":\"wqeew\",\"motor_no\":\"2NZ6978423\",\"chasis_no\":\"NCP1512012488\",\"plate_no\":\"4354354345\",\"year_model\":\"2014\"},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null},{\"make\":null,\"motor_no\":null,\"chasis_no\":null,\"plate_no\":null,\"year_model\":null}]}','2026-05-04 15:18:05','2026-05-04 15:18:05'),
(70,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":true}','2026-05-04 15:19:08','2026-05-04 15:19:08'),
(71,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"87\",\"payment_amount\":\"2000\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:49:31','2026-05-04 15:49:31'),
(72,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"87\",\"payment_amount\":\"2000\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:49:36','2026-05-04 15:49:36'),
(73,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"87\",\"payment_amount\":\"2000\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:49:40','2026-05-04 15:49:40'),
(74,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"87\",\"payment_amount\":\"2000\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:51:26','2026-05-04 15:51:26'),
(75,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"87\",\"payment_amount\":\"2000\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:51:29','2026-05-04 15:51:29'),
(76,131,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"debt_id\":\"36\",\"payment_amount\":\"1700\",\"_token\":\"HNxjqhYEjNvTaYS8A5FXOpTgIM4sKDq6L7oOpCfG\"}','2026-05-04 15:51:32','2026-05-04 15:51:32'),
(77,125,'create','driver','Created new Driver: ID: 86','86','ID: 86','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\",\"_method\":\"POST\",\"driver_id\":\"86\"}','2026-05-04 15:54:28','2026-05-04 15:54:28'),
(78,125,'create','unit','Created new Vehicle Unit: ID: 160','160','ID: 160','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\"}','2026-05-04 16:01:22','2026-05-04 16:01:22'),
(79,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\",\"unit_id\":\"1\",\"driver_id\":\"107\",\"incident_type\":\"The vehicle unit was taken\\/stolen\",\"severity\":\"critical\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":null,\"description\":\"wd\",\"days_missing\":\"11\",\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"is_driver_fault\":\"1\",\"cause_of_incident\":null}','2026-05-04 16:02:05','2026-05-04 16:02:05'),
(80,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":true}','2026-05-04 16:08:47','2026-05-04 16:08:47'),
(81,125,'create','unit','Created new Vehicle Unit: ID: 1','1','ID: 1','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\"}','2026-05-04 16:11:28','2026-05-04 16:11:28'),
(82,125,'create','unit','Created new Vehicle Unit: ID: 6','6','ID: 6','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\"}','2026-05-04 16:11:34','2026-05-04 16:11:34'),
(83,125,'create','driver','Created new Driver: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\",\"unit_id\":\"2\",\"driver_id\":\"61\",\"incident_type\":\"The vehicle unit was taken\\/stolen\",\"severity\":\"critical\",\"incident_date\":\"2026-05-04\",\"sub_classification\":null,\"traffic_fine_amount\":null,\"description\":\"sq\",\"days_missing\":\"12\",\"third_party_damage_cost\":null,\"total_charge_to_driver\":\"0\",\"is_driver_fault\":\"1\",\"cause_of_incident\":null}','2026-05-04 16:44:22','2026-05-04 16:44:22'),
(84,125,'create','unit','Created new Vehicle Unit: ID: 1','1','ID: 1','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"_token\":\"nOO43IM8i4doOUd7Pzw4dSfcStfug4mZRn9aTvsx\"}','2026-05-04 17:10:20','2026-05-04 17:10:20'),
(85,125,'create','Login','Created new Login: Unknown',NULL,'Unknown','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'{\"email\":\"robertgarcia.owner@gmail.com\",\"password\":\"Admin@2026\",\"remember\":true}','2026-05-04 17:18:00','2026-05-04 17:18:00');
/*!40000 ALTER TABLE `admin_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `message` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `valid_until` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES
(1,NULL,'testing',1,0,NULL,125,'2026-05-14 22:36:53','2026-05-25 09:35:30','2026-05-25 09:35:30'),
(2,NULL,'adadadada',1,0,NULL,125,'2026-05-14 23:02:49','2026-05-25 09:35:27','2026-05-25 09:35:27'),
(3,NULL,'testing',1,0,NULL,125,'2026-05-18 17:27:42','2026-05-25 09:35:18','2026-05-25 09:35:18'),
(4,'testing this','hellowA\r\nA\r\n\r\nA\r\nD\r\nAD\r\nAAD\r\nA\r\nDA\r\nA\r\nD\r\nAD\r\nAD\r\nAD\r\nA\r\nDA\r\nD\r\nAD\r\nADA\r\nDA\r\nDA\r\nD\r\nAD\r\nA',1,0,'2026-05-25 23:59:59',125,'2026-05-25 09:47:37','2026-05-25 10:17:55','2026-05-25 10:17:55'),
(5,'test 2','testing',1,0,'2026-05-25 23:59:59',125,'2026-05-25 10:10:03','2026-05-25 10:17:59','2026-05-25 10:17:59'),
(6,'heloy','testinb gs',1,0,'2026-05-25 23:59:59',125,'2026-05-25 10:47:14','2026-05-25 11:11:33','2026-05-25 11:11:33'),
(7,'test','aaaaaa',1,0,'2026-05-26 23:59:59',125,'2026-05-25 11:11:23','2026-05-25 11:11:30','2026-05-25 11:11:30'),
(8,'test','hahahah',1,1,'2026-05-25 23:59:59',125,'2026-05-25 11:41:48','2026-05-25 19:16:09',NULL),
(9,'metting','metting',1,0,'2026-05-30 23:59:59',125,'2026-05-29 08:46:51','2026-05-29 08:46:51',NULL),
(10,'helo','lo',1,0,'2026-05-31 23:59:59',125,'2026-05-31 14:27:46','2026-05-31 14:27:46',NULL),
(11,'testing','now',1,0,'2026-06-01 23:59:59',125,'2026-06-01 11:37:37','2026-06-01 11:37:37',NULL),
(12,'Checking','please pray for us amen',1,0,'2026-06-10 23:59:59',125,'2026-06-10 10:04:49','2026-06-10 10:08:30','2026-06-10 10:08:30'),
(13,'sfgfggd','sffggffd',1,0,'2026-06-10 23:59:59',125,'2026-06-10 10:09:00','2026-06-10 10:09:00',NULL),
(14,'gtyythygf','fbfbfgfg',1,0,'2026-06-10 23:59:59',125,'2026-06-10 10:33:25','2026-06-10 10:33:25',NULL);
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `boundaries`
--

DROP TABLE IF EXISTS `boundaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `boundaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `expected_driver_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `boundary_amount` decimal(10,2) NOT NULL,
  `actual_boundary` decimal(10,2) DEFAULT NULL,
  `damage_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `debt_payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `debt_balance_snapshot` decimal(10,2) NOT NULL DEFAULT 0.00,
  `debt_payment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_extra_driver` tinyint(1) NOT NULL DEFAULT 0,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `vehicle_damaged` tinyint(1) NOT NULL DEFAULT 0,
  `shortage` decimal(10,2) NOT NULL DEFAULT 0.00,
  `excess` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','excess','shortage') DEFAULT 'pending',
  `has_incentive` tinyint(1) NOT NULL DEFAULT 1,
  `counted_for_incentive` tinyint(1) NOT NULL DEFAULT 1,
  `incentive_released_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `active_date` varchar(191) GENERATED ALWAYS AS (if(`deleted_at` is null,`date`,NULL)) VIRTUAL,
  PRIMARY KEY (`id`),
  KEY `boundaries_unit_id_date_index` (`unit_id`,`date`),
  KEY `boundaries_driver_id_date_index` (`driver_id`,`date`),
  KEY `boundaries_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boundaries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `boundaries` WRITE;
/*!40000 ALTER TABLE `boundaries` DISABLE KEYS */;
INSERT INTO `boundaries` VALUES
(1,160,65,NULL,'2026-04-13',1100.00,500.00,0.00,0.00,0.00,0.00,0,0,0,600.00,0.00,'shortage',1,1,NULL,NULL,'2026-04-12 15:52:35','2026-04-12 15:52:35',18,18,NULL,'2026-04-13'),
(2,112,1,NULL,'2026-04-13',550.00,550.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-04-12 17:00:32','2026-04-12 17:00:32',18,18,NULL,'2026-04-13'),
(3,114,18,NULL,'2026-04-13',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,'EXTRA','2026-04-12 18:03:49','2026-04-12 18:03:49',18,18,NULL,'2026-04-13'),
(4,2,98,NULL,'2026-04-13',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-04-12 18:21:25','2026-04-12 18:21:25',18,18,NULL,'2026-04-13'),
(5,160,64,65,'2026-04-14',0.00,0.00,0.00,0.00,0.00,0.00,0,0,1,0.00,0.00,'paid',0,1,NULL,'oo [Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Unit Sent to Maintenance - Shift Schedule Paused (No Boundary)]','2026-04-14 02:46:34','2026-04-14 04:09:33',18,18,NULL,'2026-04-14'),
(6,133,29,29,'2026-04-14',0.00,0.00,0.00,0.00,0.00,0.00,0,0,1,0.00,0.00,'paid',0,1,NULL,'wadawwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww [Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Unit Sent to Maintenance - Shift Schedule Paused (No Boundary)]','2026-04-14 04:20:59','2026-04-14 04:20:59',18,18,NULL,'2026-04-14'),
(7,1,75,NULL,'2026-04-14',0.00,0.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'qwwwwwwwwwwwdddeqwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww [Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Unit Sent to Maintenance - Shift Schedule Paused (No Boundary)]','2026-04-14 04:24:09','2026-04-14 04:24:09',18,18,NULL,'2026-04-14'),
(8,1,98,NULL,'2026-04-22',600.00,600.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Vehicle Damaged]','2026-04-22 11:36:00','2026-04-22 11:36:00',18,18,NULL,'2026-04-22'),
(9,160,65,65,'2026-04-25',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'ee [Automatic Violation: Low Fuel on Return]','2026-04-25 07:41:18','2026-04-25 07:41:18',18,18,NULL,'2026-04-25'),
(10,124,14,14,'2026-04-26',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,1,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Automatic Violation: Low Fuel on Return]','2026-04-26 06:57:54','2026-04-26 06:57:54',18,18,NULL,'2026-04-26'),
(11,6,98,NULL,'2026-04-26',1000.00,1000.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Automatic Violation: Low Fuel on Return]','2026-04-26 09:19:56','2026-04-26 09:19:56',18,18,NULL,'2026-04-26'),
(12,2,18,NULL,'2026-04-27',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Vehicle Damaged] [Automatic Violation: Low Fuel on Return] [Unit Breakdown: 322.75 hrs x ₱45.83/hr - Schedule Paused]','2026-04-27 00:55:32','2026-04-27 00:55:32',18,18,NULL,'2026-04-27'),
(13,2,105,NULL,'2026-04-30',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,0,NULL,'[Automatic Violation: Late Boundary (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Automatic Violation: Low Fuel on Return] [Unit Breakdown: 79.18 hrs x ₱45.83/hr - Schedule Paused] [Disqualified: Recorded Incident - At-fault Accident]','2026-04-30 08:07:23','2026-04-30 08:07:23',125,125,NULL,'2026-04-30'),
(14,116,5,5,'2026-04-30',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'hahahaaha [Automatic Violation: Late Remittance (Past 10:00 AM)] [Unit Breakdown: 416.72 hrs x ₱50.00/hr - Schedule Paused]','2026-04-30 22:53:49','2026-04-30 22:53:49',125,125,NULL,'2026-04-30'),
(15,1,107,NULL,'2026-05-01',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'aa [Automatic Violation: Low Fuel on Return]','2026-05-01 08:55:30','2026-05-01 08:55:30',125,125,NULL,'2026-05-01'),
(16,160,64,64,'2026-05-01',650.00,650.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-01 09:23:02','2026-05-01 09:23:02',125,125,NULL,'2026-05-01'),
(17,172,86,86,'2026-05-01',1400.00,1400.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-01 13:22:14','2026-05-01 13:22:14',125,125,NULL,'2026-05-01'),
(18,126,19,19,'2026-05-01',700.00,700.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-01 17:50:00','2026-05-01 17:50:00',125,125,NULL,'2026-05-01'),
(19,22,105,NULL,'2026-05-01',1100.00,349.99,0.00,0.00,0.00,0.00,1,0,0,750.01,0.00,'shortage',1,1,NULL,'[Automatic Violation: Short Boundary]','2026-05-01 17:52:25','2026-05-01 17:52:25',125,125,NULL,'2026-05-01'),
(20,120,105,9,'2026-05-01',1400.00,2150.01,0.00,0.00,0.00,0.00,1,0,0,0.00,750.01,'excess',1,1,NULL,NULL,'2026-05-01 17:53:08','2026-05-01 17:53:08',125,125,NULL,'2026-05-01'),
(21,191,73,NULL,'2026-05-02',1000.00,1000.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-02 20:24:42','2026-05-02 20:24:42',125,125,NULL,'2026-05-02'),
(22,1,106,106,'2026-05-02',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'[Prior Incident Violation] [Automatic Violation: Late Remittance (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged] [Unit Breakdown: 35.53 hrs x ₱50.00/hr - Schedule Paused]','2026-05-02 20:28:25','2026-05-02 20:28:25',125,125,NULL,'2026-05-02'),
(23,152,54,54,'2026-05-02',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-02 20:29:54','2026-05-02 20:29:54',125,125,NULL,'2026-05-02'),
(24,113,109,2,'2026-05-02',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'hhhahah_hi [Automatic Violation: Late Remittance (Past 10:00 AM)] [Unit Breakdown: 463.98 hrs x ₱50.00/hr - Schedule Paused]','2026-05-02 22:09:36','2026-05-02 22:09:36',125,125,NULL,'2026-05-02'),
(25,1,107,NULL,'2026-05-03',1000.00,1000.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,'wx3efcx4rbvc3t5v','2026-05-03 21:06:19','2026-05-03 21:06:19',125,125,NULL,'2026-05-03'),
(26,112,2,1,'2026-05-04',550.00,550.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-04 02:57:33','2026-05-04 02:57:33',125,125,NULL,'2026-05-04'),
(27,2,2,NULL,'2026-05-04',900.00,900.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'s [Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-04 13:47:42','2026-05-04 13:47:42',125,125,NULL,'2026-05-04'),
(28,160,113,65,'2026-05-04',1300.00,1300.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',1,1,NULL,'asd','2026-05-04 20:01:06','2026-05-04 20:01:21',125,125,NULL,'2026-05-04'),
(29,112,2,NULL,'2026-05-05',1100.00,1100.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Low Fuel on Return]','2026-05-05 02:22:29','2026-05-05 05:38:32',125,129,NULL,'2026-05-05'),
(30,1,113,NULL,'2026-05-07',1000.00,1000.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',1,1,NULL,'','2026-05-07 23:45:25','2026-05-07 23:46:27',125,125,NULL,'2026-05-07'),
(31,136,124,33,'2026-05-08',1200.00,600.00,0.00,0.00,0.00,0.00,0,0,0,600.00,0.00,'shortage',1,1,NULL,NULL,'2026-05-08 14:21:18','2026-05-09 22:24:04',125,125,NULL,'2026-05-08'),
(32,20,114,NULL,'2026-05-08',1400.00,1400.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-08 23:58:43','2026-05-08 23:58:43',125,125,NULL,'2026-05-08'),
(33,136,33,33,'2026-05-09',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,' [System Hardening Auto-Archive: Concurrency Duplicate Resolved]','2026-05-09 00:09:00','2026-05-11 18:54:46',125,125,'2026-05-26 17:25:43',NULL),
(34,160,65,65,'2026-05-09',0.00,600.00,0.00,0.00,0.00,0.00,0,0,0,0.00,600.00,'excess',0,1,NULL,'Shortage Reconciliation Cash Payment (Paid via Liabilities Manager)','2026-05-09 19:49:41','2026-05-09 19:49:41',125,125,NULL,'2026-05-09'),
(35,136,124,NULL,'2026-04-25',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:05','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-25'),
(36,136,124,NULL,'2026-04-26',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:06','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-26'),
(37,136,124,NULL,'2026-04-27',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:07','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-27'),
(38,136,124,NULL,'2026-04-28',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:08','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-28'),
(39,136,124,NULL,'2026-04-29',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:09','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-29'),
(40,136,124,NULL,'2026-04-30',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:10','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-04-30'),
(41,136,124,NULL,'2026-05-01',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:11','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-05-01'),
(42,136,124,NULL,'2026-05-02',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:12','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-05-02'),
(43,136,124,NULL,'2026-05-03',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:13','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-05-03'),
(44,136,124,NULL,'2026-05-04',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:13','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-05-04'),
(45,136,124,NULL,'2026-05-05',1200.00,1200.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-09 22:18:14','2026-05-09 22:20:42',NULL,NULL,NULL,'2026-05-05'),
(46,136,124,NULL,'2026-05-06',1200.00,600.00,0.00,0.00,0.00,0.00,0,0,0,600.00,0.00,'shortage',1,1,NULL,NULL,'2026-05-09 22:18:15','2026-05-09 22:24:06',NULL,NULL,NULL,'2026-05-06'),
(47,136,124,NULL,'2026-05-07',1200.00,600.00,0.00,0.00,0.00,0.00,0,0,0,600.00,0.00,'shortage',1,1,NULL,NULL,'2026-05-09 22:18:16','2026-05-09 22:24:05',NULL,NULL,NULL,'2026-05-07'),
(48,136,124,NULL,'2026-05-09',1200.00,0.00,0.00,0.00,0.00,0.00,0,0,0,1200.00,0.00,'shortage',1,1,NULL,NULL,'2026-05-09 22:24:04','2026-05-09 22:24:04',NULL,NULL,NULL,'2026-05-09'),
(49,136,124,33,'2026-05-11',600.00,600.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',1,1,NULL,NULL,'2026-05-11 07:32:35','2026-05-11 07:32:35',125,125,NULL,'2026-05-11'),
(50,122,11,11,'2026-05-11',550.00,550.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-11 18:14:37','2026-05-11 18:15:55',125,125,NULL,'2026-05-11'),
(51,1,11,NULL,'2026-05-11',1000.00,1000.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'[Prior Incident Violation] [Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-11 18:17:38','2026-05-11 18:17:38',125,125,NULL,'2026-05-11'),
(52,112,11,NULL,'2026-05-12',1100.00,1000.00,0.00,0.00,0.00,0.00,1,0,0,100.00,0.00,'shortage',0,1,NULL,'[Automatic Violation: Short Boundary]','2026-05-12 00:49:47','2026-05-12 00:49:47',125,125,NULL,'2026-05-12'),
(53,112,124,NULL,'2026-05-25',550.00,550.00,0.00,0.00,0.00,0.00,1,0,1,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)] [Automatic Violation: Vehicle Damaged]','2026-05-25 11:43:16','2026-05-25 11:43:16',125,125,NULL,'2026-05-25'),
(54,122,11,11,'2026-05-29',1300.00,1300.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-05-29 20:39:53','2026-05-29 20:39:53',125,125,NULL,'2026-05-29'),
(55,136,124,124,'2026-06-01',600.00,600.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-01 11:26:57','2026-06-01 11:26:57',125,125,NULL,'2026-06-01'),
(56,122,11,11,'2026-06-10',1300.00,100.00,0.00,0.00,0.00,0.00,0,0,0,1200.00,0.00,'shortage',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)] [Automatic Violation: Short Boundary]','2026-06-10 10:10:03','2026-06-10 10:34:37',125,125,NULL,'2026-06-10'),
(57,122,11,11,'2026-06-11',1000.00,1000.00,0.00,0.00,0.00,0.00,0,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-10 10:12:01','2026-06-11 17:37:21',125,125,NULL,'2026-06-11'),
(58,112,124,NULL,'2026-06-10',1100.00,100.00,0.00,0.00,0.00,0.00,1,0,0,1000.00,0.00,'shortage',0,1,NULL,'[Automatic Violation: Short Boundary] [Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-10 10:14:51','2026-06-10 10:14:51',125,125,NULL,'2026-06-10'),
(59,1,11,NULL,'2026-06-11',1000.00,900.00,0.00,0.00,0.00,0.00,0,0,0,100.00,0.00,'shortage',0,1,NULL,'[Automatic Violation: Short Boundary] [Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-11 17:39:12','2026-06-11 17:39:12',125,125,NULL,'2026-06-11'),
(60,112,11,64,'2026-06-21',900.00,900.00,0.00,0.00,0.00,0.00,1,0,0,0.00,0.00,'paid',0,1,NULL,'[Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-21 20:13:20','2026-06-21 20:13:20',125,125,NULL,'2026-06-21'),
(61,7,11,NULL,'2026-06-24',1200.00,1000.00,0.00,0.00,0.00,0.00,0,0,0,200.00,0.00,'shortage',0,1,NULL,'[Automatic Violation: Short Boundary] [Automatic Violation: Late Remittance (Past 10:00 AM)]','2026-06-24 16:23:09','2026-06-24 16:23:09',125,125,NULL,'2026-06-24');
/*!40000 ALTER TABLE `boundaries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `boundary_rules`
--

DROP TABLE IF EXISTS `boundary_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `boundary_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `start_year` year(4) NOT NULL,
  `end_year` year(4) NOT NULL,
  `regular_rate` decimal(10,2) NOT NULL,
  `sat_discount` decimal(10,2) NOT NULL DEFAULT 100.00,
  `sun_discount` decimal(10,2) NOT NULL,
  `coding_rate` decimal(10,2) NOT NULL,
  `coding_is_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boundary_rules`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `boundary_rules` WRITE;
/*!40000 ALTER TABLE `boundary_rules` DISABLE KEYS */;
INSERT INTO `boundary_rules` VALUES
(1,'Legacy Models (2014 & Below)',2000,2014,1100.00,100.00,200.00,550.00,0,NULL,'2026-04-10 04:56:39','2026-04-10 04:56:39'),
(2,'Standard Models (2015-2017)',2015,2017,1200.00,100.00,200.00,600.00,0,NULL,'2026-04-10 04:56:39','2026-04-10 04:56:39'),
(3,'Modern Models (2018-2020)',2018,2020,1300.00,100.00,200.00,650.00,0,NULL,'2026-04-10 04:56:39','2026-04-10 04:56:39'),
(4,'Premium Models (2021-2023)',2021,2025,1400.00,100.00,200.00,700.00,0,NULL,'2026-04-10 04:56:39','2026-04-10 04:56:39'),
(5,'qewewq',2005,2005,3123.00,0.00,0.00,0.00,0,'2026-05-04 21:49:29','2026-05-01 13:43:34','2026-05-04 21:49:29'),
(6,'Test',2025,2026,11000.00,100.00,200.00,550.00,1,'2026-05-04 21:49:34','2026-05-04 15:24:09','2026-05-04 21:49:34');
/*!40000 ALTER TABLE `boundary_rules` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_to_user_id_created_at_index` (`to_user_id`,`created_at`),
  KEY `chat_messages_from_user_id_created_at_index` (`from_user_id`,`created_at`),
  CONSTRAINT `chat_messages_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chat_messages_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES
(1,125,130,'test',NULL,'2026-06-01 17:54:47','2026-06-01 17:54:47'),
(2,125,160,'hghfghgfh',NULL,'2026-06-10 10:02:24','2026-06-10 10:02:24');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coding_records`
--

DROP TABLE IF EXISTS `coding_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coding_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned NOT NULL,
  `date` date DEFAULT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coding_records`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coding_records` WRITE;
/*!40000 ALTER TABLE `coding_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `coding_records` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coding_rules`
--

DROP TABLE IF EXISTS `coding_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coding_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coding_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `restricted_plate_numbers` varchar(50) NOT NULL,
  `coding_type` enum('full_ban','partial') NOT NULL DEFAULT 'full_ban',
  `allowed_areas` text DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_coding_rules_day_status` (`coding_day`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coding_rules`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coding_rules` WRITE;
/*!40000 ALTER TABLE `coding_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `coding_rules` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coding_violations`
--

DROP TABLE IF EXISTS `coding_violations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coding_violations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `violation_type` varchar(191) NOT NULL,
  `location_name` varchar(191) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `violation_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coding_violations_unit_id_violation_time_index` (`unit_id`,`violation_time`),
  CONSTRAINT `coding_violations_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coding_violations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coding_violations` WRITE;
/*!40000 ALTER TABLE `coding_violations` DISABLE KEYS */;
INSERT INTO `coding_violations` VALUES
(15,136,'Standard Coding','Museo Pambata, Roxas Boulevard, Ermita, Fifth District, Manila, Capital District, Metro Manila, 1000, Philippines',14.57953100,120.97728000,'2026-04-20 00:35:31','2026-04-20 00:46:44','2026-04-20 00:46:44'),
(16,152,'Standard Coding','Trinity Restaurant, President Diosdado Macapagal Boulevard, Metropolitan Park, Barangay 76, Zone 10, District 1, Pasay, Southern Manila District, Metro Manila, 1308, Philippines',14.54516800,120.98607100,'2026-04-20 00:38:30','2026-04-20 00:47:03','2026-04-20 00:47:03');
/*!40000 ALTER TABLE `coding_violations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dashcam_devices`
--

DROP TABLE IF EXISTS `dashcam_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashcam_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `firmware_version` varchar(50) DEFAULT NULL,
  `installation_date` date NOT NULL,
  `status` enum('active','inactive','maintenance','retired') DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_id` (`device_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_dashcam_devices_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashcam_devices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dashcam_devices` WRITE;
/*!40000 ALTER TABLE `dashcam_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashcam_devices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dashcam_events`
--

DROP TABLE IF EXISTS `dashcam_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashcam_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dashcam_device_id` int(11) NOT NULL,
  `event_type` enum('accident','emergency','sudden_brake','hard_acceleration','collision','manual') NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_file_path` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT 0,
  `duration` int(11) DEFAULT 0 COMMENT 'Duration in seconds',
  `location_lat` decimal(10,8) DEFAULT NULL,
  `location_lng` decimal(11,8) DEFAULT NULL,
  `speed_before` decimal(5,2) DEFAULT 0.00,
  `speed_after` decimal(5,2) DEFAULT 0.00,
  `g_force` decimal(4,2) DEFAULT 0.00,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `uploaded` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_dashcam_device_id` (`dashcam_device_id`),
  KEY `idx_timestamp` (`timestamp`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_device_timestamp` (`dashcam_device_id`,`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashcam_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dashcam_events` WRITE;
/*!40000 ALTER TABLE `dashcam_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashcam_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dashcam_footage`
--

DROP TABLE IF EXISTS `dashcam_footage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashcam_footage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `camera_type` enum('front','interior','rear') DEFAULT 'front',
  `is_incident` tinyint(1) DEFAULT 0,
  `incident_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `incident_id` (`incident_id`),
  CONSTRAINT `dashcam_footage_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `dashcam_footage_ibfk_2` FOREIGN KEY (`incident_id`) REFERENCES `driver_behavior` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashcam_footage`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dashcam_footage` WRITE;
/*!40000 ALTER TABLE `dashcam_footage` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashcam_footage` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dashcam_settings`
--

DROP TABLE IF EXISTS `dashcam_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashcam_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dashcam_device_id` int(11) NOT NULL,
  `video_quality` int(11) DEFAULT 1080 COMMENT 'Video quality (720, 1080, 4K)',
  `recording_mode` enum('continuous','event','manual') DEFAULT 'continuous',
  `event_recording_enabled` tinyint(1) DEFAULT 1,
  `g_sensor_enabled` tinyint(1) DEFAULT 1,
  `wifi_enabled` tinyint(1) DEFAULT 1,
  `auto_upload_enabled` tinyint(1) DEFAULT 1,
  `storage_alert` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_dashcam_device_id` (`dashcam_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashcam_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dashcam_settings` WRITE;
/*!40000 ALTER TABLE `dashcam_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashcam_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `dashcam_test_logs`
--

DROP TABLE IF EXISTS `dashcam_test_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashcam_test_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dashcam_device_id` int(11) NOT NULL,
  `test_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`test_result`)),
  `test_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dashcam_device_id` (`dashcam_device_id`),
  KEY `idx_test_date` (`test_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashcam_test_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `dashcam_test_logs` WRITE;
/*!40000 ALTER TABLE `dashcam_test_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashcam_test_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `device_alerts`
--

DROP TABLE IF EXISTS `device_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_type` enum('gps','dashcam') NOT NULL,
  `device_id` int(11) NOT NULL,
  `alert_type` enum('offline','low_battery','storage_full','error','maintenance') NOT NULL,
  `alert_message` text NOT NULL,
  `alert_level` enum('info','warning','critical') NOT NULL,
  `resolved` tinyint(1) DEFAULT 0,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `resolved_by` (`resolved_by`),
  KEY `idx_device_type` (`device_type`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_alert_type` (`alert_type`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_resolved` (`resolved`),
  CONSTRAINT `device_alerts_ibfk_1` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_alerts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `device_alerts` WRITE;
/*!40000 ALTER TABLE `device_alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_alerts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `device_import_history`
--

DROP TABLE IF EXISTS `device_import_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_import_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `import_type` enum('gps','dashcam','both') NOT NULL,
  `device_count` int(11) NOT NULL,
  `import_status` enum('success','partial','failed') NOT NULL,
  `import_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`import_details`)),
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_import_type` (`import_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_import_history`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `device_import_history` WRITE;
/*!40000 ALTER TABLE `device_import_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_import_history` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `driver_balances`
--

DROP TABLE IF EXISTS `driver_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_balances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) NOT NULL,
  `incident_id` int(11) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `remaining_balance` decimal(12,2) NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `driver_balances_driver_id_foreign` (`driver_id`),
  KEY `driver_balances_incident_id_foreign` (`incident_id`),
  CONSTRAINT `driver_balances_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `driver_balances_incident_id_foreign` FOREIGN KEY (`incident_id`) REFERENCES `driver_behavior` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_balances`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `driver_balances` WRITE;
/*!40000 ALTER TABLE `driver_balances` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_balances` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `driver_behavior`
--

DROP TABLE IF EXISTS `driver_behavior`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_behavior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) DEFAULT NULL,
  `driver_id` int(11) NOT NULL,
  `incident_type` varchar(191) DEFAULT NULL,
  `sub_classification` varchar(191) DEFAULT NULL,
  `days_missing` smallint(5) unsigned DEFAULT NULL,
  `traffic_fine_amount` decimal(10,2) DEFAULT NULL,
  `sub_type` varchar(191) DEFAULT NULL,
  `cause_of_incident` varchar(191) DEFAULT NULL,
  `severity` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `third_party_name` varchar(191) DEFAULT NULL,
  `third_party_vehicle` varchar(191) DEFAULT NULL,
  `own_unit_damage_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `third_party_damage_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_driver_fault` tinyint(1) NOT NULL DEFAULT 0,
  `total_charge_to_driver` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remaining_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `incentive_released_at` date DEFAULT NULL,
  `charge_status` varchar(191) DEFAULT 'none',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `incident_date` date DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `missing_days_reported` smallint(5) unsigned DEFAULT NULL,
  `stolen_driver_detail_name` varchar(255) DEFAULT NULL,
  `stolen_driver_detail_contact` varchar(64) DEFAULT NULL,
  `stolen_driver_license_no` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `driver_id` (`driver_id`),
  CONSTRAINT `driver_behavior_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_behavior`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `driver_behavior` WRITE;
/*!40000 ALTER TABLE `driver_behavior` DISABLE KEYS */;
INSERT INTO `driver_behavior` VALUES
(3,133,29,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-14 04:20:58',NULL,'','2026-04-14 04:20:58',NULL,NULL,NULL,NULL,NULL,NULL),
(4,133,29,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-14 04:20:58',NULL,'','2026-04-14 04:20:58',NULL,NULL,NULL,NULL,NULL,NULL),
(5,1,75,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-14 04:24:09',NULL,'','2026-04-14 04:24:09',NULL,'2026-04-22 09:42:48',NULL,NULL,NULL,NULL),
(6,1,75,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-14 04:24:09',NULL,'','2026-04-14 04:24:09',NULL,'2026-04-22 09:43:06',NULL,NULL,NULL,NULL),
(7,1,75,'Accident',NULL,NULL,NULL,NULL,NULL,'critical','Subagent Test Incident - Final Fix Verification','Juan Dela Cruz','Sedan',1500.00,0.00,1,1500.00,1500.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-04-22 02:34:12','2026-04-22','','2026-04-22 02:34:12',NULL,NULL,NULL,NULL,NULL,NULL),
(8,160,64,'Other',NULL,NULL,NULL,NULL,NULL,'high','qdwdqwd - VERIFIED BY AIUpdated Incident Details - Final Check',NULL,NULL,650.00,0.00,1,650.00,650.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-04-22 03:09:28','2026-04-22','','2026-04-22 03:09:28',NULL,NULL,NULL,NULL,NULL,NULL),
(9,1,98,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-22 11:36:00',NULL,'','2026-04-22 11:36:00',NULL,NULL,NULL,NULL,NULL,NULL),
(10,112,1,'Other',NULL,NULL,NULL,NULL,NULL,'medium','bgugyuu',NULL,NULL,938.00,0.00,1,938.00,138.00,800.00,NULL,'pending',0.00000000,0.00000000,'2026-04-22 11:43:44','2026-04-22','','2026-04-22 11:43:44',NULL,NULL,NULL,NULL,NULL,NULL),
(11,1,98,'Coding Violation',NULL,NULL,NULL,NULL,NULL,'medium','Verif',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-22 11:51:27','2026-04-22','','2026-04-22 11:51:27',NULL,NULL,NULL,NULL,NULL,NULL),
(12,160,65,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-25 07:41:18','2026-04-25','','2026-04-25 07:41:18',NULL,NULL,NULL,NULL,NULL,NULL),
(13,124,14,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 06:57:54','2026-04-26','','2026-04-26 06:57:54',NULL,NULL,NULL,NULL,NULL,NULL),
(14,124,14,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 06:57:54',NULL,'','2026-04-26 06:57:54',NULL,NULL,NULL,NULL,NULL,NULL),
(15,124,14,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 06:57:54','2026-04-26','','2026-04-26 06:57:54',NULL,NULL,NULL,NULL,NULL,NULL),
(16,124,14,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-04-26 06:57:54',NULL,NULL,'2026-04-26 06:57:54',NULL,NULL,NULL,NULL,NULL,NULL),
(17,6,98,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 09:19:56','2026-04-26','','2026-04-26 09:19:56',NULL,NULL,NULL,NULL,NULL,NULL),
(18,6,98,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 09:19:56',NULL,'','2026-04-26 09:19:56',NULL,NULL,NULL,NULL,NULL,NULL),
(19,6,98,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-26 09:19:56','2026-04-26','','2026-04-26 09:19:56',NULL,NULL,NULL,NULL,NULL,NULL),
(20,6,98,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-04-26 09:19:56',NULL,NULL,'2026-04-26 09:19:56',NULL,NULL,NULL,NULL,NULL,NULL),
(21,112,1,'Other',NULL,NULL,NULL,NULL,NULL,'medium','ehhe',NULL,NULL,64999350.00,0.00,1,64999350.00,1998.00,0.00,NULL,'pending',0.00000000,0.00000000,'2026-04-26 17:01:56','2026-04-27','','2026-04-26 17:01:56','2026-05-04 18:05:33','2026-05-04 18:05:33',NULL,NULL,NULL,NULL),
(22,2,18,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-27 00:55:32',NULL,'','2026-04-27 00:55:32',NULL,NULL,NULL,NULL,NULL,NULL),
(23,2,18,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-27 00:55:32','2026-04-27','','2026-04-27 00:55:32',NULL,NULL,NULL,NULL,NULL,NULL),
(24,2,18,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Breakdown]: Unit broke down after 322.75 hrs on shift.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-27 00:55:32',NULL,'','2026-04-27 00:55:32',NULL,NULL,NULL,NULL,NULL,NULL),
(25,112,1,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'medium','bg',NULL,NULL,1200.00,0.00,1,1200.00,500.00,700.00,NULL,'pending',0.00000000,0.00000000,'2026-04-27 01:00:03','2026-04-27','','2026-04-27 01:00:03',NULL,NULL,NULL,NULL,NULL,NULL),
(26,132,27,'Accident',NULL,NULL,NULL,NULL,NULL,'high','Lashing',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-27 06:41:27','2026-04-27','','2026-04-27 06:41:27',NULL,NULL,NULL,NULL,NULL,NULL),
(27,160,64,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'critical','Reported by LTFRB  of fare contract',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-27 07:24:05','2026-04-27','','2026-04-27 07:24:05',NULL,NULL,NULL,NULL,NULL,NULL),
(28,160,64,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit NEF 4940 is overdue for >48 hours (Missing since Apr 26, 2026). Investigation required.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-28 07:58:21','2026-04-26','','2026-04-28 07:58:21',NULL,NULL,NULL,NULL,NULL,NULL),
(29,124,15,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit CAV 9662 is overdue for >48 hours (Missing since Apr 27, 2026). Investigation required.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-29 08:05:13','2026-04-27','','2026-04-29 08:05:13',NULL,NULL,NULL,NULL,NULL,NULL),
(30,2,105,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 08:07:23','2026-04-30','','2026-04-30 08:07:23',NULL,'2026-04-30 23:32:04',NULL,NULL,NULL,NULL),
(31,2,105,'other',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 08:07:23',NULL,'','2026-04-30 08:07:23',NULL,NULL,NULL,NULL,NULL,NULL),
(32,2,105,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 08:07:23','2026-04-30','','2026-04-30 08:07:23',NULL,NULL,NULL,NULL,NULL,NULL),
(33,2,105,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Breakdown]: Unit broke down after 79.18 hrs on shift.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 08:07:23',NULL,'','2026-04-30 08:07:23',NULL,NULL,NULL,NULL,NULL,NULL),
(34,2,105,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-04-30 08:07:23',NULL,NULL,'2026-04-30 08:07:23',NULL,NULL,NULL,NULL,NULL,NULL),
(35,160,105,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','engot',NULL,NULL,17000.00,0.00,1,17000.00,17000.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-04-30 10:01:09','2026-04-30','','2026-04-30 10:01:09',NULL,NULL,NULL,NULL,NULL,NULL),
(36,1,6,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','masakit paa ko',NULL,NULL,1700.00,0.00,1,1700.00,0.00,1700.00,NULL,'pending',0.00000000,0.00000000,'2026-04-30 22:00:26','2026-04-08','','2026-04-30 14:00:26',NULL,'2026-04-30 23:30:11',NULL,NULL,NULL,NULL),
(37,116,5,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Breakdown]: Unit broke down after 416.72 hrs on shift.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 14:53:49',NULL,'','2026-04-30 22:53:49',NULL,'2026-04-30 23:30:41',NULL,NULL,NULL,NULL),
(38,116,5,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-04-30 22:53:49',NULL,NULL,'2026-04-30 22:53:49',NULL,'2026-04-30 23:30:02',NULL,NULL,NULL,NULL),
(39,1,93,'Accident',NULL,NULL,NULL,NULL,NULL,'critical','HHHHHHHAHAAAHHHHHHHHHHHHHH',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-04-30 23:33:59','2026-04-30','','2026-04-30 15:33:59',NULL,NULL,NULL,NULL,NULL,NULL),
(40,1,107,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel]: Driver returned the unit without refueling (Kulang sa gas).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-01 08:55:30','2026-05-01','','2026-05-01 08:55:30',NULL,NULL,NULL,NULL,NULL,NULL),
(41,51,18,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','aa',NULL,NULL,850.00,0.00,1,850.00,850.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-05-01 11:39:15','2026-05-01','','2026-05-01 03:39:15',NULL,NULL,NULL,NULL,NULL,NULL),
(42,126,19,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-01 17:50:00',NULL,NULL,'2026-05-01 17:50:00',NULL,NULL,NULL,NULL,NULL,NULL),
(43,22,105,'short_boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱750.01 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-01 17:52:25',NULL,NULL,'2026-05-01 17:52:25',NULL,NULL,NULL,NULL,NULL,NULL),
(44,2,18,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','contracting',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 06:43:53','2026-05-02','','2026-05-01 22:43:53',NULL,NULL,NULL,NULL,NULL,NULL),
(45,2,18,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','contracting',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 06:58:26','2026-05-02','','2026-05-01 22:58:26',NULL,NULL,NULL,NULL,NULL,NULL),
(46,2,18,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','contracting',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 06:59:45','2026-05-02','','2026-05-01 22:59:45',NULL,NULL,NULL,NULL,NULL,NULL),
(47,2,18,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','contracting',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 06:59:52','2026-05-02','','2026-05-01 22:59:52',NULL,NULL,NULL,NULL,NULL,NULL),
(48,2,18,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','contracting',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 07:01:00','2026-05-02','','2026-05-01 23:01:00',NULL,NULL,NULL,NULL,NULL,NULL),
(49,1,106,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','YAWA',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 07:02:22','2026-05-02','','2026-05-01 23:02:22',NULL,NULL,NULL,NULL,NULL,NULL),
(50,1,106,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','YAWA',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 07:03:28','2026-05-02','','2026-05-01 23:03:28',NULL,NULL,NULL,NULL,NULL,NULL),
(51,112,1,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','missing unit',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 10:50:40','2026-05-02','','2026-05-02 02:50:40',NULL,NULL,NULL,NULL,NULL,NULL),
(52,112,1,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','missing unit',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 10:52:58','2026-05-02','','2026-05-02 02:52:58',NULL,NULL,NULL,NULL,NULL,NULL),
(53,160,64,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','missing unit',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 10:57:23','2026-05-02','','2026-05-02 02:57:23',NULL,NULL,NULL,NULL,NULL,NULL),
(54,117,6,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','nabanggnya bubu',NULL,NULL,0.00,0.00,1,1000.00,0.00,1000.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 12:26:11','2026-05-02','','2026-05-02 04:26:11',NULL,NULL,NULL,NULL,NULL,NULL),
(55,2,20,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','aa',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 12:50:41','2026-05-02','','2026-05-02 04:50:41',NULL,NULL,NULL,NULL,NULL,NULL),
(56,146,47,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','aaatdug',NULL,NULL,650.00,0.00,1,650.00,650.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-05-02 12:52:14','2026-05-02','','2026-05-02 04:52:14',NULL,NULL,NULL,NULL,NULL,NULL),
(57,126,19,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','TARUB',NULL,NULL,21110.00,0.00,1,21110.00,21110.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-05-02 13:07:54','2026-05-02','','2026-05-02 05:07:54',NULL,NULL,NULL,NULL,NULL,NULL),
(58,158,61,'Traffic Violation',NULL,NULL,NULL,NULL,NULL,'medium','NANGUNGUPAL',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:12:26','2026-05-02','','2026-05-02 05:12:26',NULL,NULL,NULL,NULL,NULL,NULL),
(59,144,45,'Traffic Violation',NULL,NULL,NULL,NULL,NULL,'medium','OVERTAKE',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:28:56','2026-05-02','','2026-05-02 05:28:56',NULL,NULL,NULL,NULL,NULL,NULL),
(60,126,19,'Traffic Violation',NULL,NULL,NULL,NULL,NULL,'medium','aa',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:43:54','2026-05-02','','2026-05-02 05:43:54',NULL,NULL,NULL,NULL,NULL,NULL),
(61,2,22,'Traffic Violation',NULL,NULL,NULL,NULL,NULL,'medium','aaaa',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:48:12','2026-05-02','','2026-05-02 05:48:12',NULL,NULL,NULL,NULL,NULL,NULL),
(62,21,21,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','zzads',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:50:02','2026-05-02','','2026-05-02 05:50:02',NULL,NULL,NULL,NULL,NULL,NULL),
(63,125,17,'Absent / No Show',NULL,NULL,NULL,NULL,NULL,'low','adfa',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:55:06','2026-05-02','','2026-05-02 05:55:06',NULL,NULL,NULL,NULL,NULL,NULL),
(64,154,56,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','aa',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 13:59:53','2026-05-02','','2026-05-02 05:59:53',NULL,NULL,NULL,NULL,NULL,NULL),
(65,120,9,'Hard Braking',NULL,NULL,NULL,NULL,NULL,'low','aasdwad',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 14:07:13','2026-05-02','','2026-05-02 06:07:13',NULL,NULL,NULL,NULL,NULL,NULL),
(66,2,82,'Traffic Violation',NULL,NULL,NULL,NULL,NULL,'medium','adaw',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 14:11:41','2026-05-02','','2026-05-02 06:11:41',NULL,NULL,NULL,NULL,NULL,NULL),
(67,8,34,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','qaqwe',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 14:15:16','2026-05-02','','2026-05-02 06:15:16',NULL,NULL,NULL,NULL,NULL,NULL),
(68,146,47,'Speeding',NULL,NULL,0.00,NULL,NULL,'high','dqwew',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 15:00:51','2026-05-02','','2026-05-02 07:00:51',NULL,NULL,NULL,NULL,NULL,NULL),
(69,191,73,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:24:42','2026-05-02',NULL,'2026-05-02 12:24:42',NULL,NULL,NULL,NULL,NULL,NULL),
(70,191,73,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:24:42','2026-05-02',NULL,'2026-05-02 12:24:42',NULL,NULL,NULL,NULL,NULL,NULL),
(71,1,106,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:28:25','2026-05-02',NULL,'2026-05-02 12:28:25',NULL,NULL,NULL,NULL,NULL,NULL),
(72,1,106,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:28:25','2026-05-02',NULL,'2026-05-02 12:28:25',NULL,NULL,NULL,NULL,NULL,NULL),
(73,1,106,'Other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Breakdown]: Unit broke down after 35.53 hrs. No boundary collected.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:28:25','2026-05-02',NULL,'2026-05-02 12:28:25',NULL,NULL,NULL,NULL,NULL,NULL),
(74,1,106,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:28:25','2026-05-02',NULL,'2026-05-02 12:28:25',NULL,NULL,NULL,NULL,NULL,NULL),
(75,152,54,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:29:54','2026-05-02',NULL,'2026-05-02 12:29:54',NULL,NULL,NULL,NULL,NULL,NULL),
(76,152,54,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 20:29:54','2026-05-02',NULL,'2026-05-02 12:29:54',NULL,NULL,NULL,NULL,NULL,NULL),
(77,113,109,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 22:09:36','2026-05-02',NULL,'2026-05-02 14:09:36',NULL,NULL,NULL,NULL,NULL,NULL),
(78,113,109,'Other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Breakdown]: Unit broke down after 463.98 hrs. No boundary collected.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 22:09:36','2026-05-02',NULL,'2026-05-02 14:09:36',NULL,NULL,NULL,NULL,NULL,NULL),
(79,113,109,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-02 22:09:36','2026-05-02',NULL,'2026-05-02 14:09:36',NULL,NULL,NULL,NULL,NULL,NULL),
(80,114,3,'Passenger Complaint',NULL,NULL,NULL,NULL,NULL,'critical','ahahhhah\r\n-***',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-02 22:23:40','2026-05-02','','2026-05-02 14:23:40',NULL,'2026-05-02 22:24:55',NULL,NULL,NULL,NULL),
(81,160,65,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','adwawdawd',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 00:22:04','2026-05-04','','2026-05-03 16:22:04',NULL,NULL,NULL,NULL,NULL,NULL),
(82,160,18,'The vehicle unit was taken/stolen',NULL,15,NULL,NULL,NULL,'critical','wqe',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 00:39:48','2026-05-04','','2026-05-03 16:39:48',NULL,NULL,NULL,NULL,NULL,NULL),
(83,160,6,'The vehicle unit was taken/stolen',NULL,123,NULL,NULL,NULL,'critical','qwe',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 00:41:06','2026-05-04','','2026-05-03 16:41:06',NULL,NULL,NULL,NULL,NULL,NULL),
(84,1,107,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'high','JK',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 02:40:17','2026-05-04','','2026-05-03 18:40:17',NULL,NULL,NULL,NULL,NULL,NULL),
(85,160,86,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','wadw',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 03:38:44','2026-05-04','','2026-05-03 19:38:44',NULL,NULL,NULL,NULL,NULL,NULL),
(86,160,73,'The vehicle unit was taken/stolen',NULL,100,NULL,NULL,NULL,'critical','aw',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 03:39:33','2026-05-04','','2026-05-03 19:39:33',NULL,NULL,NULL,NULL,NULL,NULL),
(87,12,61,'Speeding',NULL,NULL,2000.00,NULL,NULL,'high','hhhah',NULL,NULL,0.00,0.00,0,2000.00,0.00,2000.00,NULL,'pending',0.00000000,0.00000000,'2026-05-04 08:58:14','2026-05-04','','2026-05-04 00:58:14',NULL,'2026-05-04 08:58:34',NULL,NULL,NULL,NULL),
(88,172,87,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit NFH 3664 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 13:33:50','2026-05-02','','2026-05-04 13:33:50',NULL,NULL,NULL,NULL,NULL,NULL),
(89,2,2,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-04 13:47:42','2026-05-04',NULL,'2026-05-04 05:47:42',NULL,NULL,NULL,NULL,NULL,NULL),
(90,2,2,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-04 13:47:42','2026-05-04',NULL,'2026-05-04 05:47:42',NULL,NULL,NULL,NULL,NULL,NULL),
(91,1,107,'The vehicle unit was taken/stolen',NULL,11,NULL,NULL,NULL,'critical','wd',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 16:02:05','2026-05-04','','2026-05-04 08:02:05',NULL,NULL,NULL,NULL,NULL,NULL),
(92,2,61,'The vehicle unit was taken/stolen',NULL,12,NULL,NULL,NULL,'critical','sq',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 16:44:22','2026-05-04','','2026-05-04 08:44:22',NULL,NULL,NULL,NULL,NULL,NULL),
(93,126,20,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit CBM 1979 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 17:53:05','2026-05-02','','2026-05-04 17:53:05',NULL,NULL,NULL,NULL,NULL,NULL),
(94,120,9,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit ASA 6135 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-04 18:22:31','2026-05-02','','2026-05-04 18:22:31',NULL,NULL,NULL,NULL,NULL,NULL),
(95,160,113,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-04 20:01:06','2026-05-04',NULL,'2026-05-04 12:01:06',NULL,NULL,NULL,NULL,NULL,NULL),
(96,160,113,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-04 20:01:06','2026-05-04',NULL,'2026-05-04 12:01:06',NULL,NULL,NULL,NULL,NULL,NULL),
(97,160,31,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','dsfefer',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-05 00:51:58','2026-05-05','','2026-05-04 16:51:58',NULL,NULL,NULL,NULL,NULL,NULL),
(98,160,5,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','wadw',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-05 01:12:11','2026-05-05','','2026-05-04 17:12:11',NULL,NULL,13,'Henry Belen','091231312312312',NULL),
(99,112,2,'other',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Low Fuel/Update]: Driver returned unit without refueling (Update).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-05 05:38:32',NULL,NULL,'2026-05-05 05:38:32',NULL,NULL,NULL,NULL,NULL,NULL),
(100,2,86,'Accident',NULL,NULL,NULL,NULL,NULL,'high','Test',NULL,NULL,850.00,0.00,1,850.00,0.00,850.00,NULL,'pending',0.00000000,0.00000000,'2026-05-05 09:15:17','2026-05-05','','2026-05-05 01:15:17',NULL,'2026-05-11 20:14:38',NULL,NULL,NULL,NULL),
(101,152,54,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit NCW 5011 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-06 11:30:15','2026-05-03','','2026-05-06 11:30:15',NULL,NULL,NULL,NULL,NULL,NULL),
(102,160,106,'The vehicle unit was taken/stolen',NULL,NULL,NULL,NULL,NULL,'critical','j',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-06 18:54:05','2026-05-06','','2026-05-06 10:54:05',NULL,NULL,5,'dian Santiago Dian','09158112931','guyt8t8t87'),
(103,172,87,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit NFH 3664 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-07 21:57:01','2026-05-05','','2026-05-07 21:57:01',NULL,NULL,NULL,NULL,NULL,NULL),
(104,20,114,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-08 23:58:43','2026-05-08',NULL,'2026-05-08 15:58:43',NULL,NULL,NULL,NULL,NULL,NULL),
(105,20,114,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-08 23:58:43','2026-05-08',NULL,'2026-05-08 15:58:43',NULL,NULL,NULL,NULL,NULL,NULL),
(106,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-11 18:14:37','2026-05-11',NULL,'2026-05-11 10:14:37',NULL,NULL,NULL,NULL,NULL,NULL),
(107,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-11 18:14:37','2026-05-11',NULL,'2026-05-11 10:14:37',NULL,NULL,NULL,NULL,NULL,NULL),
(108,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-11 18:15:55',NULL,NULL,'2026-05-11 18:15:55',NULL,NULL,NULL,NULL,NULL,NULL),
(109,1,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-11 18:17:38','2026-05-11',NULL,'2026-05-11 10:17:38',NULL,NULL,NULL,NULL,NULL,NULL),
(110,1,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-11 18:17:38','2026-05-11',NULL,'2026-05-11 10:17:38',NULL,NULL,NULL,NULL,NULL,NULL),
(111,112,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱1,000.00 instead of ₱1,100.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-12 00:49:47','2026-05-12',NULL,'2026-05-11 16:49:47',NULL,NULL,NULL,NULL,NULL,NULL),
(112,112,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱100.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-12 00:49:47','2026-05-12',NULL,'2026-05-11 16:49:47',NULL,NULL,NULL,NULL,NULL,NULL),
(113,136,124,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit DCQ 1551 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-05-14 22:23:56','2026-05-12','','2026-05-14 22:23:56',NULL,NULL,NULL,NULL,NULL,NULL),
(114,112,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-25 11:43:16','2026-05-25',NULL,'2026-05-25 03:43:16',NULL,NULL,NULL,NULL,NULL,NULL),
(115,112,124,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover.',NULL,NULL,0.00,0.00,1,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-25 11:43:16','2026-05-25',NULL,'2026-05-25 03:43:16',NULL,NULL,NULL,NULL,NULL,NULL),
(116,112,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-25 11:43:16','2026-05-25',NULL,'2026-05-25 03:43:16',NULL,NULL,NULL,NULL,NULL,NULL),
(117,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-29 20:39:53','2026-05-29',NULL,'2026-05-29 12:39:53',NULL,NULL,NULL,NULL,NULL,NULL),
(118,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-05-29 20:39:53','2026-05-29',NULL,'2026-05-29 12:39:53',NULL,NULL,NULL,NULL,NULL,NULL),
(119,136,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-01 11:26:57','2026-06-01',NULL,'2026-06-01 03:26:57',NULL,NULL,NULL,NULL,NULL,NULL),
(120,136,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-01 11:26:57','2026-06-01',NULL,'2026-06-01 03:26:57',NULL,NULL,NULL,NULL,NULL,NULL),
(121,172,86,'Administrative Ban',NULL,NULL,NULL,NULL,'Administrative Action','critical','hhh',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-02 18:10:53','2026-06-02','','2026-06-02 18:10:53','2026-06-02 18:10:53',NULL,NULL,NULL,NULL,NULL),
(122,172,86,'Administrative Suspension',NULL,NULL,NULL,NULL,'Administrative Action','critical','hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-02 18:21:58','2026-06-02','','2026-06-02 18:21:58','2026-06-02 18:21:58',NULL,NULL,NULL,NULL,NULL),
(123,172,86,'Administrative Suspension',NULL,NULL,NULL,NULL,'Administrative Action','critical','sssssssssssssssssssssssssssssssssssssssssssssssssss                                                                          dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd                                                                                                                                                                                                      ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-02 19:03:22','2026-06-02','','2026-06-02 19:03:22','2026-06-02 19:03:22',NULL,NULL,NULL,NULL,NULL),
(124,136,124,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit DCQ 1551 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-05 16:55:07','2026-06-02','','2026-06-05 16:55:07',NULL,NULL,NULL,NULL,NULL,NULL),
(125,122,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱300.00 instead of ₱1,300.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:10:03','2026-06-10',NULL,'2026-06-10 02:10:03',NULL,NULL,NULL,NULL,NULL,NULL),
(126,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:10:03','2026-06-10',NULL,'2026-06-10 02:10:03',NULL,NULL,NULL,NULL,NULL,NULL),
(127,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:10:03','2026-06-10',NULL,'2026-06-10 02:10:03',NULL,NULL,NULL,NULL,NULL,NULL),
(128,122,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱1,000.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:10:03','2026-06-10',NULL,'2026-06-10 02:10:03',NULL,NULL,NULL,NULL,NULL,NULL),
(129,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:11:02',NULL,NULL,'2026-06-10 10:11:02',NULL,NULL,NULL,NULL,NULL,NULL),
(130,122,11,'short_boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage/Update]: Boundary update resulted in a ₱700.00 shortage.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:11:02',NULL,NULL,'2026-06-10 10:11:02',NULL,NULL,NULL,NULL,NULL,NULL),
(131,122,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱100.00 instead of ₱1,300.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:12:01','2026-06-11',NULL,'2026-06-10 02:12:01',NULL,NULL,NULL,NULL,NULL,NULL),
(132,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:12:01','2026-06-11',NULL,'2026-06-10 02:12:01',NULL,NULL,NULL,NULL,NULL,NULL),
(133,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:12:01','2026-06-11',NULL,'2026-06-10 02:12:01',NULL,NULL,NULL,NULL,NULL,NULL),
(134,122,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱1,200.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:12:01','2026-06-11',NULL,'2026-06-10 02:12:01',NULL,NULL,NULL,NULL,NULL,NULL),
(135,112,124,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱100.00 instead of ₱1,100.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:14:51','2026-06-10',NULL,'2026-06-10 02:14:51',NULL,NULL,NULL,NULL,NULL,NULL),
(136,112,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:14:51','2026-06-10',NULL,'2026-06-10 02:14:51',NULL,NULL,NULL,NULL,NULL,NULL),
(137,112,124,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:14:51','2026-06-10',NULL,'2026-06-10 02:14:51',NULL,NULL,NULL,NULL,NULL,NULL),
(138,112,124,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱1,000.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:14:51','2026-06-10',NULL,'2026-06-10 02:14:51',NULL,NULL,NULL,NULL,NULL,NULL),
(139,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:16:27',NULL,NULL,'2026-06-10 10:16:27',NULL,NULL,NULL,NULL,NULL,NULL),
(140,122,11,'short_boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage/Update]: Boundary update resulted in a ₱800.00 shortage.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:16:27',NULL,NULL,'2026-06-10 10:16:27',NULL,NULL,NULL,NULL,NULL,NULL),
(141,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:34:37',NULL,NULL,'2026-06-10 10:34:37',NULL,NULL,NULL,NULL,NULL,NULL),
(142,122,11,'short_boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage/Update]: Boundary update resulted in a ₱1,200.00 shortage.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-10 10:34:37',NULL,NULL,'2026-06-10 10:34:37',NULL,NULL,NULL,NULL,NULL,NULL),
(143,122,11,'Administrative Suspension',NULL,NULL,NULL,NULL,'Administrative Action','critical','TESTAAA',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-11 16:45:59','2026-06-11','','2026-06-11 16:45:59','2026-06-11 16:45:59',NULL,NULL,NULL,NULL,NULL),
(144,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:31:41',NULL,NULL,'2026-06-11 17:31:41',NULL,NULL,NULL,NULL,NULL,NULL),
(145,122,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late/Update]: Boundary update marked as Late Remittance (Past 10:00 AM).',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:37:21',NULL,NULL,'2026-06-11 17:37:21',NULL,NULL,NULL,NULL,NULL,NULL),
(146,1,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱900.00 instead of ₱1,000.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:39:12','2026-06-11',NULL,'2026-06-11 09:39:12',NULL,NULL,NULL,NULL,NULL,NULL),
(147,1,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:39:12','2026-06-11',NULL,'2026-06-11 09:39:12',NULL,NULL,NULL,NULL,NULL,NULL),
(148,1,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:39:12','2026-06-11',NULL,'2026-06-11 09:39:12',NULL,NULL,NULL,NULL,NULL,NULL),
(149,1,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱100.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-11 17:39:12','2026-06-11',NULL,'2026-06-11 09:39:12',NULL,NULL,NULL,NULL,NULL,NULL),
(150,112,64,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit AAA 4591 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-13 12:45:02','2026-06-11','','2026-06-13 12:45:02',NULL,NULL,NULL,NULL,NULL,NULL),
(151,122,11,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit CAV 2607 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-13 12:45:02','2026-06-11','','2026-06-13 12:45:02',NULL,NULL,NULL,NULL,NULL,NULL),
(152,122,11,'Administrative Suspension',NULL,NULL,NULL,NULL,'Administrative Action','critical','tamad',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-20 17:04:25','2026-06-20','','2026-06-20 17:04:25','2026-06-20 17:04:25',NULL,NULL,NULL,NULL,NULL),
(153,7,11,'Administrative Ban',NULL,NULL,NULL,NULL,'Administrative Action','critical','test lng sa app',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-20 18:19:50','2026-06-20','','2026-06-20 18:19:50','2026-06-20 18:19:50',NULL,NULL,NULL,NULL,NULL),
(154,7,11,'Administrative Ban',NULL,NULL,NULL,NULL,'Administrative Action','critical','testing',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-20 18:49:05','2026-06-20','','2026-06-20 18:49:05','2026-06-20 18:49:05',NULL,NULL,NULL,NULL,NULL),
(155,112,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-21 20:13:20','2026-06-21',NULL,'2026-06-21 12:13:20',NULL,NULL,NULL,NULL,NULL,NULL),
(156,112,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-21 20:13:20','2026-06-21',NULL,'2026-06-21 12:13:20',NULL,NULL,NULL,NULL,NULL,NULL),
(157,7,11,'Vehicle Damage',NULL,NULL,NULL,NULL,NULL,'high','TEST',NULL,NULL,1944.00,0.00,1,1944.00,1944.00,0.00,NULL,'paid',0.00000000,0.00000000,'2026-06-24 16:17:04','2026-06-24','','2026-06-24 08:17:04',NULL,NULL,NULL,NULL,NULL,NULL),
(158,7,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Shortage]: Driver remitted ₱1,000.00 instead of ₱1,200.00',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-24 16:23:09','2026-06-24',NULL,'2026-06-24 08:23:09',NULL,NULL,NULL,NULL,NULL,NULL),
(159,7,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver remitted boundary after the 10:00 AM cutoff.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-24 16:23:09','2026-06-24',NULL,'2026-06-24 08:23:09',NULL,NULL,NULL,NULL,NULL,NULL),
(160,7,11,'Late Remittance',NULL,NULL,NULL,NULL,NULL,'medium','Auto-logged [Late Remittance]: Driver submitted boundary past the 10:00 AM cut-off.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-24 16:23:09','2026-06-24',NULL,'2026-06-24 08:23:09',NULL,NULL,NULL,NULL,NULL,NULL),
(161,7,11,'Short Boundary',NULL,NULL,NULL,NULL,NULL,'low','Auto-logged [Shortage]: Boundary payment was ₱200.00 short.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',NULL,NULL,'2026-06-24 16:23:09','2026-06-24',NULL,'2026-06-24 08:23:09',NULL,NULL,NULL,NULL,NULL,NULL),
(162,112,64,'missing_unit_overdue',NULL,NULL,NULL,NULL,NULL,'high','Auto-logged [Flagdown]: Unit AAA 4591 is overdue for >48 hours.',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-25 14:07:26','2026-06-22','','2026-06-25 14:07:26',NULL,NULL,NULL,NULL,NULL,NULL),
(163,7,11,'Administrative Ban',NULL,NULL,NULL,NULL,'Administrative Action','critical','TESSSSSS',NULL,NULL,0.00,0.00,0,0.00,0.00,0.00,NULL,'none',0.00000000,0.00000000,'2026-06-25 17:54:32','2026-06-25','','2026-06-25 17:54:32','2026-06-25 17:54:32',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `driver_behavior` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `driver_incentives`
--

DROP TABLE IF EXISTS `driver_incentives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `driver_incentives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `incentive_type` enum('performance','safety','attendance','customer_service','fuel_efficiency','boundary_target','other') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `incentive_date` date NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `performance_metric` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_driver` (`driver_id`),
  KEY `idx_unit` (`unit_id`),
  KEY `idx_month_year` (`month`,`year`),
  KEY `idx_date` (`incentive_date`),
  CONSTRAINT `driver_incentives_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `driver_incentives_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `driver_incentives`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `driver_incentives` WRITE;
/*!40000 ALTER TABLE `driver_incentives` DISABLE KEYS */;
/*!40000 ALTER TABLE `driver_incentives` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `nickname` varchar(191) DEFAULT NULL,
  `profile_photo` varchar(191) DEFAULT NULL,
  `license_number` varchar(191) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `license_photo` varchar(191) DEFAULT NULL,
  `nbi_clearance_photo` varchar(191) DEFAULT NULL,
  `pnp_clearance_photo` varchar(191) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `daily_boundary_target` decimal(10,2) DEFAULT 1100.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `driver_type` enum('regular','senior','trainee') DEFAULT 'regular',
  `driver_status` enum('available','assigned','on_leave','suspended','banned') DEFAULT 'available',
  `suspended_until` datetime DEFAULT NULL,
  `suspension_reason` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `license_number` (`license_number`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drivers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `drivers` WRITE;
/*!40000 ALTER TABLE `drivers` DISABLE KEYS */;
INSERT INTO `drivers` VALUES
(1,NULL,'Jesus','Duero',NULL,NULL,'TBD-32001EFF','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-02 10:52:58','regular','banned',NULL,NULL,NULL,NULL,NULL),
(2,NULL,'Randy','Genchez',NULL,NULL,'TBD-3F8AA113','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-04 08:34:01','regular','available',NULL,NULL,NULL,125,NULL),
(3,NULL,'Sanjali','Untal',NULL,NULL,'TBD-C9FCB570','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-02 22:23:40','regular','banned',NULL,NULL,NULL,NULL,NULL),
(4,NULL,'Norodin','Dimanda',NULL,NULL,'TBD-01E746AB','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(5,NULL,'Henry','Belen',NULL,NULL,'TBD-97C6F120','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-05 01:12:11','regular','banned',NULL,NULL,NULL,NULL,NULL),
(6,NULL,'Arwin','Azarcon',NULL,NULL,'D09-12-312312','2026-10-01',NULL,NULL,NULL,'09123213123','bahay','Maria','09744624123','2026-05-06',1100.00,'2026-04-10 03:49:10','2026-06-23 18:57:08','regular','available',NULL,NULL,NULL,125,NULL),
(7,NULL,'Arvy','Rodriguez',NULL,NULL,'TBD-C97E22A7','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(8,NULL,'Bensar','Kalaing',NULL,NULL,'TBD-51CE31E1','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(10,NULL,'Jamie','Ferrer',NULL,NULL,'TBD-1301FC96','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(11,163,'Joel','Sumando',NULL,NULL,'TBD-0B402E3D','2027-01-01','uploads/driver_docs/license_11_1782047363.jpg','uploads/driver_docs/nbi_11_1782047322.jpg','uploads/driver_docs/pnp_11_1782047325.jpg','09911275418','di na babalik','mama ko','09911275418',NULL,1200.00,'2026-04-10 03:49:10','2026-06-25 17:56:47','regular','available',NULL,NULL,NULL,125,NULL),
(12,NULL,'Virgilio','Ramos',NULL,NULL,'TBD-6F9CEC83','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(13,NULL,'Dindo','Defeo',NULL,NULL,'TBD-D9A510FD','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(14,NULL,'Rodel','Gudran',NULL,NULL,'TBD-D8123B07','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(15,NULL,'Rodel','Gundran',NULL,NULL,'TBD-73FB9CB8','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(16,NULL,'Angelo','Taboada',NULL,NULL,'TBD-A5A6914F','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(17,NULL,'Virgilio','Reponte',NULL,NULL,'TBD-8816FACA','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(18,NULL,'Elmer','Andrade',NULL,NULL,'TBD-7E5B68F8','2026-03-18',NULL,NULL,NULL,'09153520035','nagcarlam laguna\r\n464644','asdasdasda','09153520035','2026-04-30',1400.00,'2026-04-10 03:49:10','2026-05-04 08:42:10','regular','banned',NULL,NULL,NULL,125,'2026-05-04 08:42:10'),
(19,NULL,'Felimon','Evangilista',NULL,NULL,'TBD-0843B864','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(20,NULL,'Norlando','Fernandez',NULL,NULL,'TBD-AB326AFE','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(21,NULL,'Nelson','Castro',NULL,NULL,'TBD-1764CD13','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(22,NULL,'Willy','Bautista',NULL,NULL,'TBD-EC9C7E7D','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(23,NULL,'Ramil','Cadalzo',NULL,NULL,'TBD-6F4BCD5E','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(24,NULL,'Freddie','Lamigo',NULL,NULL,'TBD-8B25B9B4','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(25,NULL,'Erwin','Pajanilla',NULL,NULL,'TBD-4CDA3ECF','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(26,NULL,'Roel','Norombaba',NULL,NULL,'TBD-338EA643','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(27,NULL,'Roel','Peñol',NULL,NULL,'TBD-642148BE','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(28,NULL,'Domingo','Tresvalles',NULL,NULL,'TBD-C66E77B8','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(29,NULL,'Simeon','Miranda',NULL,NULL,'TBD-12607E9D','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(30,NULL,'Carlito','Sitoy',NULL,NULL,'TBD-13308793','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(31,NULL,'Francisco','Baja',NULL,NULL,'TBD-31EAC3C7','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-05 00:51:58','regular','banned',NULL,NULL,NULL,NULL,NULL),
(32,NULL,'Juanito','Cabales',NULL,NULL,'TBD-D1DCF7F4','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(34,NULL,'Nelson','Juluat',NULL,NULL,'TBD-C8A89BDF','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(35,NULL,'Aldrin','Laya',NULL,NULL,'TBD-4B0B572C','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(36,164,'Elmar','Pabalate',NULL,NULL,'TBD-29EEA458','2027-01-01',NULL,NULL,NULL,'09066740167',NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-06-24 20:10:35','regular','available',NULL,NULL,NULL,NULL,NULL),
(37,NULL,'Agapito','Ostonal',NULL,NULL,'TBD-6BC7B1E1','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-04 04:02:18','regular','available',NULL,NULL,NULL,125,NULL),
(38,NULL,'Melencio','Singalawa',NULL,NULL,'TBD-C2310849','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(39,NULL,'Efren','Trinidad',NULL,NULL,'TBD-B6E39CF4','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(40,NULL,'Rogelio','Sanchez',NULL,NULL,'TBD-8875EE6B','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(41,NULL,'Michael','Fontanilla',NULL,NULL,'TBD-B7C6B411','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(42,NULL,'Wilfredo','Domingo',NULL,NULL,'TBD-66F042EE','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(43,NULL,'Yasse','Tangginog',NULL,NULL,'TBD-AF996EF1','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(44,NULL,'Dayanodin','Tangginog',NULL,NULL,'TBD-63A6B25F','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(45,NULL,'Domingo','Uyangorin',NULL,NULL,'TBD-0BEBFA4A','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(46,NULL,'Ricardo','Cuevas',NULL,NULL,'TBD-460577C7','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(47,NULL,'Gerse','Matallano',NULL,NULL,'TBD-93459098','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(48,NULL,'Gerse','Matallino',NULL,NULL,'TBD-331ADD63','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(49,NULL,'Ibrahim','Kaiting',NULL,NULL,'TBD-24752FB3','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(50,NULL,'Felimon','Malunes',NULL,NULL,'TBD-374A8078','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(51,NULL,'Alkisar','Makapundag',NULL,NULL,'TBD-5A32F300','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(52,NULL,'Mark Lester','Gundran',NULL,NULL,'TBD-5BE5E02E','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(53,NULL,'Radzmil','Nur',NULL,NULL,'TBD-DEBBC346','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(55,NULL,'Paulo','Ubag',NULL,NULL,'TBD-913DCC00','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(56,NULL,'Lito','Ayag',NULL,NULL,'TBD-D7722629','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-02 13:59:53','regular','banned',NULL,NULL,NULL,NULL,NULL),
(57,NULL,'Mario','Opeña',NULL,NULL,'TBD-85DED9A3','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(58,NULL,'Wilfredo','Orias',NULL,NULL,'TBD-EF88B2BC','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(59,NULL,'R','Laurente',NULL,NULL,'TBD-97778A34','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(60,NULL,'Javier','Ramber',NULL,NULL,'TBD-3DCD2E21','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-04-10 03:49:10','regular','available',NULL,NULL,NULL,NULL,NULL),
(61,NULL,'Felix','Ausa',NULL,NULL,'TBD-10D37CD1','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:10','2026-05-04 16:44:22','regular','banned',NULL,NULL,NULL,NULL,NULL),
(62,NULL,'Joseph','Penaflor',NULL,NULL,'TBD-E435D4EF','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(63,NULL,'Victor','Manalo',NULL,NULL,'TBD-AE1A242A','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(64,NULL,'July','Sunico',NULL,NULL,'TBD-5DDC5FF9','2027-01-01','uploads/driver_docs/license_64_1781179785.jpg','uploads/driver_docs/nbi_64_1781179788.jpg','uploads/driver_docs/pnp_64_1781179790.jpg','09911275418','manuhay street','wala','09214564615',NULL,1100.00,'2026-04-10 03:49:11','2026-06-23 18:57:08','regular','available',NULL,NULL,NULL,162,NULL),
(65,NULL,'Roberto','Sunico',NULL,NULL,'TBD-552F0D2F','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1300.00,'2026-04-10 03:49:11','2026-06-07 13:03:29','regular','available',NULL,NULL,NULL,125,NULL),
(66,NULL,'Jimmy','Gundran',NULL,NULL,'TBD-EF5EB292','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(67,NULL,'Rommel','Gonzales',NULL,NULL,'TBD-806EC1B3','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(68,NULL,'Apolinario','Calingasan',NULL,NULL,'TBD-BB0FD0DD','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(69,NULL,'Apolinario','Calisangan',NULL,NULL,'TBD-0749A4DC','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(70,NULL,'Morlino','Boroy',NULL,NULL,'TBD-614DB287','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(71,NULL,'Henner','Bonsol',NULL,NULL,'TBD-22953AE4','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(72,NULL,'Leonildo','Calubag',NULL,NULL,'TBD-6721635B','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(73,NULL,'Marlito','Baguioro',NULL,NULL,'TBD-3ECA69B6','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-05-04 03:39:33','regular','banned',NULL,NULL,NULL,NULL,NULL),
(74,NULL,'Peter','Leyva',NULL,NULL,'TBD-DD721DD5','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(75,NULL,'Sismundo','Candelaria',NULL,NULL,'TBD-438D1D7B','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(76,NULL,'Jefrrey','Tandual',NULL,NULL,'TBD-DE6108ED','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(77,NULL,'Edwin','Satar',NULL,NULL,'TBD-2ABDB36A','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(78,NULL,'Ricky','Romera',NULL,NULL,'TBD-421F8DF0','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(79,NULL,'Jose','Rio',NULL,NULL,'TBD-6B7ECDFB','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(80,NULL,'Alejandro','Ramos',NULL,NULL,'TBD-5F6031ED','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(81,NULL,'Joey','Motol',NULL,NULL,'TBD-80310A21','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(82,NULL,'Hermilio','Granado',NULL,NULL,'TBD-258E02E3','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(83,NULL,'Ronipo','Quijado',NULL,NULL,'TBD-5812B9FE','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(84,NULL,'Daud','Utap',NULL,NULL,'TBD-848C8A42','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(85,NULL,'Joseph','Piandiong',NULL,NULL,'TBD-DDCE7112','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(86,NULL,'Oliver','Ariola',NULL,NULL,'D06-12-312312','2025-02-14',NULL,NULL,NULL,'09131231231','bahay','maria delacurz','09171231263','2026-05-06',1100.00,'2026-04-10 03:49:11','2026-06-02 19:03:22','regular','suspended',NULL,NULL,NULL,125,NULL),
(87,NULL,'Edward','Nieva',NULL,NULL,'TBD-EDF6CD3F','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(88,NULL,'Rolly','Cuballes',NULL,NULL,'TBD-88375B7B','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(89,NULL,'Angel','Salazar',NULL,NULL,'TBD-7F1D6F2B','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(90,NULL,'Domingo','Jorojoro',NULL,NULL,'TBD-8FED4CF2','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(91,NULL,'Monico','Funtanilla',NULL,NULL,'TBD-70B90598','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(92,NULL,'William','Monisit',NULL,NULL,'TBD-5A58CAD8','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(93,NULL,'Jayson','Borromeo',NULL,NULL,'TBD-0AD5AF1A','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(94,NULL,'Edwin','Joquino',NULL,NULL,'TBD-3404AC5D','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(95,NULL,'Fernando','Razo',NULL,NULL,'TBD-70D35714','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(96,NULL,'Renato','Cortez',NULL,NULL,'TBD-FD364D1A','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(97,NULL,'Noel','Tequillo',NULL,NULL,'TBD-03354041','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(98,NULL,'Nelson','Adobas',NULL,NULL,'TBD-15312D74','2026-01-16',NULL,NULL,NULL,'09153520035','nagcarlam laguna\r\n464644','1124434343','09153520035','2026-04-29',1400.00,'2026-04-10 03:49:11','2026-04-29 13:16:05','regular','available',NULL,NULL,NULL,125,'2026-04-29 13:16:05'),
(99,NULL,'Armando','Cruz',NULL,NULL,'TBD-D1599031','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(100,NULL,'Napoleon','Emberso',NULL,NULL,'TBD-B77035A6','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(101,NULL,'Alfredo','Hagad',NULL,NULL,'TBD-00AC1C90','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(102,NULL,'Francisco','Raagas',NULL,NULL,'TBD-F4A2CA4F','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(103,NULL,'Gary','Lorenzo',NULL,NULL,'TBD-C1D70343','2027-01-01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-10 03:49:11','2026-04-10 03:49:11','regular','available',NULL,NULL,NULL,NULL,NULL),
(104,NULL,'sunibertson','sunico',NULL,NULL,'2121131','2026-04-09',NULL,NULL,NULL,'09153520035','nagcarlam laguna\r\n464644','asdasdasda','09153520035','2026-04-13',1100.00,'2026-04-12 17:37:20','2026-04-12 17:47:17','regular','available',NULL,NULL,18,18,'2026-04-12 17:47:17'),
(105,NULL,'sunibertson','sunico',NULL,NULL,'TBD-7E5B68F8w','2026-04-30',NULL,NULL,NULL,'09153520035','nagcarlam laguna\r\n464644','vvvvvv','09153520035','2026-04-30',1100.00,'2026-04-30 07:38:17','2026-04-30 07:42:39','regular','available',NULL,NULL,125,18,NULL),
(106,NULL,'dian','Santiago Dian',NULL,NULL,'56484','2026-04-28',NULL,NULL,NULL,'09158112931','Brgy Labuin Sta cruz laguna','dian','09158112931','2026-04-30',1200.00,'2026-04-30 22:05:55','2026-05-06 18:54:05','regular','banned',NULL,NULL,125,125,NULL),
(107,NULL,'yanzkie','ramos',NULL,NULL,'546','2026-04-08',NULL,NULL,NULL,'09158112931','labuin','maria','09896811293','2026-04-30',1200.00,'2026-04-30 22:17:27','2026-05-04 16:02:05','regular','banned',NULL,NULL,125,125,NULL),
(108,NULL,'Ria Jane','Perocho',NULL,NULL,'TBD-1111','2026-04-30',NULL,NULL,NULL,'09814444055','0049 Liwag st','Ronie Jollibee','09814444055','2026-04-30',0.00,'2026-04-30 23:00:22','2026-04-30 23:01:15','regular','available',NULL,NULL,125,125,'2026-04-30 23:01:15'),
(109,NULL,'Ria','Perocho',NULL,NULL,'A01-12-3456789999999','2026-04-30',NULL,NULL,NULL,'09814444055','0049 Liwag st','Ronie Jollibee','09814444055','2026-05-01',0.00,'2026-05-02 20:41:50','2026-05-02 20:41:50','regular','available',NULL,NULL,125,125,NULL),
(110,NULL,'Mary Anne','Santos',NULL,NULL,'A01-12-36777899A9999','2026-05-01',NULL,NULL,NULL,'09814444055','0049 Liwag st____','Ronie Jollibee','09814444055','2026-05-02',0.00,'2026-05-02 21:23:55','2026-05-02 21:23:55','regular','available',NULL,NULL,125,125,NULL),
(111,NULL,'RI','RO',NULL,NULL,'A01-22-245677','2026-05-03',NULL,NULL,NULL,'09814444055','0049 Liwag st','RONIE JOLLIBEE','09814444055','2026-05-04',0.00,'2026-05-04 08:45:22','2026-05-04 08:45:22','regular','available',NULL,NULL,125,125,NULL),
(112,NULL,'Ria Jane','Perocho',NULL,NULL,'A03-45-666666','2026-04-02',NULL,NULL,NULL,'09814444055','0049 Liwag st','RONIE JOLLIBEE','09814444055','2026-05-04',1000.00,'2026-05-04 08:48:47','2026-06-23 19:16:12','regular','available',NULL,NULL,125,125,NULL),
(113,NULL,'Rebbel','Mortrl',NULL,NULL,'Q35-67-753453','2011-05-04',NULL,NULL,NULL,'09090909090','Csvdgdgdgdgdggdhhffh','Jsyyy','09912356466','2026-05-04',1100.00,'2026-05-04 17:33:31','2026-06-01 16:46:38','regular','available',NULL,NULL,125,125,NULL),
(123,NULL,'Almar','Monarba',NULL,NULL,'PENDING-152-1778170023','2027-05-08',NULL,NULL,NULL,'09911275452','wala','wala','0912467542674','2026-05-08',1100.00,'2026-05-08 00:07:03','2026-05-08 00:27:54','regular','available',NULL,NULL,NULL,NULL,'2026-05-08 00:27:54'),
(124,NULL,'Almar','Monarba',NULL,NULL,'ALMAR-124-LIC','2032-05-23',NULL,NULL,NULL,'09911275418','Di Mahahap Street','Sangoku','09912457815',NULL,1100.00,'2026-05-09 21:38:39','2026-06-10 10:43:48','regular','available',NULL,NULL,NULL,155,NULL);
/*!40000 ALTER TABLE `drivers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `employee_type` enum('mechanic','office_staff','driver','manager') NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary_rate` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `spare_part_id` bigint(20) unsigned DEFAULT NULL,
  `franchise_case_id` bigint(20) unsigned DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recorded_by` int(11) DEFAULT NULL,
  `expense_category` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `approved_by` (`approved_by`),
  KEY `idx_expenses_date` (`date`),
  KEY `idx_expenses_category` (`category`),
  KEY `expenses_spare_part_id_index` (`spare_part_id`),
  CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES
(1,'Spare Parts Purchase','PURCHASED: 17 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,14450.00,NULL,NULL,NULL,'2026-04-25',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-25 12:57:37','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(2,'Spare Parts Purchase','PURCHASED: 1 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,850.00,NULL,NULL,NULL,'2026-04-25',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-25 13:26:07','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(3,'Spare Parts Purchase','PURCHASED: 29 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,24650.00,NULL,NULL,NULL,'2026-04-25',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-25 13:37:18','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(4,'Spare Parts Purchase','PURCHASED: 11 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,9350.00,NULL,NULL,NULL,'2026-04-25',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-25 13:37:39','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(5,'Spare Parts Purchase','PURCHASED: 1 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,850.00,NULL,NULL,NULL,'2026-04-25',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-25 14:04:33','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(6,'Spare Parts Purchase','PURCHASED: 1 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,850.00,NULL,NULL,NULL,'2026-04-26',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-26 07:11:11','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(7,'Spare Parts Purchase','PURCHASED: 78 pcs of Air Filter (Toyota Vios/Hiace) from Unspecified Supplier',NULL,66300.00,NULL,NULL,NULL,'2026-04-26',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-26 10:19:03','2026-04-26 11:07:17',18,'Spare Parts Purchase',18,NULL,NULL),
(8,'Electricity (Meralco)','aa','meralco',1000.00,NULL,NULL,'Cash','2026-04-26','2131231331',NULL,NULL,NULL,NULL,NULL,'pending',NULL,'2026-04-26 11:06:15','2026-04-26 11:06:15',18,NULL,18,18,NULL),
(9,'Spare Parts Purchase','PURCHASED: Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',1700.00,2,850.00,'Cash','2026-04-26','123123',NULL,2,NULL,NULL,NULL,'pending',NULL,'2026-04-26 14:14:49','2026-04-26 14:14:49',18,NULL,18,18,NULL),
(10,'Spare Parts Purchase','PURCHASED: bb','A. BONIFACIO AUTO',2024.00,23,88.00,'Cash','2026-04-26','12312312312',NULL,23,NULL,NULL,NULL,'pending',NULL,'2026-04-26 14:16:06','2026-04-26 14:16:06',18,NULL,18,18,NULL),
(11,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-04-26',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-04-26 14:18:07','2026-04-26 14:18:07',18,NULL,18,18,NULL),
(12,'Spare Parts Purchase','PURCHASED: Brake Fluid (500ml)','AMONLATHE WORKS',350.00,1,350.00,'Cash','2026-04-26','1wwww1w1w',NULL,13,NULL,NULL,NULL,'pending',NULL,'2026-04-26 14:28:40','2026-04-26 14:28:40',18,NULL,18,18,NULL),
(13,'Spare Parts Purchase','REGISTERED & PURCHASED: jj','ABC AUTO PARTS',81.00,9,9.00,'Cash','2026-04-26',NULL,NULL,26,NULL,NULL,NULL,'pending',NULL,'2026-04-26 15:33:49','2026-04-26 15:33:49',18,NULL,18,18,NULL),
(14,'Internet & WiFi','uso bayad','balot vendor',2000.00,NULL,NULL,'Transfer','2026-04-27',NULL,NULL,NULL,NULL,NULL,NULL,'pending',NULL,'2026-04-26 16:55:33','2026-04-26 16:55:33',18,NULL,18,18,NULL),
(15,'Damage Recovery','Direct cash payment from Sismundo Candelaria for accident debt (Incident Date: 2026-04-22)',NULL,-100.00,NULL,NULL,'Cash','2026-04-30',NULL,1,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 06:59:40','2026-04-30 06:59:40',125,NULL,125,125,NULL),
(16,'Damage Recovery','Direct cash payment from Sismundo Candelaria for accident debt (Incident Date: 2026-04-22)',NULL,-100.00,NULL,NULL,'Cash','2026-04-30',NULL,1,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 07:55:15','2026-04-30 07:55:15',125,NULL,125,125,NULL),
(17,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-04-30',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-04-30 08:20:06','2026-04-30 08:20:06',125,NULL,125,125,NULL),
(18,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-04-30',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-04-30 08:20:13','2026-04-30 08:20:13',125,NULL,125,125,NULL),
(19,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-04-30',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-04-30 08:20:13','2026-04-30 08:20:13',125,NULL,125,125,NULL),
(20,'Spare Parts Purchase','Inventory STOCK: 11 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',7150.00,11,650.00,NULL,'2026-04-30',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-04-30 08:20:29','2026-04-30 08:20:29',125,NULL,125,125,NULL),
(21,'Spare Parts Purchase','PURCHASED: Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',5950.00,7,850.00,'Cash','2026-04-30',NULL,NULL,2,NULL,NULL,NULL,'pending',NULL,'2026-04-30 08:22:12','2026-04-30 08:22:12',125,NULL,125,125,NULL),
(22,'Spare Parts Purchase','PURCHASED: Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',5950.00,7,850.00,'Cash','2026-04-30',NULL,NULL,2,NULL,NULL,NULL,'pending',NULL,'2026-04-30 08:22:12','2026-04-30 08:22:12',125,NULL,125,125,NULL),
(23,'Electricity (Meralco)','meralco bills',NULL,1100.00,NULL,NULL,'Cash','2026-04-30','2131231331',NULL,NULL,NULL,NULL,NULL,'pending',NULL,'2026-04-30 08:23:01','2026-04-30 08:23:01',125,NULL,125,125,NULL),
(24,'Spare Parts Purchase','REGISTERED & PURCHASED: brake hose','A. BONIFACIO AUTO',5000.00,10,500.00,'Cash','2026-04-30','111',NULL,27,NULL,NULL,NULL,'pending',NULL,'2026-04-30 09:34:50','2026-04-30 09:34:50',125,NULL,125,125,NULL),
(25,'Damage Recovery','Direct cash payment from sunibertson sunico for accident debt (Incident Date: 2026-04-30)',NULL,-5000.00,NULL,NULL,'Cash','2026-04-30',NULL,160,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 10:01:42','2026-04-30 10:01:42',125,NULL,125,125,NULL),
(26,'Damage Recovery','Direct cash payment from Sismundo Candelaria for accident debt (Incident Date: 2026-04-22)',NULL,-1300.00,NULL,NULL,'Cash','2026-04-30',NULL,1,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 10:02:07','2026-04-30 10:02:07',125,NULL,125,125,NULL),
(27,'Damage Recovery','Direct cash payment from July Sunico for accident debt (Incident Date: 2026-04-22)',NULL,-300.00,NULL,NULL,'Cash','2026-04-30',NULL,160,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 10:02:37','2026-04-30 10:02:37',125,NULL,125,125,NULL),
(28,'Damage Recovery','Direct cash payment from July Sunico for accident debt (Incident Date: 2026-04-22)',NULL,-350.00,NULL,NULL,'Cash','2026-04-30',NULL,160,NULL,NULL,NULL,NULL,'approved',NULL,'2026-04-30 10:03:04','2026-04-30 10:03:04',125,NULL,125,125,NULL),
(29,'Water (Maynilad)','123','meralco',500000.00,NULL,NULL,'Cash','2026-04-30','12312312312',NULL,NULL,NULL,125,'2026-04-30 10:17:18','approved',NULL,'2026-04-30 10:17:18','2026-04-30 10:17:18',125,NULL,125,125,NULL),
(30,'Water (Maynilad)','bakal si sunico berto',NULL,9999998.98,NULL,NULL,'Cash','2026-04-30',NULL,NULL,NULL,NULL,125,'2026-04-30 22:12:06','approved',NULL,'2026-04-30 22:12:06','2026-04-30 22:12:06',125,NULL,125,125,NULL),
(31,'Internet & WiFi','bsdhfib','meralcoo',100.00,NULL,NULL,'Cash','2026-04-30','sdifhgs',NULL,NULL,NULL,125,'2026-04-30 22:13:05','approved',NULL,'2026-04-30 22:13:05','2026-04-30 22:13:05',125,NULL,125,125,NULL),
(32,'Internet & WiFi','lalaaa','meralcoo',50.00,NULL,NULL,'Cash','2026-04-22','67',NULL,NULL,NULL,125,'2026-04-30 22:25:16','approved',NULL,'2026-04-30 22:25:16','2026-04-30 22:25:16',125,NULL,125,125,NULL),
(33,'Water (Maynilad)','meralco','meralco',1000.00,NULL,NULL,'Cash','2026-05-01','123123',NULL,NULL,NULL,125,'2026-05-01 08:57:02','approved',NULL,'2026-05-01 08:57:02','2026-05-05 05:33:39',125,NULL,125,125,'2026-05-05 05:33:39'),
(35,'Electricity (Meralco)','wifi','FLECO_324',6000.00,NULL,NULL,'Cash','2026-05-02','drto-2344444444444444444444444',NULL,NULL,NULL,125,'2026-05-02 22:28:23','approved',NULL,'2026-05-02 22:28:23','2026-05-05 05:33:00',125,NULL,125,125,'2026-05-05 05:33:00'),
(36,'Water (Maynilad)','tubig','FLECO_324',800.00,NULL,NULL,'Check','2026-05-02','drto-2344444444444444444444444',NULL,NULL,NULL,125,'2026-05-02 22:29:26','approved',NULL,'2026-05-02 22:29:26','2026-05-05 05:34:03',125,NULL,125,125,'2026-05-05 05:34:03'),
(37,'Spare Parts Purchase','PURCHASED: Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',1700.00,9999,850.00,'Cash','2026-05-02',NULL,NULL,2,NULL,125,'2026-05-02 23:59:38','approved',NULL,'2026-05-02 23:59:38','2026-05-05 05:32:50',125,NULL,125,125,'2026-05-05 05:32:50'),
(38,'Franchise Renewal','FRANCHISE RENEWAL: Case #2012-0502 (Old Expiry: May 12, 2026 -> New: May 13, 2026)',NULL,1000.00,NULL,NULL,'Cash','2026-05-03',NULL,NULL,NULL,44,125,'2026-05-03 00:52:22','approved',NULL,'2026-05-03 00:52:22','2026-05-03 00:52:22',125,NULL,125,125,NULL),
(40,'Water (Maynilad)','VRDFDDD','GSFSDX',4.00,NULL,NULL,'Cash','2026-05-04','VDFSFSNVJFNJNKJDNKKDNVFNFNVJFN',NULL,NULL,NULL,125,'2026-05-04 02:38:52','approved',NULL,'2026-05-04 02:38:52','2026-05-04 02:38:52',125,NULL,125,125,NULL),
(42,'Damage Recovery','Direct cash payment from Gerse Matallano for accident debt (Incident Date: 2026-05-02)',NULL,-650.00,NULL,NULL,'Cash','2026-05-04',NULL,146,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 02:44:09','2026-05-04 02:44:09',125,NULL,125,125,NULL),
(43,'Damage Recovery','Direct cash payment from Elmer Andrade for accident debt (Incident Date: 2026-05-01)',NULL,-850.00,NULL,NULL,'Cash','2026-05-04',NULL,51,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 03:35:40','2026-05-05 05:35:27',125,NULL,125,125,NULL),
(44,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27)',NULL,-1.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 03:59:27','2026-05-04 03:59:27',125,NULL,125,125,NULL),
(45,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27)',NULL,99.00,NULL,NULL,'Cash','2026-05-04',NULL,NULL,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 03:59:45','2026-05-05 05:35:14',125,NULL,125,125,NULL),
(46,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-22)',NULL,-38.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 04:00:25','2026-05-04 04:00:25',125,NULL,125,125,NULL),
(47,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-22)',NULL,-38.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 04:03:00','2026-05-04 04:03:00',125,NULL,125,125,NULL),
(48,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-22)',NULL,-62.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 04:03:34','2026-05-04 04:03:34',125,NULL,125,125,NULL),
(49,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27)',NULL,-998.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 04:03:54','2026-05-05 05:32:36',125,NULL,125,125,'2026-05-05 05:32:36'),
(50,'Spare Parts Purchase','Inventory STOCK: 1 pcs of Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',850.00,1,850.00,NULL,'2026-05-04',NULL,NULL,2,NULL,NULL,NULL,'approved',NULL,'2026-05-04 08:04:12','2026-05-04 08:04:12',125,NULL,125,125,NULL),
(51,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-05-04',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-05-04 08:12:54','2026-05-04 08:12:54',125,NULL,125,125,NULL),
(52,'Spare Parts Purchase','Inventory STOCK: 1 pcs of Dggdgdgd','213',222.00,1,222.00,NULL,'2026-05-04',NULL,NULL,28,NULL,NULL,NULL,'approved',NULL,'2026-05-04 08:15:03','2026-05-04 11:14:48',125,NULL,125,125,NULL),
(53,'Spare Parts Purchase','Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L)','Unspecified Supplier',650.00,1,650.00,NULL,'2026-05-04',NULL,NULL,14,NULL,NULL,NULL,'approved',NULL,'2026-05-04 08:18:32','2026-05-05 05:34:25',125,NULL,125,125,'2026-05-05 05:34:25'),
(54,'Damage Recovery','Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27)',NULL,-500.00,NULL,NULL,'Cash','2026-05-04',NULL,112,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-04 17:44:38','2026-05-04 17:44:38',125,NULL,125,125,NULL),
(55,'Franchise Renewal','FRANCHISE RENEWAL: Case #NCR 2018-4-2015-02370 (Old Expiry: Oct 31, 2028 -> New: May 06, 2026)',NULL,67.00,NULL,NULL,'Cash','2026-05-05',NULL,NULL,NULL,43,129,'2026-05-05 05:37:20','approved',NULL,'2026-05-05 05:37:20','2026-05-05 05:37:20',129,NULL,129,129,NULL),
(56,'Spare Parts Purchase','Inventory STOCK: 5 pcs of Brake Pads','AUTOPHIL ZONE SALES',1500.00,5,1500.00,'Cash','2026-05-04',NULL,NULL,21,NULL,125,'2026-05-05 05:49:59','approved',NULL,'2026-05-05 05:49:59','2026-05-05 05:49:59',125,NULL,125,125,NULL),
(57,'Damage Recovery','Direct cash payment from Roberto Sunico to settle Boundary Shortage',NULL,-600.00,NULL,NULL,'Cash','2026-05-09',NULL,160,NULL,NULL,NULL,NULL,'approved',NULL,'2026-05-09 19:49:41','2026-05-09 19:49:41',125,NULL,125,125,NULL),
(58,'Spare Parts Purchase','Inventory STOCK: 4 pcs of Air Filter (Toyota Vios/Hiace)','A. BONIFACIO AUTO',3400.00,4,850.00,NULL,'2026-05-27',NULL,NULL,2,NULL,NULL,NULL,'approved',NULL,'2026-05-27 21:24:19','2026-05-27 21:24:19',125,NULL,125,125,NULL),
(59,'Damage Recovery','Direct cash payment from Joel Sumando for accident debt (Incident Date: 2026-06-24)',NULL,-1900.00,NULL,NULL,'Cash','2026-06-24',NULL,7,NULL,NULL,NULL,NULL,'approved',NULL,'2026-06-24 16:18:19','2026-06-24 16:18:19',125,NULL,125,125,NULL),
(60,'Damage Recovery','Direct cash payment from Joel Sumando for accident debt (Incident Date: 2026-06-24)',NULL,-44.00,NULL,NULL,'Cash','2026-06-24',NULL,7,NULL,NULL,NULL,NULL,'approved',NULL,'2026-06-24 16:19:30','2026-06-24 16:19:30',125,NULL,125,125,NULL);
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `franchise_case_units`
--

DROP TABLE IF EXISTS `franchise_case_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_case_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `franchise_case_id` int(11) NOT NULL,
  `make` varchar(191) DEFAULT NULL,
  `motor_no` varchar(191) DEFAULT NULL,
  `chasis_no` varchar(191) DEFAULT NULL,
  `plate_no` varchar(191) DEFAULT NULL,
  `year_model` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_case_units_franchise_case_id_foreign` (`franchise_case_id`),
  CONSTRAINT `franchise_case_units_franchise_case_id_foreign` FOREIGN KEY (`franchise_case_id`) REFERENCES `franchise_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_case_units`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `franchise_case_units` WRITE;
/*!40000 ALTER TABLE `franchise_case_units` DISABLE KEYS */;
INSERT INTO `franchise_case_units` VALUES
(96,1,'TOYOTA VIOS','1NRX142517','PA1B119F30H4027929','NCN 8583','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(97,1,'TOYOTA VIOS','1NRX428108','PA1B119F33K4083254','NEI 4883','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(101,3,'TOYOTA VIOS','1NRX665295','PA1B18F3XM4139156','CBM 1979','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(102,3,'TOYOTA VIOS','1NRX593251','PA1B18F33L4123685','DAT 2567','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(103,3,'TOYOTA VIOS','1NRX662804','PA1B18F32M4138437','NEP 2440','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(104,4,'TOYOTA VIOS','1NRX539051','PA1B18F35L4109741','DAZ 9769','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(105,4,'TOYOTA VIOS','1NRX554443','PA1B18F3XL4112067','DBA 5420','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(106,4,'TOYOTA VIOS','1NRX585027','PA1B18F33L4120575','NGA 7736','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(107,5,'TOYOTA VIOS','1NRX570523','PA1B18F35L4115295','EAE 1247','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(108,5,'TOYOTA VIOS','1NRX288337','PA1B19F31J060654','NCW 5011','2018','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(109,5,'TOYOTA VIOS','1NRX617160','PA1B18F3XL4124719','NGB 6033','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(110,6,'TOYOTA VIOS','1NRX479141','PA1B13F39K4095280','NEN 2955','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(111,6,'TOYOTA VIOS','1NRX478775','PA1B13F37K4095102','NEN 2957','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(112,6,'TOYOTA VIOS','1NRX592060','PA1B18F34L4123212','EAF 7245','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(113,7,'TOYOTA VIOS','1NRX519089','PA1B18F37K4105320','CAT 6073','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(114,7,'TOYOTA VIOS','1NRX544017','PA1B118F30L4110974','DBA 1887','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(115,7,'TOYOTA VIOS','1NRX560364','PA1B18F35L4113725','NAN 1349','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(116,7,'TOYOTA VIOS','1NRX563284','PA1B18F33L4114131','NFZ 8295','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(117,7,'TOYOTA VIOS','1NRX513727','PA1B13F32K4103414','NGA 5044','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(119,9,'TOYOTA VIOS','1NRX728802','PA1B18F33M4156266','EAE 4949','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(120,10,'TOYOTA VIOS','1NRX670488','PA1B18F33M4140536','NEP 9750','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(121,11,'TOYOTA VIOS','1NRX399793','PA1B13F30J4076793','NDA 8102','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(122,12,'TOYOTA VIOS','1NRX382535','PA1B13F37J4074295','NDA 5429','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(123,13,'TOYOTA VIOS','1NRX711083','PA1B18F35M4150503','NET6100','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(124,14,'TOYOTA VIOS','1NRX511105','PA1B13F30K4102617','EAD 7438','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(125,15,'TOYOTA VIOS','1NRX265877','PA1B19F33J4055018','NAM 1610','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(126,16,'TOYOTA VIOS','1NRX765584','PA1B18F37N4171824','CAX 5430','2022','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(127,17,'TOYOTA VIOS','1NRX400695','PA1B13F38J4076895','NDA 8106','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(128,18,'TOYOTA VIOS','1NRX399472','PA1B13F38J4076640','NEA 1292','2019','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(129,19,'TOYOTA VIOS','1NRX505510','PA1B13F34K4101664','NGF 1484','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(130,20,'TOYOTA VIOS','1NRX684775','PA1B18F354143793','EAE 1919','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(131,21,'TOYOTA VIOS','1NRX676394','PA1B18F39M4141920','VAA 9864','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(132,22,'TOYOTA VIOS','1NRX587826','PA1B18F37L4121826','NGO 2629','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(133,23,'TOYOTA VIOS','2NZ6564244','NCP92-964857','VFL 543','2013','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(134,24,'TOYOTA VIOS','1NRX728865','PA1B18F35M4156270','NEV 5065','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(135,25,'TOYOTA VIOS','1NRX586443','PA1B18F37L4121129','DAT 1367','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(136,26,'TOYOTA VIOS','1NRX711080','PA1B18F33M4150502','NEW 6279','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(137,27,'TOYOTA VIOS','1NRX530110','PA1B18F38K4108095','DBA 2302','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(138,28,'TOYOTA VIOS','1NRX587947','PA1B18F34L4121976','EAF 6347','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(139,29,'TOYOTA VIOS','1NRX758930','PA1B18F35N4169456','NFH 3664','2022','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(140,30,'TOYOTA VIOS','1NRX494346','PA1B13F39K4098339','NEU 5546','2020','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(142,32,'TOYOTA VIOS','1NRX622596','PA1B18F33L4125985','CAV 9716','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(143,33,'TOYOTA VIOS','1NRX735643','PA1B18F3XM4159021','EAE 5883','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(144,34,'TOYOTA VIOS','1NRX626439','PA1B18F30L4128830','NGP 1887','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(146,36,'TOYOTA VIOS','1NRX593170','PA1B18F36L4123549','NGB 2854','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(147,42,'TOYOTA VIOS','1NRX591797','PA1B18F34L4123081','CAV 6803','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(148,43,'TOYOTA VIOS','1NRX669745','PA1B18F39M4140346','DAU 9027','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(149,39,'TOYOTA VIOS','1NRX703030','PA1B18F3XM4149041','NEO 6716','2021','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(150,40,'TOYOTA VIOS','2NZ7307868','NCP151-2031009','AAK 9196','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(151,40,'TOYOTA VIOS','2NZ6978423','NCP151-2012488','AAA 4591','2014','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(152,40,'TOYOTA VIOS','2NZ7160776','NCP151-2022506','AAQ 1743','2014','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(153,40,'TOYOTA VIOS','2NZ7384223','NCP151-2036531','ALA 3699','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(154,40,'TOYOTA VIOS','2NZ7494105','NCP151-2043398','ABG 7479','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(155,40,'TOYOTA VIOS','2NZ7400896','NCP151-2037524','ABL 6901','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(156,40,'TOYOTA VIOS','2NZ7542383','NCP151-2046832','ABL 1667','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(157,40,'TOYOTA VIOS','2NZ7301579','NCP151-2030436','AEA 9630','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(158,40,'TOYOTA VIOS','2NZ7470861','NCP151-2042785','ABF 7471','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(159,40,'TOYOTA VIOS','2NZ7557953','NCP151-2048091','ABP 2705','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(160,40,'TOYOTA VIOS','2NZ7541411','NCP151-2046789','ABP 7643','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(161,40,'TOYOTA VIOS','2NZ7263141','NCP151-2028527','AOA 8917','2015','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(162,40,'TOYOTA VIOS','1NRX128495','PA1B19F32H4024496','DAD 7555','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(163,40,'TOYOTA VIOS','1NRX049858','PA1B19F37G4007336','DCQ 1551','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(164,40,'TOYOTA VIOS','1NRX136597','PA1B19F31H4026529','NBX 4348','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(165,40,'TOYOTA VIOS','2NZ7666502','NCP151-2055742','NBW 7071','2016','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(166,40,'TOYOTA VIOS','1NRX118001','PA1B19F35H4021382','NAE 7193','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(167,40,'TOYOTA VIOS','1NRX093367','PA1B19F36G4016559','NAD 1140','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(168,40,'TOYOTA VIOS','1NRX072072','PA1B19F3XG4012319','NAC 4989','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(169,40,'TOYOTA VIOS','1NRX074746','PA1B19F32G4012928','NDG 7105','2017','2026-04-13 02:18:20','2026-04-13 02:18:20'),
(180,45,'TOYOTA VIOS','1NRX507225','PA1B13F31K4102013','NEF 4940','2020','2026-04-13 02:31:53','2026-04-13 02:31:53'),
(182,48,'haha','1234A','23579w','XYZ 827753','2026','2026-05-02 21:46:14','2026-05-02 21:46:14'),
(183,49,'wqeew','2NZ6978423','NCP1512012488','4354354345','2014','2026-05-04 15:18:05','2026-05-04 15:18:05'),
(195,44,'HONDA','DES12W131231231','3121EDADQAEQWEQ','D1321','1231','2026-05-05 00:50:09','2026-05-05 00:50:09'),
(196,44,'qdqwdqdqwd','DQWDQWDQWDWQDDW','DQWDQDQWDQWDQWD','DQWDW','1231','2026-05-05 00:50:09','2026-05-05 00:50:09'),
(197,8,'TOYOTAVIOS','1NRX364595','PA1B13F35J','D7468','2019','2026-05-07 01:51:18','2026-05-07 01:51:18');
/*!40000 ALTER TABLE `franchise_case_units` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `franchise_cases`
--

DROP TABLE IF EXISTS `franchise_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_name` varchar(255) NOT NULL,
  `case_no` varchar(100) NOT NULL,
  `type_of_application` varchar(255) NOT NULL,
  `denomination` varchar(255) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `date_filed` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('pending','approved','denied','expired') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_no` (`case_no`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_cases`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `franchise_cases` WRITE;
/*!40000 ALTER TABLE `franchise_cases` DISABLE KEYS */;
INSERT INTO `franchise_cases` VALUES
(1,'EUROTAXI INC.','NCR 2014-01300','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2027-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(2,'EUROTAXI INC.','NCR 2014-01302','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2022-10-31','approved',NULL,'2026-04-13 01:40:12','2026-05-02 22:41:43','2026-05-02 22:41:43'),
(3,'EUROTAXI INC.','NCR 2014-01299','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2024-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(4,'EUROTAXI INC.','NCR 2014-01301','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(5,'EUROTAXI INC.','NCR 2014-01286','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2025-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(6,'EUROTAXI INC.','NCR 2014-01303','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2024-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(7,'EUROTAXI INC.','NCR 2014-01304','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2026-02-27','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(8,'EUROTAXI INC.','201401287','FranchiseRenewalTransfer','Taxi',NULL,'2024-01-01','2026-07-16','approved',NULL,'2026-04-13 01:40:12','2026-05-07 01:51:18',NULL),
(9,'EUROTAXI INC.','NCR 2014-01285','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2027-10-14','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(10,'EUROTAXI INC.','NCR 2014-01288','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2027-10-19','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(11,'EUROTAXI INC.','NCR 2014-01289','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2027-10-27','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(12,'EUROTAXI INC.','NCR 2014-01149','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2024-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(13,'EUROTAXI INC.','NCR 2014-01233','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2025-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(14,'EUROTAXI INC.','NCR 2014-01148','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(15,'EUROTAXI INC.','NCR 2014-01231','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(16,'EUROTAXI INC.','NCR 2014-01151','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(17,'EUROTAXI INC.','NCR 2014-01235','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(18,'EUROTAXI INC.','NCR 2014-01234','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2025-07-11','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(19,'EUROTAXI INC.','NCR 2014-01232','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2025-10-18','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(20,'EUROTAXI INC.','NCR 2014-01150','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2025-12-08','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(21,'EUROTAXI INC.','NCR 2014-01152','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2026-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(22,'EUROTAXI INC.','NCR 2014-01153','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2026-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(23,'EUROTAXI INC.','NCR 2014-01147','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2029-06-12','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(24,'CENTRAL','CENTRAL 96-9555','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(25,'CENTRAL','CENTRAL 95-866','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-01','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(26,'CENTRAL','CENTRAL 95-20643','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-02','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(27,'CENTRAL','CENTRAL 95-9798','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-03','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(28,'CENTRAL','CENTRAL 95-3745','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-04','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(29,'CENTRAL','CENTRAL 95-27627','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-05','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(30,'CENTRAL','CENTRAL 97-00846','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-11-06','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(31,'RQG TRANSPORT','NCR 2015-02362','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2022-10-31','approved',NULL,'2026-04-13 01:40:12','2026-05-04 08:54:21','2026-05-04 08:54:21'),
(32,'RQG TRANSPORT','NCR 2015-02366','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-08-02','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(33,'RQG TRANSPORT','NCR 2015-02368','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(34,'RQG TRANSPORT','NCR 2015-02853','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(36,'RQG TRANSPORT','NCR 2015-02367','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(39,'RQG TRANSPORT','NCR 2015-02363','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2028-10-31','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(40,'RQG TRANSPORT','NCR 2015-00083','Franchise Renewal/Transfer','Taxi',NULL,'2024-01-01','2027-09-02','approved',NULL,'2026-04-13 01:40:12','2026-04-13 01:40:12',NULL),
(42,'RQG TRANSPORT','NCR 2018-4-2015-02365','Extension of Validity','Taxi Airconditioned Service',NULL,'2023-10-31','2028-10-31','approved',NULL,'2026-04-13 02:18:20','2026-05-04 19:42:19',NULL),
(43,'RQG TRANSPORT','NCR 2018-4-2015-02370','Extension of Validity','Taxi Airconditioned Service',NULL,'2023-10-31','2026-05-06','pending',NULL,'2026-04-13 02:18:20','2026-05-05 05:37:20',NULL),
(44,'WANITO','20120502','Franchise Verification','Taxi Airconditioned Service',NULL,'2035-11-08','2035-11-08','pending',NULL,'2026-04-13 02:21:30','2026-05-05 00:50:09',NULL),
(45,'RQG TRANSPORT','NCR 2018-4-2015-02364','Extension of Validity','Taxi Airconditioned Service',NULL,'2018-10-31','2023-10-31','approved',NULL,'2026-04-13 02:31:53','2026-05-04 19:42:03',NULL),
(46,'sunico','siraa','ewan','ooo',NULL,'2026-04-30','2026-04-30','pending',NULL,'2026-05-02 20:33:23','2026-05-02 20:33:58','2026-05-02 20:33:58'),
(48,'Pepito_ANNNNN','sira_34','RIA_344444','ooo_5555',NULL,'2026-05-06','2026-05-07','pending',NULL,'2026-05-02 21:46:14','2026-05-02 21:46:14','2026-05-04 19:02:31'),
(49,'AHTDOG','ewqeqw','qewqqwqweqew','qweewq',NULL,'2026-05-04','2026-05-03','pending',NULL,'2026-05-04 15:18:05','2026-05-04 15:18:05','2026-05-04 18:57:54');
/*!40000 ALTER TABLE `franchise_cases` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `franchise_units`
--

DROP TABLE IF EXISTS `franchise_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `franchise_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `make` varchar(255) NOT NULL,
  `motor_no` varchar(255) NOT NULL,
  `chasis_no` varchar(255) NOT NULL,
  `plate_no` varchar(255) NOT NULL,
  `year_model` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`),
  CONSTRAINT `franchise_units_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `franchise_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `franchise_units`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `franchise_units` WRITE;
/*!40000 ALTER TABLE `franchise_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `franchise_units` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gps_devices`
--

DROP TABLE IF EXISTS `gps_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gps_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `firmware_version` varchar(50) DEFAULT NULL,
  `installation_date` date NOT NULL,
  `status` enum('active','inactive','maintenance','retired') DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_id` (`device_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_gps_devices_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gps_devices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gps_devices` WRITE;
/*!40000 ALTER TABLE `gps_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `gps_devices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gps_logs`
--

DROP TABLE IF EXISTS `gps_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gps_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gps_device_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `speed` decimal(5,2) DEFAULT 0.00 COMMENT 'Speed in km/h',
  `heading` int(11) DEFAULT 0 COMMENT 'Heading in degrees',
  `altitude` decimal(8,2) DEFAULT 0.00 COMMENT 'Altitude in meters',
  `accuracy` decimal(6,2) DEFAULT 0.00 COMMENT 'Accuracy in meters',
  `battery_level` int(11) DEFAULT 100 COMMENT 'Battery level in percentage',
  `signal_strength` int(11) DEFAULT 0 COMMENT 'Signal strength',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_gps_device_id` (`gps_device_id`),
  KEY `idx_timestamp` (`timestamp`),
  KEY `idx_device_timestamp` (`gps_device_id`,`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gps_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gps_logs` WRITE;
/*!40000 ALTER TABLE `gps_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `gps_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gps_settings`
--

DROP TABLE IF EXISTS `gps_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gps_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gps_device_id` int(11) NOT NULL,
  `update_interval` int(11) DEFAULT 30 COMMENT 'Update interval in seconds',
  `accuracy_threshold` int(11) DEFAULT 10 COMMENT 'Accuracy threshold in meters',
  `speed_threshold` int(11) DEFAULT 5 COMMENT 'Speed threshold in km/h',
  `geofencing_enabled` tinyint(1) DEFAULT 1,
  `geofence_radius` int(11) DEFAULT 100 COMMENT 'Geofence radius in meters',
  `low_battery_alert` tinyint(1) DEFAULT 1,
  `offline_alert` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_gps_device_id` (`gps_device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gps_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gps_settings` WRITE;
/*!40000 ALTER TABLE `gps_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `gps_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gps_test_logs`
--

DROP TABLE IF EXISTS `gps_test_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gps_test_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gps_device_id` int(11) NOT NULL,
  `test_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`test_result`)),
  `test_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gps_device_id` (`gps_device_id`),
  KEY `idx_test_date` (`test_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gps_test_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gps_test_logs` WRITE;
/*!40000 ALTER TABLE `gps_test_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `gps_test_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gps_tracking`
--

DROP TABLE IF EXISTS `gps_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gps_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `speed` decimal(5,2) DEFAULT NULL,
  `heading` decimal(5,2) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_id` varchar(191) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `ignition_status` tinyint(1) DEFAULT 0,
  `odo` decimal(12,2) DEFAULT NULL,
  `daily_start_mileage` decimal(12,2) DEFAULT NULL,
  `daily_start_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_unit_timestamp` (`unit_id`,`timestamp`),
  CONSTRAINT `gps_tracking_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gps_tracking`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gps_tracking` WRITE;
/*!40000 ALTER TABLE `gps_tracking` DISABLE KEYS */;
INSERT INTO `gps_tracking` VALUES
(1,112,14.66838600,121.06920900,0.00,135.00,'2001:fd8:e253:1700:2408:2ea5:e830:350','476ce36629f0aaff','2026-06-25 09:29:54',0,101695.39,101685.69,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(2,1,14.61887000,121.05561800,0.00,151.00,'2001:fd8:e253:1700:2408:2ea5:e830:350','476ce36629f0aaff','2026-06-25 09:39:16',1,21380.64,21380.64,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(3,115,14.66830100,121.07015100,0.00,45.00,NULL,NULL,'2026-06-10 03:27:03',0,43550.73,43550.73,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(4,187,14.62778800,121.08110200,0.00,320.00,NULL,NULL,'2026-05-12 16:08:38',0,79995.88,79995.88,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(5,116,14.66419900,121.04355600,0.00,341.00,NULL,NULL,'2026-06-25 09:23:04',1,76350.28,76319.99,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(6,118,14.66836600,121.06951100,0.00,254.00,NULL,NULL,'2026-05-06 23:15:17',0,41157.88,41157.88,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(7,7,14.64729400,121.07432900,17.00,176.00,'2001:fd8:e240:2100:75e2:f6b3:4af0:569','476ce36629f0aaff','2026-06-25 09:40:32',1,24267.40,24241.59,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:56:52'),
(8,120,14.69196600,121.08744900,0.00,1.00,NULL,NULL,'2026-06-25 09:27:20',0,82371.00,82328.78,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(9,122,14.65045600,121.01764400,0.00,186.00,'2001:fd8:e240:2100:b485:848c:f33:cff6','476ce36629f0aaff','2026-06-25 09:24:37',1,126399.16,126366.07,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(10,136,14.62472000,121.07091600,0.00,158.00,'131.226.106.33','476ce36629f0aaff','2026-06-25 08:56:22',1,108787.95,108780.37,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(11,138,14.66828400,121.07000000,0.00,172.00,NULL,NULL,'2026-06-25 07:01:03',0,112427.19,112420.50,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(12,139,14.65004200,121.05166200,16.00,18.00,'180.195.70.222','ec0e6a44af4e6cd8','2026-06-25 09:43:11',1,107127.54,107117.95,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(13,8,14.74885900,121.06545800,0.00,84.00,NULL,NULL,'2026-06-25 06:44:46',0,44650.30,44614.03,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(14,186,14.61993600,121.04697800,0.00,108.00,NULL,NULL,'2026-06-25 08:21:36',0,120319.72,120319.72,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(15,149,14.61730600,121.04446200,0.00,53.00,NULL,NULL,'2026-06-25 09:38:28',0,64169.82,64148.97,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(16,20,14.70119000,121.03491600,0.00,27.00,NULL,NULL,'2026-06-25 09:28:30',0,96654.40,96616.91,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(17,151,14.66840700,121.06981300,0.00,238.00,NULL,NULL,'2026-06-03 04:21:30',0,19286.15,19286.15,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(18,152,14.66863000,121.07002700,0.00,177.00,'2001:fd8:e253:1700:2408:2ea5:e830:350','476ce36629f0aaff','2026-06-25 09:17:26',0,53784.93,53768.09,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(19,156,14.62072200,121.00485300,31.00,219.00,NULL,NULL,'2026-06-25 09:43:55',1,101895.96,101851.53,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(20,157,14.66832900,121.06972400,0.00,119.00,NULL,NULL,'2026-05-22 09:48:49',0,44266.17,44266.17,'2026-06-25','2026-04-12 06:17:07','2026-06-25 17:44:02'),
(21,185,14.62087100,121.00548400,29.00,62.00,NULL,NULL,'2026-06-25 06:07:44',1,69871.99,69855.03,'2026-06-25','2026-04-12 06:17:07','2026-06-25 14:08:19'),
(22,160,14.66847000,121.06960000,0.00,0.00,NULL,NULL,'2026-06-06 01:25:11',0,0.00,0.00,'2026-06-25','2026-06-06 01:25:11','2026-06-25 14:08:19');
/*!40000 ALTER TABLE `gps_tracking` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `incident_classifications`
--

DROP TABLE IF EXISTS `incident_classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incident_classifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `default_severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `color` varchar(191) NOT NULL DEFAULT 'gray',
  `icon` varchar(191) NOT NULL DEFAULT 'alert-circle',
  `behavior_mode` enum('narrative','complaint','traffic','damage','security') DEFAULT 'narrative',
  `show_not_at_fault` tinyint(1) NOT NULL DEFAULT 0,
  `sub_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sub_options`)),
  `auto_ban_trigger` tinyint(1) NOT NULL DEFAULT 0,
  `ban_trigger_value` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `incident_classifications_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incident_classifications`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `incident_classifications` WRITE;
/*!40000 ALTER TABLE `incident_classifications` DISABLE KEYS */;
INSERT INTO `incident_classifications` VALUES
(2,'Late Remittance','medium','orange','clock','narrative',0,NULL,0,NULL,'2026-04-28 08:28:18','2026-04-30 08:13:11',NULL),
(3,'Short Boundary','medium','yellow','trending-down','narrative',0,NULL,0,NULL,'2026-04-28 08:28:18','2026-04-28 08:28:18',NULL),
(4,'Vehicle Damage','high','gray','alert-circle','damage',0,'[]',0,NULL,'2026-04-28 08:28:18','2026-04-29 04:51:55',NULL),
(5,'Accident','high','gray','alert-circle','damage',0,'[]',0,NULL,'2026-04-28 08:28:18','2026-05-01 19:46:47',NULL),
(6,'Traffic Violation','medium','gray','alert-circle','narrative',1,'[]',0,NULL,'2026-04-28 08:28:18','2026-05-02 11:51:36',NULL),
(7,'Absent / No Show','low','gray','user-x','narrative',0,NULL,0,NULL,'2026-04-28 08:28:18','2026-05-02 08:19:24',NULL),
(8,'Passenger Complaint','critical','gray','alert-circle','complaint',0,'[\"Contracting\"]',1,'Contracting','2026-04-28 08:28:18','2026-05-01 19:39:08',NULL),
(9,'Speeding','high','gray','alert-circle','traffic',0,'[\"speeding\"]',0,NULL,'2026-04-28 08:28:18','2026-05-02 10:02:10',NULL),
(10,'Hard Braking','low','orange','zap','narrative',0,NULL,0,NULL,'2026-04-28 08:28:18','2026-05-02 16:50:27','2026-05-02 16:50:27'),
(11,'Other','low','gray','alert-circle','narrative',0,NULL,0,NULL,'2026-04-28 08:28:18','2026-04-28 08:28:18',NULL),
(12,'The vehicle unit was taken/stolen','critical','gray','alert-circle','security',1,'[]',0,NULL,'2026-05-02 08:54:07','2026-05-02 11:55:54',NULL);
/*!40000 ALTER TABLE `incident_classifications` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `incident_involved_parties`
--

DROP TABLE IF EXISTS `incident_involved_parties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incident_involved_parties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_behavior_id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `vehicle_type` varchar(191) DEFAULT NULL,
  `plate_number` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incident_involved_parties_driver_behavior_id_foreign` (`driver_behavior_id`),
  CONSTRAINT `incident_involved_parties_driver_behavior_id_foreign` FOREIGN KEY (`driver_behavior_id`) REFERENCES `driver_behavior` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incident_involved_parties`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `incident_involved_parties` WRITE;
/*!40000 ALTER TABLE `incident_involved_parties` DISABLE KEYS */;
INSERT INTO `incident_involved_parties` VALUES
(1,7,'Juan Dela Cruz','Sedan','ABC 1234','2026-04-22 02:34:12','2026-04-22 02:34:12');
/*!40000 ALTER TABLE `incident_involved_parties` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `incident_parts_estimates`
--

DROP TABLE IF EXISTS `incident_parts_estimates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `incident_parts_estimates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_behavior_id` int(11) NOT NULL,
  `spare_part_id` bigint(20) unsigned DEFAULT NULL,
  `custom_part_name` varchar(191) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_charged_to_driver` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incident_parts_estimates_spare_part_id_foreign` (`spare_part_id`),
  KEY `incident_parts_estimates_driver_behavior_id_foreign` (`driver_behavior_id`),
  CONSTRAINT `incident_parts_estimates_driver_behavior_id_foreign` FOREIGN KEY (`driver_behavior_id`) REFERENCES `driver_behavior` (`id`) ON DELETE CASCADE,
  CONSTRAINT `incident_parts_estimates_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spare_parts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incident_parts_estimates`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `incident_parts_estimates` WRITE;
/*!40000 ALTER TABLE `incident_parts_estimates` DISABLE KEYS */;
INSERT INTO `incident_parts_estimates` VALUES
(1,7,21,NULL,1,1500.00,1500.00,1,'2026-04-22 02:34:12','2026-04-22 02:34:12'),
(2,8,14,NULL,1,650.00,650.00,1,'2026-04-22 03:09:28','2026-04-22 03:09:28'),
(3,10,NULL,NULL,1,88.00,88.00,1,'2026-04-22 11:43:44','2026-04-22 11:43:44'),
(4,10,2,NULL,1,850.00,850.00,1,'2026-04-22 11:43:44','2026-04-22 11:43:44'),
(5,21,14,NULL,99999,650.00,64999350.00,1,'2026-04-26 17:01:56','2026-04-26 17:01:56'),
(6,25,2,NULL,1,850.00,850.00,1,'2026-04-27 01:00:03','2026-04-27 01:00:03'),
(7,25,13,NULL,1,350.00,350.00,1,'2026-04-27 01:00:03','2026-04-27 01:00:03'),
(8,35,2,NULL,20,850.00,17000.00,1,'2026-04-30 10:01:09','2026-04-30 10:01:09'),
(9,36,2,NULL,2,850.00,1700.00,1,'2026-04-30 22:00:26','2026-04-30 22:00:26'),
(10,41,2,NULL,1,850.00,850.00,1,'2026-05-01 11:39:15','2026-05-01 11:39:15'),
(11,56,14,NULL,1,650.00,650.00,1,'2026-05-02 12:52:14','2026-05-02 12:52:14'),
(12,57,NULL,'FELISISIMO',1,21110.00,21110.00,1,'2026-05-02 13:07:54','2026-05-02 13:07:54'),
(13,100,2,NULL,1,850.00,850.00,1,'2026-05-05 09:15:17','2026-05-05 09:15:17'),
(14,157,21,NULL,1,1500.00,1500.00,1,'2026-06-24 16:17:04','2026-06-24 16:17:04'),
(15,157,NULL,'FFF',1,444.00,444.00,1,'2026-06-24 16:17:04','2026-06-24 16:17:04');
/*!40000 ALTER TABLE `incident_parts_estimates` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `login_audit`
--

DROP TABLE IF EXISTS `login_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_audit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(191) DEFAULT NULL,
  `user_email` varchar(191) DEFAULT NULL,
  `user_role` varchar(191) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `login_audit_user_id_action_index` (`user_id`,`action`),
  KEY `login_audit_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=893 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_audit`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `login_audit` WRITE;
/*!40000 ALTER TABLE `login_audit` DISABLE KEYS */;
INSERT INTO `login_audit` VALUES
(1,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 01:55:42'),
(2,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 01:57:56'),
(3,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:04:01'),
(4,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 02:04:58'),
(5,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:09:34'),
(6,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 02:10:14'),
(7,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:13:03'),
(8,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 02:13:41'),
(9,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:14:41'),
(10,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin@eurotaxisystem.com','2026-04-27 02:14:49'),
(11,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 02:15:56'),
(12,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 03:40:04'),
(13,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:22:05'),
(14,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:22:17'),
(15,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:23:15'),
(16,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:23:53'),
(17,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:24:03'),
(18,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:25:32'),
(19,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:31:32'),
(20,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 05:38:23'),
(21,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 05:38:31'),
(22,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 18:40:19'),
(23,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:26'),
(24,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:29'),
(25,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:44'),
(26,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:49'),
(27,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:51'),
(28,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:40:53'),
(29,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:41:01'),
(30,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:41:04'),
(31,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: sonysunico02@gmail.com','2026-04-27 18:41:07'),
(32,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 18:43:09'),
(33,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 18:43:39'),
(34,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 18:44:34'),
(35,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 19:26:03'),
(36,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 19:29:11'),
(37,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 19:29:46'),
(38,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 19:32:34'),
(39,126,'Secretary Test','appcarrental2025@gmail.com','secretary','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 20:55:13'),
(40,127,'Secretary Test','appcarrental2025@gmail.com','secretary','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-04-27 20:58:59'),
(41,127,'Secretary Test','appcarrental2025@gmail.com','secretary','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 21:00:05'),
(42,128,'Secretary Test','appcarrental2025@gmail.com','secretary','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-04-27 21:05:52'),
(43,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: appcarrental2025@gmail.com','2026-04-27 21:06:52'),
(44,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: appcarrental2025@gmail.com','2026-04-27 21:06:54'),
(45,128,'Secretary Test','appcarrental2025@gmail.com','secretary','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-27 21:07:30'),
(46,128,'Secretary Test','appcarrental2025@gmail.com','secretary','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 21:15:10'),
(47,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-28 08:35:26'),
(48,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-28 08:41:53'),
(49,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-29 03:43:29'),
(50,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Nelson Adobas\nUpdated details and status to Available','2026-04-29 06:31:05'),
(51,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Nelson Adobas\nUpdated details and status to Available','2026-04-29 06:31:26'),
(52,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591 moved to archive system.','2026-04-29 08:05:29'),
(53,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAK 4591 was restored from the system archive.','2026-04-29 08:06:28'),
(54,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: TX-0011 was permanently wiped from the database.','2026-04-29 08:06:38'),
(55,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: ABC123 was permanently wiped from the database.','2026-04-29 08:06:41'),
(56,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Driver','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Nelson Adobas moved to archive.','2026-04-29 13:16:05'),
(57,128,'Secretary Test','appcarrental2025@gmail.com','secretary','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account deactivated by Robert Garcia','2026-04-30 05:33:53'),
(58,128,'Secretary Test','appcarrental2025@gmail.com','secretary','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account re-activated by Robert Garcia','2026-04-30 05:33:55'),
(59,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account deactivated by Robert Garcia','2026-04-30 05:35:52'),
(60,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account re-activated by Robert Garcia','2026-04-30 05:35:52'),
(61,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account deactivated by Robert Garcia','2026-04-30 05:35:55'),
(62,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account re-activated by Robert Garcia','2026-04-30 05:35:58'),
(63,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account disabled by Robert Garcia Reason: aa','2026-04-30 05:47:44'),
(64,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 05:49:51'),
(65,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: appcarrental2025@gmail.com','2026-04-30 05:53:25'),
(66,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: appcarrental2025@gmail.com','2026-04-30 05:53:29'),
(67,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: appcarrental2025@gmail.com','2026-04-30 05:56:57'),
(68,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login blocked: account disabled. Reason: aa','2026-04-30 05:57:04'),
(69,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login blocked: account disabled. Reason: aa','2026-04-30 05:57:06'),
(70,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login blocked: account disabled. Reason: aa','2026-04-30 05:57:10'),
(71,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login blocked: account disabled. Reason: aa','2026-04-30 05:57:16'),
(72,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account enabled by Robert Garcia','2026-04-30 05:57:30'),
(73,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 05:57:57'),
(74,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account disabled by Robert Garcia Reason: aaaaaa','2026-04-30 05:58:10'),
(75,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account disabled by Robert Garcia Reason: aaaaaa','2026-04-30 05:58:12'),
(76,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','approved','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account enabled by Robert Garcia','2026-04-30 05:58:38'),
(77,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted Staff','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Romy M. Tomas was permanently wiped from the database.','2026-04-30 06:11:51'),
(78,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Elmer Andrade\nUpdated details and status to Available','2026-04-30 06:20:48'),
(79,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Elmer Andrade\nUpdated details and status to Available','2026-04-30 06:21:31'),
(80,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱100.00 cash payment from Sismundo Candelaria for accident debt.','2026-04-30 06:59:40'),
(81,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: sunibertson sunico\nLicense: TBD-7E5B68F8w\nStatus: Available','2026-04-30 07:38:17'),
(82,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Driver','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: sunibertson sunico moved to archive.','2026-04-30 07:39:00'),
(83,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 07:40:58'),
(84,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','Restored Driver','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: sunibertson sunico was restored from the system archive.','2026-04-30 07:41:26'),
(85,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: TX-00122\nCategory: TOYOTA VIOS (2026)\nStatus: Active','2026-04-30 07:42:39'),
(86,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: TX-00122 moved to archive system.','2026-04-30 07:43:34'),
(87,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱100.00 cash payment from Sismundo Candelaria for accident debt.','2026-04-30 07:55:15'),
(88,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nDriver: sunibertson sunico\nDate: 2026-04-30\nCollected: ₱1,100.00\nStatus: Paid','2026-04-30 08:07:23'),
(89,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nType: Corrective\nRecord archived and stock returned.','2026-04-30 08:16:57'),
(90,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Maintenance','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Automatic entry: Reported broken down during boundary turnover (Half Boundary).\nComputation: 79.18 hrs x ₱45.83/hr was restored from the system archive.','2026-04-30 08:17:28'),
(91,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Maintenance','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Automatic entry: Reported broken down during boundary turnover (Half Boundary).\nComputation: 79.18 hrs x ₱45.83/hr was restored from the system archive.','2026-04-30 08:17:29'),
(92,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Maintenance','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Automatic entry: Reported broken down during boundary turnover (Half Boundary).\nComputation: 79.18 hrs x ₱45.83/hr was restored from the system archive.','2026-04-30 08:17:30'),
(93,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Toyota Super Long Life Coolant (1L) restored from archive.','2026-04-30 08:18:36'),
(94,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Toyota Super Long Life Coolant (1L) restored from archive.','2026-04-30 08:18:37'),
(95,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +1 units (New total: 11)\nOffice Expense recorded: #17','2026-04-30 08:20:06'),
(96,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +1 units (New total: 12)\nOffice Expense recorded: #18','2026-04-30 08:20:13'),
(97,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +1 units (New total: 13)\nOffice Expense recorded: #19','2026-04-30 08:20:13'),
(98,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +11 units (New total: 24)\nOffice Expense recorded: #20','2026-04-30 08:20:29'),
(99,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Spare Parts Purchase\nDescription: PURCHASED: Air Filter (Toyota Vios/Hiace)\nAmount: ₱5,950.00','2026-04-30 08:22:12'),
(100,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Spare Parts Purchase\nDescription: PURCHASED: Air Filter (Toyota Vios/Hiace)\nAmount: ₱5,950.00','2026-04-30 08:22:12'),
(101,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Electricity (Meralco)\nDescription: meralco bills\nAmount: ₱1,100.00','2026-04-30 08:23:01'),
(102,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Spare Parts Purchase\nDescription: REGISTERED & PURCHASED: brake hose\nAmount: ₱5,000.00','2026-04-30 09:34:50'),
(103,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ADY 2599\nType: Preventive\nCost: ₱5,351.00\nParts used: Air Filter (Toyota Vios/Hiace) (x2), ATF / CVT Transmission Fluid (1L) (x1), kupal','2026-04-30 09:39:14'),
(104,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: DBA 1887\nType: Preventive\nCost: ₱16,500.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1), ATF / CVT Transmission Fluid (1L) (x1), labordaybukas','2026-04-30 09:40:31'),
(105,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: sunibertson sunico\nUnit: NEF 4940\nType: Vehicle Damage\nSeverity: High','2026-04-30 10:01:09'),
(106,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱5,000.00 cash payment from sunibertson sunico for accident debt.','2026-04-30 10:01:42'),
(107,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱1,300.00 cash payment from Sismundo Candelaria for accident debt.','2026-04-30 10:02:07'),
(108,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱300.00 cash payment from July Sunico for accident debt.','2026-04-30 10:02:37'),
(109,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱350.00 cash payment from July Sunico for accident debt.','2026-04-30 10:03:04'),
(110,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Water (Maynilad)\nDescription: 123\nAmount: ₱500,000.00','2026-04-30 10:17:18'),
(111,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Preventive\nCost: ₱650,850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1), 222','2026-04-30 10:18:13'),
(112,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','created','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-04-30 10:20:27'),
(113,130,'Rea Remitra','remitra.manager1@gmail.com','manager','created','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: manager','2026-04-30 10:21:13'),
(114,131,'Romy Thomas','Romy.dispatcher1@gmail.com','dispatcher','created','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: dispatcher','2026-04-30 10:21:45'),
(115,128,'Secretary Test','appcarrental2025@gmail.com','secretary','rejected','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-04-30 10:23:52'),
(116,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 10:56:15'),
(117,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 10:58:05'),
(118,131,'Romy Thomas','Romy.dispatcher1@gmail.com','dispatcher','password_changed','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-04-30 11:05:09'),
(119,131,'Romy Thomas','Romy.dispatcher1@gmail.com','dispatcher','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 11:05:09'),
(120,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 11:25:19'),
(121,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 11:53:53'),
(122,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 21:38:38'),
(123,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','logout','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 21:41:53'),
(124,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 21:42:41'),
(125,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','139.135.200.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Preventive\nCost: ₱1,950.00\nParts used: ATF / CVT Transmission Fluid (1L) (x3)','2026-04-30 21:46:36'),
(126,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 21:58:57'),
(127,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Arwin Azarcon\nUnit: AAK 9196\nType: Vehicle Damage\nSeverity: High','2026-04-30 22:00:26'),
(128,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: dian Santiago Dian\nLicense: 56484\nStatus: Available','2026-04-30 22:05:55'),
(129,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nCategory: Toyota Vios\nStatus: Active','2026-04-30 22:08:45'),
(130,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Water (Maynilad)\nDescription: bakal si sunico berto\nAmount: ₱9,999,998.98','2026-04-30 22:12:06'),
(131,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Internet & WiFi\nDescription: bsdhfib\nAmount: ₱100.00','2026-04-30 22:13:05'),
(132,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: yanzkie ramos\nLicense: 546\nStatus: Available','2026-04-30 22:17:27'),
(133,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nCategory: Toyota Vios\nStatus: Active','2026-04-30 22:18:02'),
(134,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Internet & WiFi\nDescription: lalaaa\nAmount: ₱50.00','2026-04-30 22:25:16'),
(135,132,'Yana Santiago','dianesantiago879@gmail.com','manager','created','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: manager','2026-04-30 22:28:28'),
(136,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:30:18'),
(137,132,'Yana Santiago','dianesantiago879@gmail.com','manager','password_changed','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-04-30 22:32:54'),
(138,132,'Yana Santiago','dianesantiago879@gmail.com','manager','login','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:32:54'),
(139,132,'Yana Santiago','dianesantiago879@gmail.com','manager','logout','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:34:39'),
(140,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 22:40:37'),
(141,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:43:14'),
(142,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591 moved to archive system.','2026-04-30 22:44:25'),
(143,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAK 4591 was restored from the system archive.','2026-04-30 22:44:32'),
(144,132,'Yana Santiago','dianesantiago879@gmail.com','manager','login','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 22:45:35'),
(145,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABP 7643\nDriver: Henry Belen\nDate: 2026-04-30\nCollected: ₱1,200.00\nStatus: Paid','2026-04-30 22:53:49'),
(146,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-04-30 22:59:40'),
(147,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Ria Jane Perocho\nLicense: TBD-1111\nStatus: Available','2026-04-30 23:00:22'),
(148,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:12'),
(149,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Driver','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Ria Jane Perocho moved to archive.','2026-04-30 23:01:15'),
(150,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:19'),
(151,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:35'),
(152,132,'Yana Santiago','dianesantiago879@gmail.com','manager','logout','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:38'),
(153,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:40'),
(154,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:43'),
(155,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:01:54'),
(156,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 23:02:09'),
(157,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nCategory: TOYOTA VIOS (2026)\nStatus: Active','2026-04-30 23:03:23'),
(158,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD1245\nCategory: HONDA VIOS (2026)\nStatus: Active','2026-04-30 23:10:34'),
(159,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Late Remittance\nDriver: Henry Belen\nRecord moved to archive.','2026-04-30 23:30:02'),
(160,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Vehicle Damage\nDriver: Arwin Azarcon\nRecord moved to archive.','2026-04-30 23:30:11'),
(161,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: other\nDriver: Henry Belen\nRecord moved to archive.','2026-04-30 23:30:41'),
(162,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Late Remittance\nDriver: sunibertson sunico\nRecord moved to archive.','2026-04-30 23:32:04'),
(163,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Staff Record','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Name: Ria Jane Calubayan Perocho\nRole: Mechanic','2026-04-30 23:45:29'),
(164,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Staff Record','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff: Ria Jane Calubayan Perocho moved to archive.','2026-04-30 23:47:24'),
(165,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: angelasanvictores08@gmail.com','2026-05-01 02:45:04'),
(166,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nDriver: yanzkie ramos\nDate: 2026-05-01\nCollected: ₱1,200.00\nStatus: Paid','2026-05-01 08:55:30'),
(167,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Water (Maynilad)\nDescription: meralco\nAmount: ₱1,000.00','2026-05-01 08:57:02'),
(168,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940\nDriver: July Sunico\nDate: 2026-05-01\nCollected: ₱650.00\nStatus: Paid','2026-05-01 09:23:02'),
(169,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW\nCategory: 3123FWEFWE EQWEQWEQWEF2234 (2026)\nStatus: Active','2026-05-01 10:07:57'),
(170,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW moved to archive system.','2026-05-01 10:08:38'),
(171,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: WWWWWWWW was restored from the system archive.','2026-05-01 10:09:33'),
(172,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW\nCategory: 3123FWEFWE EQWEQWEQWEF2234\nStatus: Coding','2026-05-01 10:16:27'),
(173,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW\nCategory: 3123FWEFWE EQWEQWEQWEF2234\nStatus: Coding','2026-05-01 10:20:01'),
(174,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW\nCategory: 3123FWEFWE EQWEQWEQWEF2234\nStatus: Coding','2026-05-01 10:22:41'),
(175,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Elmer Andrade\nUnit: CAX 5430\nType: Vehicle Damage\nSeverity: High','2026-05-01 11:39:15'),
(176,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:03:27'),
(177,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:23:36'),
(178,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:23:38'),
(179,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-01 12:42:29'),
(180,NULL,NULL,NULL,NULL,'failed_login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-01 12:51:48'),
(181,NULL,NULL,NULL,NULL,'failed_login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-01 12:51:52'),
(182,NULL,NULL,NULL,NULL,'failed_login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-01 12:52:15'),
(183,NULL,NULL,NULL,NULL,'failed_login','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-01 12:53:35'),
(184,NULL,NULL,NULL,NULL,'failed_login','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-01 12:53:51'),
(185,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','password_changed','49.144.209.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-05-01 12:58:30'),
(186,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','49.144.209.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:58:30'),
(187,133,'Ria Jane Perocho','perochoriajane4@gmail.com','dispatcher','created','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: dispatcher','2026-05-01 13:05:20'),
(188,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:05:34'),
(189,133,'Ria Jane Perocho','perochoriajane4@gmail.com','dispatcher','password_changed','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-05-01 13:08:23'),
(190,133,'Ria Jane Perocho','perochoriajane4@gmail.com','dispatcher','login','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:08:23'),
(191,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:08:56'),
(192,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:09:06'),
(193,133,'Ria Jane Perocho','perochoriajane4@gmail.com','dispatcher','Created Unit','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD1256\nCategory: HONDA CIVIC (2026)\nStatus: Active','2026-05-01 13:09:53'),
(194,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:10:00'),
(195,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:10:04'),
(196,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:10:22'),
(197,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:10:30'),
(198,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:10:54'),
(199,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NFH 3664\nDriver: Oliver Ariola\nDate: 2026-05-01\nCollected: ₱1,400.00\nStatus: Paid','2026-05-01 13:22:14'),
(200,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Boundary Rule','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Rule: qewewq\nRange: 2005-2005\nRegular Rate: ₱3,123.00','2026-05-01 13:43:34'),
(201,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Boundary Rule','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Rule: qewewq was archived.','2026-05-01 13:51:11'),
(202,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Air Filter (Toyota Vios/Hiace) moved to archive.','2026-05-01 15:49:20'),
(203,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Air Filter (Toyota Vios/Hiace) restored from archive.','2026-05-01 15:49:27'),
(204,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ADY 2599\nStatus changed to: Pending','2026-05-01 16:05:00'),
(205,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ADY 2599\nType: Preventive\nRefreshed details and parts.','2026-05-01 16:05:16'),
(206,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Preventive\nCost: ₱1,500.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1), ATF / CVT Transmission Fluid (1L) (x1), eqwwwwwwwwwwwwwwwwwwwwwwwwwwww','2026-05-01 17:05:50'),
(207,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-01 17:24:57'),
(208,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CBM 1979\nDriver: Felimon Evangilista\nDate: 2026-05-01\nCollected: ₱700.00\nStatus: Paid','2026-05-01 17:50:00'),
(209,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: VFL 543\nDriver: sunibertson sunico\nDate: 2026-05-01\nCollected: ₱349.99\nStatus: Shortage','2026-05-01 17:52:25'),
(210,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ASA 6135\nDriver: sunibertson sunico\nDate: 2026-05-01\nCollected: ₱2,150.01\nStatus: Excess','2026-05-01 17:53:08'),
(211,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 18:33:30'),
(212,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 18:34:55'),
(213,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-01 18:37:06'),
(214,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 18:38:37'),
(215,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','49.147.74.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 20:56:51'),
(216,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 06:43:13'),
(217,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: dian Santiago Dian\nUnit: AAK 9196\nType: Passenger Complaint\nSeverity: Critical','2026-05-02 07:03:28'),
(218,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: dian Santiago Dian moved to archive.','2026-05-02 07:18:16'),
(219,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: dian Santiago Dian was restored from the system archive.','2026-05-02 07:18:37'),
(220,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: roberga','2026-05-02 09:44:16'),
(221,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-02 09:45:39'),
(222,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Jesus Duero\nUnit: AAK 4591\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-02 10:52:58'),
(223,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: July Sunico\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-02 10:57:23'),
(224,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591 recovered from status: Missing','2026-05-02 11:05:26'),
(225,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Arwin Azarcon\nUnit: ACH 5774\nType: Vehicle Damage\nSeverity: High','2026-05-02 12:26:11'),
(226,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Norlando Fernandez\nUnit: AAQ 1743\nType: Vehicle Damage\nSeverity: High','2026-05-02 12:50:41'),
(227,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Gerse Matallano\nUnit: NAC 4989\nType: Vehicle Damage\nSeverity: High','2026-05-02 12:52:14'),
(228,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Felimon Evangilista\nUnit: CBM 1979\nType: Vehicle Damage\nSeverity: High','2026-05-02 13:07:54'),
(229,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Felix Ausa\nUnit: NDI 2585\nType: Traffic Violation\nSeverity: Medium','2026-05-02 13:12:26'),
(230,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Domingo Uyangorin\nUnit: EAF 6347\nType: Traffic Violation\nSeverity: Medium','2026-05-02 13:28:56'),
(231,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Felimon Evangilista\nUnit: CBM 1979\nType: Traffic Violation\nSeverity: Medium','2026-05-02 13:43:54'),
(232,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Willy Bautista\nUnit: AAQ 1743\nType: Traffic Violation\nSeverity: Medium','2026-05-02 13:48:12'),
(233,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Nelson Castro\nUnit: NDA 8102\nType: Late Remittance\nSeverity: Medium','2026-05-02 13:50:02'),
(234,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Virgilio Reponte\nUnit: CAV 9716\nType: Absent / No Show\nSeverity: Low','2026-05-02 13:55:06'),
(235,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Lito Ayag\nUnit: NAD 8102\nType: Passenger Complaint\nSeverity: Critical','2026-05-02 13:59:53'),
(236,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Jose Camillotes\nUnit: ASA 6135\nType: Hard Braking\nSeverity: Low','2026-05-02 14:07:13'),
(237,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Hermilio Granado\nUnit: AAQ 1743\nType: Traffic Violation\nSeverity: Medium','2026-05-02 14:11:41'),
(238,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Nelson Juluat\nUnit: NAD 1140\nType: Late Remittance\nSeverity: Medium','2026-05-02 14:15:16'),
(239,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-02 14:30:50'),
(240,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Gerse Matallano\nUnit: NAC 4989\nType: Speeding\nSeverity: High','2026-05-02 15:00:51'),
(241,134,'Angela San Victores','angelasanvictores2005@gmail.com','secretary','created','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-05-02 16:09:31'),
(242,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-02 17:00:36'),
(243,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: A. BONIFACIO AUTO moved to archive.','2026-05-02 17:41:46'),
(244,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 19:03:58'),
(245,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 19:04:03'),
(246,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: A. BONIFACIO AUTO was restored from the system archive.','2026-05-02 19:14:54'),
(247,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 19:40:33'),
(248,135,'Ria Jane Perocho','perochoriajane065@gmail.com','secretary','created','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-05-02 19:42:58'),
(249,134,'Angela San Victores','angelasanvictores2005@gmail.com','secretary','rejected','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-05-02 19:44:39'),
(250,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591 moved to archive system.','2026-05-02 20:00:18'),
(251,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-02 20:08:10'),
(252,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-02 20:23:21'),
(253,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nDriver: Marlito Baguioro\nDate: 2026-05-02\nCollected: ₱1,000.00\nStatus: Paid','2026-05-02 20:24:42'),
(254,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nDriver: dian Santiago Dian\nDate: 2026-05-02\nCollected: ₱1,100.00\nStatus: Paid','2026-05-02 20:28:25'),
(255,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NCW 5011\nDriver: Ruben Patajo\nDate: 2026-05-02\nCollected: ₱1,200.00\nStatus: Paid','2026-05-02 20:29:54'),
(256,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-02 20:31:33'),
(257,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Maintenance Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Emergency\nRefreshed details and parts.','2026-05-02 20:32:00'),
(258,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Emergency\nRecord archived and stock returned.','2026-05-02 20:32:09'),
(259,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Franchise Case','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: siraa\nApplicant: sunico\nType: ewan','2026-05-02 20:33:23'),
(260,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: siraa moved to archive.','2026-05-02 20:33:58'),
(261,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Ria Perocho\nLicense: A01-12-3456789999999\nStatus: Available','2026-05-02 20:41:50'),
(262,136,'Ria Jane Janeeeeeeeeeeeeeeeeee','criztelperocho@gmail.com','dispatcher','created','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: dispatcher','2026-05-02 21:02:08'),
(263,135,'Ria Jane Perocho','perochoriajane065@gmail.com','secretary','rejected','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-05-02 21:10:21'),
(264,136,'Ria Jane Janeeeeeeeeeeeeeeeeee','criztelperocho@gmail.com','dispatcher','rejected','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-05-02 21:10:57'),
(265,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABC12344\nCategory: TOYOTA CIVIC (2026)\nStatus: Active','2026-05-02 21:17:22'),
(266,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABC12344 moved to archive system.','2026-05-02 21:20:14'),
(267,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Mary Anne Santos\nLicense: A01-12-36777899A9999\nStatus: Available','2026-05-02 21:23:55'),
(268,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Franchise Case','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: sira_34\nApplicant: Pepito_ANNNNN\nType: RIA_344444','2026-05-02 21:46:14'),
(269,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 7471\nDriver: Ria Perocho\nDate: 2026-05-02\nCollected: ₱1,100.00\nStatus: Paid','2026-05-02 22:09:36'),
(270,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABG 7479\nType: Preventive\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-02 22:20:53'),
(271,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Sanjali Untal\nUnit: ABG 7479\nType: Passenger Complaint\nSeverity: Critical','2026-05-02 22:23:40'),
(272,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Passenger Complaint\nDriver: Sanjali Untal\nRecord moved to archive.','2026-05-02 22:24:55'),
(273,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Electricity (Meralco)\nDescription: Wifi _45\nAmount: ₱49,999.98','2026-05-02 22:26:07'),
(274,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Electricity (Meralco)\nDescription: wifi\nAmount: ₱6,000.00','2026-05-02 22:28:23'),
(275,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Water (Maynilad)\nDescription: tubig\nAmount: ₱800.00','2026-05-02 22:29:26'),
(276,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Processed Salary','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Employee: Callito A.  Belmar\nTotal: ₱27,186.00\nPeriod: 5/2026\nSource: Staff','2026-05-02 22:34:21'),
(277,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Salary Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Record #4 was removed from the system.','2026-05-02 22:34:36'),
(278,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Staff Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Name: Ria Jane Calubayan Perocho56_\nRole: Guard','2026-05-02 22:37:34'),
(279,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Staff Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff: Ria Jane Calubayan Perocho56_ moved to archive.','2026-05-02 22:37:44'),
(280,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Expense: tubig moved to archive.','2026-05-02 22:40:20'),
(281,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: NCR 2014-01302 moved to archive.','2026-05-02 22:41:43'),
(282,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nType: Preventive\nCost: ₱0.00\nParts used: None','2026-05-02 22:43:51'),
(283,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Preventive\nCost: ₱0.00\nParts used: None','2026-05-02 22:44:03'),
(284,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Salary Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Record #3 was removed from the system.','2026-05-02 22:44:27'),
(285,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 23:29:52'),
(286,NULL,NULL,NULL,NULL,'failed_login','49.147.73.226','Mozilla/5.0 (Linux; Android 14; Infinix X6833B Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/559.0.0.49.75;]','Failed login for: jhh','2026-05-02 23:45:31'),
(287,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Spare Parts Purchase\nDescription: PURCHASED: Air Filter (Toyota Vios/Hiace)\nAmount: ₱8,499,150.00','2026-05-02 23:59:38'),
(288,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Franchise Renewal\nDescription: FRANCHISE RENEWAL: Case #2012-0502 (Old Expiry: May 12, 2026 -> New: May 13, 2026)\nAmount: ₱1,000.00','2026-05-03 00:52:22'),
(289,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin','2026-05-03 01:05:37'),
(290,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: Admin_shiellamarie','2026-05-03 01:07:05'),
(291,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: noreply@eurotaxisystem.site','2026-05-03 01:07:17'),
(292,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: Admin_shiellamarie','2026-05-03 01:07:46'),
(293,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 01:07:55'),
(294,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 01:29:19'),
(295,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-03 01:30:55'),
(296,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-03 01:31:03'),
(297,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-03 01:31:20'),
(298,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-03 01:31:25'),
(299,NULL,NULL,NULL,NULL,'failed_login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-03 01:38:28'),
(300,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 01:40:46'),
(301,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 01:46:05'),
(302,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 02:54:32'),
(303,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 02:58:07'),
(304,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 02:58:10'),
(305,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 03:00:07'),
(306,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 03:21:21'),
(307,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 03:27:38'),
(308,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 03:27:54'),
(309,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36','Login via MFA device verification.','2026-05-03 03:49:59'),
(310,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 11:39:30'),
(311,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 11:42:56'),
(312,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 11:46:46'),
(313,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','created','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-05-03 11:48:23'),
(314,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 11:48:34'),
(315,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','password_changed','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-05-03 11:50:31'),
(316,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 11:50:31'),
(317,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:00:27'),
(318,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:00:41'),
(319,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:02:44'),
(320,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:03:06'),
(321,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:20:17'),
(322,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:20:32'),
(323,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 12:20:35'),
(324,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:20:38'),
(325,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 12:20:42'),
(326,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:21:09'),
(327,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:27:48'),
(328,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 12:45:37'),
(329,136,'Ria Jane Janeeeeeeeeeeeeeeeeee','criztelperocho@gmail.com','dispatcher','approved','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account restored by Robert Garcia','2026-05-03 13:17:10'),
(330,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','rejected','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Account archived by Robert Garcia','2026-05-03 13:25:55'),
(331,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','approved','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Account restored by Robert Garcia','2026-05-03 13:27:21'),
(332,138,'Gelatokisdinagbabayad Tulog','sunicoq@gmail.com','dispatcher','created','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Staff account created by Robert Garcia with role: dispatcher','2026-05-03 13:31:14'),
(333,138,'Gelatokisdinagbabayad Tulog','sunicoq@gmail.com','dispatcher','rejected','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Account archived by Robert Garcia','2026-05-03 13:31:32'),
(334,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','rejected','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Account archived by Robert Garcia','2026-05-03 13:51:53'),
(335,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','approved','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Account restored by Robert Garcia','2026-05-03 14:47:07'),
(336,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 20:10:21'),
(337,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 20:13:46'),
(338,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 20:13:50'),
(339,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 20:14:00'),
(340,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 20:14:03'),
(341,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nDriver: yanzkie ramos\nDate: 2026-05-03\nCollected: ₱1,000.00\nStatus: Paid','2026-05-03 21:06:19'),
(342,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin@eurotaxisystem.com','2026-05-03 21:45:38'),
(343,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin@gmail.com','2026-05-03 21:46:49'),
(344,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin','2026-05-03 21:47:49'),
(345,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: admin@eurotaxisystem.site','2026-05-03 21:48:34'),
(346,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Staff Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Name: A B C D E F G\nRole: Mechanic','2026-05-03 21:56:49'),
(347,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 21:57:06'),
(348,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 21:57:21'),
(349,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-03 21:57:46'),
(350,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 21:58:01'),
(351,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-03 22:00:24'),
(352,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940 recovered from status: Missing','2026-05-03 22:20:33'),
(353,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: BDEHF376\nCategory: RNGRNGJTNGTGJRN BGTBRGJRBGKJRGJ (2026)\nStatus: Active','2026-05-03 22:24:49'),
(354,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAK 4591 was restored from the system archive.','2026-05-03 23:00:14'),
(355,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743 moved to archive system.','2026-05-03 23:00:37'),
(356,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAQ 1743 was restored from the system archive.','2026-05-03 23:00:48'),
(357,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Staff Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff: A B C D E F G moved to archive.','2026-05-03 23:02:05'),
(358,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Staff','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Ria Jane Calubayan Perocho was restored from the system archive.','2026-05-03 23:02:48'),
(359,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Pricing_rule','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: qewewq was restored from the system archive.','2026-05-03 23:04:07'),
(360,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Staff','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Ria Jane Calubayan Perocho56_ was restored from the system archive.','2026-05-03 23:04:14'),
(361,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: tubig was restored from the system archive.','2026-05-03 23:04:19'),
(362,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Randy Genchez was restored from the system archive.','2026-05-03 23:04:23'),
(363,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-03 23:59:47'),
(364,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-03 23:59:50'),
(365,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-04 00:00:35'),
(366,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-04 00:01:00'),
(367,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-04 00:12:16'),
(368,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered via Mobile App.','2026-05-04 00:12:36'),
(369,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered from status: Active','2026-05-04 00:14:33'),
(370,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered from status: Active','2026-05-04 00:15:29'),
(371,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered from status: Active','2026-05-04 00:15:35'),
(372,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9662 recovered from status: Active','2026-05-04 00:19:57'),
(373,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Roberto Sunico\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 00:22:04'),
(374,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940 recovered from status: Missing','2026-05-04 00:29:34'),
(375,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Elmer Andrade\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 00:39:48'),
(376,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940 recovered from status: Missing','2026-05-04 00:40:09'),
(377,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Arwin Azarcon\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 00:41:06'),
(378,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940 recovered via Mobile App.','2026-05-04 01:04:27'),
(379,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Pantry & Cleaning\nDescription: General Cleaning\nAmount: ₱50,000.00','2026-05-04 01:21:19'),
(380,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 02:17:18'),
(381,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 02:18:33'),
(382,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nStatus changed to: Pending','2026-05-04 02:26:46'),
(383,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Water (Maynilad)\nDescription: VRDFDDD\nAmount: ₱4.00','2026-05-04 02:38:52'),
(384,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: yanzkie ramos\nUnit: AAK 9196\nType: Late Remittance\nSeverity: High','2026-05-04 02:40:17'),
(385,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Processed Salary','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Employee: Abran A. Oracion\nTotal: ₱356,761.00\nPeriod: 8/2027\nSource: Staff','2026-05-04 02:41:33'),
(386,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱21,110.00 cash payment from Felimon Evangilista for accident debt.','2026-05-04 02:44:00'),
(387,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱650.00 cash payment from Gerse Matallano for accident debt.','2026-05-04 02:44:09'),
(388,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591\nDriver: Randy Genchez\nDate: 2026-05-04\nCollected: ₱550.00\nStatus: Paid','2026-05-04 02:57:33'),
(389,NULL,NULL,NULL,NULL,'failed_login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-04 03:12:19'),
(390,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 03:21:08'),
(391,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.64','Mozilla/5.0 (Linux; Android 11; 2201117TG Build/RKQ1.211001.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36','Processed ₱850.00 cash payment from Elmer Andrade for accident debt via Mobile App.','2026-05-04 03:35:40'),
(392,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 03:38:44'),
(393,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Marlito Baguioro\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 03:39:33'),
(394,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:30'),
(395,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:32'),
(396,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:33'),
(397,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:37'),
(398,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:39'),
(399,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:40'),
(400,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:40'),
(401,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:41'),
(402,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:42'),
(403,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:42'),
(404,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:46'),
(405,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:48'),
(406,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: remitra.manager1@gmail.com','2026-05-04 03:45:50'),
(407,130,'Rea Remitra','remitra.manager1@gmail.com','manager','password_changed','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff forced password change completed.','2026-05-04 03:49:00'),
(408,130,'Rea Remitra','remitra.manager1@gmail.com','manager','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 03:49:00'),
(409,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 03:49:09'),
(410,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Reset Maintenance Health','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Manual health reset for Unit: CAV 2607. Counter restarted at 116,822 KM.','2026-05-04 03:53:10'),
(411,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Reset Maintenance Health','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Manual health reset for Unit: EAB 8186. Counter restarted at 97,885 KM.','2026-05-04 03:53:51'),
(412,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱1.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 03:59:27'),
(413,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱999.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 03:59:45'),
(414,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱38.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 04:00:25'),
(415,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Agapito Ostonal was restored from the system archive.','2026-05-04 04:02:18'),
(416,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱38.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 04:03:00'),
(417,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱62.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 04:03:34'),
(418,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱998.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 04:03:54'),
(419,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 04:22:25'),
(420,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 07:05:56'),
(421,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 07:14:02'),
(422,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 07:14:07'),
(423,130,'Rea Remitra','remitra.manager1@gmail.com','manager','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 07:46:38'),
(424,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nStatus changed to: Pending','2026-05-04 07:51:08'),
(425,NULL,NULL,NULL,NULL,'failed_login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: perochoriajane4@gmail.com','2026-05-04 08:00:21'),
(426,NULL,NULL,NULL,NULL,'failed_login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-04 08:02:36'),
(427,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 08:03:50'),
(428,NULL,NULL,NULL,NULL,'failed_login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-04 08:03:54'),
(429,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Air Filter (Toyota Vios/Hiace)\nPrice: ₱850.00\nStock Added: +1 units (New total: 10156)\nOffice Expense recorded: #50','2026-05-04 08:04:12'),
(430,NULL,NULL,NULL,NULL,'failed_login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-04 08:04:18'),
(431,NULL,NULL,NULL,NULL,'failed_login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-04 08:04:59'),
(432,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 08:06:03'),
(433,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: we\nContact: ','2026-05-04 08:06:46'),
(434,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: qweqwewq\nContact: ','2026-05-04 08:06:57'),
(435,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: qwe\nContact: qweqwe','2026-05-04 08:07:42'),
(436,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: 213\nContact: edqewa','2026-05-04 08:09:07'),
(437,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Supplier','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Supplier: qwe moved to archive.','2026-05-04 08:09:55'),
(438,139,'Ria Jane Perocho','haha@gmail.com','manager','created','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: manager','2026-05-04 08:10:09'),
(439,140,'Pepito Pepito','HA@GMAIL.COM','dispatcher','created','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: dispatcher','2026-05-04 08:11:50'),
(440,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +1 units (New total: 18)\nOffice Expense recorded: #51','2026-05-04 08:12:54'),
(441,141,'Ri Po','pepito@gmail.com','secretary','created','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: secretary','2026-05-04 08:14:13'),
(442,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Spare Part','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Part: Dggdgdgd\nPrice: ₱222.00\nStock Added: +1 units (New total: 1)\nOffice Expense recorded: #52','2026-05-04 08:15:03'),
(443,136,'Ria Jane Janeeeeeeeeeeeeeeeeee','criztelperocho@gmail.com','dispatcher','rejected','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-05-04 08:16:59'),
(444,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Part: ATF / CVT Transmission Fluid (1L)\nPrice: ₱650.00\nStock Added: +1 units (New total: 19)\nOffice Expense recorded: #53','2026-05-04 08:18:32'),
(445,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591 moved to archive system.','2026-05-04 08:30:25'),
(446,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196 moved to archive system.','2026-05-04 08:30:37'),
(447,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAK 9196 was restored from the system archive.','2026-05-04 08:30:46'),
(448,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: AAK 4591 was restored from the system archive.','2026-05-04 08:30:48'),
(449,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABC2425\nCategory: LAMBORGINI HAKDOG (2026)\nStatus: Active','2026-05-04 08:34:01'),
(450,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABCC 123\nCategory: LAMBORGINI WOWERS (2026)\nStatus: Active','2026-05-04 08:37:31'),
(451,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABCC 123\nCategory: LAMBORGINI WOWERS\nStatus: Active','2026-05-04 08:39:28'),
(452,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABCC 123 moved to archive system.','2026-05-04 08:41:37'),
(453,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABC2425 moved to archive system.','2026-05-04 08:41:55'),
(454,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Driver','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Elmer Andrade moved to archive.','2026-05-04 08:42:10'),
(455,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: RI RO\nLicense: A01-22-245677\nStatus: Available','2026-05-04 08:45:22'),
(456,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Driver Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Ria Jane Perocho\nLicense: A03-45-666666\nStatus: Available','2026-05-04 08:48:47'),
(457,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: NCR 2015-02362 moved to archive.','2026-05-04 08:54:21'),
(458,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 2607\nType: Preventive\nCost: ₱1,200.00\nParts used: Brake Fluid (500ml) (x1), Air Filter (Toyota Vios/Hiace) (x1)','2026-05-04 08:56:17'),
(459,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 2607\nType: Preventive\nRecord archived and stock returned.','2026-05-04 08:57:12'),
(460,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Felix Ausa\nUnit: ABF 2705\nType: Speeding\nSeverity: High','2026-05-04 08:58:14'),
(461,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Speeding\nDriver: Felix Ausa\nRecord moved to archive.','2026-05-04 08:58:34'),
(462,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','136.158.67.35','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Inventory STOCK: 1 pcs of Dggdgdgd moved to archive.','2026-05-04 11:14:22'),
(463,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Inventory STOCK: 1 pcs of Dggdgdgd was restored from the system archive.','2026-05-04 11:14:48'),
(464,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.52.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 11:56:07'),
(465,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 13:30:08'),
(466,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 13:30:49'),
(467,131,'Romy Thomas','Romy.dispatcher1@gmail.com','dispatcher','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 13:31:36'),
(468,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nDriver: Randy Genchez\nDate: 2026-05-04\nCollected: ₱900.00\nStatus: Paid','2026-05-04 13:47:42'),
(469,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 14:03:49'),
(470,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 14:15:03'),
(471,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Franchise Case','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: ewqeqw\nApplicant: AHTDOG\nType: qewqqwqweqew','2026-05-04 15:18:05'),
(472,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 15:19:08'),
(473,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: yanzkie ramos\nUnit: AAK 9196\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 16:02:05'),
(474,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 16:08:47'),
(475,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Felix Ausa\nUnit: AAQ 1743\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-04 16:44:22'),
(476,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Reset Maintenance Health','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Manual health reset for Unit: AAK 9196. Counter restarted at 15,686 KM.','2026-05-04 17:10:20'),
(477,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 17:18:00'),
(478,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Unit: AAQ 1743 recovered via Mobile App.','2026-05-04 17:41:27'),
(479,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Processed ₱500.00 cash payment from Jesus Duero for accident debt via Mobile App.','2026-05-04 17:44:38'),
(480,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: ewqeqw moved to archive via Mobile App.','2026-05-04 18:57:49'),
(481,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: ewqeqw moved to archive via Mobile App.','2026-05-04 18:57:53'),
(482,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: ewqeqw moved to archive via Mobile App.','2026-05-04 18:57:54'),
(483,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: sira_34 moved to archive via Mobile App.','2026-05-04 19:02:31'),
(484,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ASA 6135 recovered from status: Active','2026-05-04 19:03:55'),
(485,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ASA 6135 recovered from status: Active','2026-05-04 19:04:00'),
(486,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: 2012-0502 updated via Mobile App.','2026-05-04 19:07:53'),
(487,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Franchise Case','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: 2012-0502 moved to archive.','2026-05-04 19:15:48'),
(488,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Supplier','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Supplier: nnn\nContact: jjjj','2026-05-04 19:28:13'),
(489,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940\nDriver: Rebbel Mortrl\nDate: 2026-05-04\nCollected: ₱1,300.00\nStatus: Paid','2026-05-04 20:01:06'),
(490,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940\nDriver: Rebbel Mortrl\nNew Amount: ₱1,300.00 (Paid)','2026-05-04 20:01:21'),
(491,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: bb moved to archive.','2026-05-04 20:05:53'),
(492,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: jj moved to archive.','2026-05-04 20:05:57'),
(493,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Dggdgdgd moved to archive.','2026-05-04 20:06:09'),
(494,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: h\nPrice: ₱87.00','2026-05-04 20:07:47'),
(495,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAA 4540\nType: Preventive\nCost: ₱87.00\nParts used: h (x1)','2026-05-04 20:08:08'),
(496,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: h moved to archive.','2026-05-04 20:08:25'),
(497,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Iridium Spark Plugs (Set of 4) moved to archive.','2026-05-04 20:08:57'),
(498,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Spare Part','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Part: Iridium Spark Plugs (Set of 4) restored from archive.','2026-05-04 20:09:01'),
(499,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 20:10:31'),
(500,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-04 20:29:02'),
(501,130,'Rea Remitra','remitra.manager1@gmail.com','manager','logout','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 20:34:04'),
(502,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Franchise Case','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Case No: 2012-0502 updated via Mobile App.','2026-05-04 21:19:51'),
(503,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 21:25:43'),
(504,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Archived Boundary Rule','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Rule: qewewq was archived.','2026-05-04 21:49:29'),
(505,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Archived Boundary Rule','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Rule: Test was archived.','2026-05-04 21:49:34'),
(506,139,'Ria Jane Perocho','haha@gmail.comaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','manager','approved','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account details updated by Robert Garcia','2026-05-04 21:58:09'),
(507,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nStatus changed to: Pending','2026-05-04 22:30:56'),
(508,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 23:06:44'),
(509,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Franchise Case','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: 20120502\nApplicant: WANITO\nType: Franchise Verification','2026-05-05 00:50:09'),
(510,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Francisco Baja\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-05 00:51:58'),
(511,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Henry Belen\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-05 01:12:11'),
(512,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Recovered Unit','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEF 4940 recovered from status: Missing','2026-05-05 01:13:01'),
(513,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591\nDriver: Randy Genchez\nDate: 2026-05-05\nCollected: ₱1,100.00\nStatus: Paid','2026-05-05 02:22:29'),
(514,141,'Ri Po','pepito@gmail.com','secretary','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 02:23:28'),
(515,140,'Pepito Pepito','HA@GMAIL.COM','dispatcher','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 02:23:56'),
(516,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-05 03:39:22'),
(517,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-05 05:18:00'),
(518,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Record #37\nCategory: Spare Parts Purchase\nNew Amount: ₱7,650.00','2026-05-05 05:29:35'),
(519,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Record #37\nCategory: Spare Parts Purchase\nNew Amount: ₱1,700.00','2026-05-05 05:30:59'),
(520,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Record #45\nCategory: Damage Recovery\nNew Amount: ₱99.00','2026-05-05 05:31:12'),
(521,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Direct cash payment from Felimon Evangilista for accident debt (Incident Date: 2026-05-02) moved to archive.','2026-05-05 05:31:32'),
(522,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27) moved to archive.','2026-05-05 05:31:48'),
(523,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: General Cleaning moved to archive.','2026-05-05 05:32:26'),
(524,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27) moved to archive.','2026-05-05 05:32:36'),
(525,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: PURCHASED: Air Filter (Toyota Vios/Hiace) moved to archive.','2026-05-05 05:32:50'),
(526,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: wifi moved to archive.','2026-05-05 05:33:00'),
(527,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Wifi _45 moved to archive.','2026-05-05 05:33:09'),
(528,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: meralco moved to archive.','2026-05-05 05:33:39'),
(529,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: tubig moved to archive.','2026-05-05 05:34:03'),
(530,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Direct cash payment from Elmer Andrade for accident debt (Incident Date: 2026-05-01) moved to archive.','2026-05-05 05:34:13'),
(531,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Expense: Inventory STOCK: 1 pcs of ATF / CVT Transmission Fluid (1L) moved to archive.','2026-05-05 05:34:26'),
(532,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Direct cash payment from Jesus Duero for accident debt (Incident Date: 2026-04-27) was restored from the system archive.','2026-05-05 05:35:14'),
(533,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Direct cash payment from Elmer Andrade for accident debt (Incident Date: 2026-05-01) was restored from the system archive.','2026-05-05 05:35:27'),
(534,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Permanently Deleted Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Wifi _45 was permanently wiped from the database.','2026-05-05 05:36:17'),
(535,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Permanently Deleted Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: General Cleaning was permanently wiped from the database.','2026-05-05 05:36:22'),
(536,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Permanently Deleted Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Direct cash payment from Felimon Evangilista for accident debt (Incident Date: 2026-05-02) was permanently wiped from the database.','2026-05-05 05:36:28'),
(537,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Created Office Expense','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Category: Franchise Renewal\nDescription: FRANCHISE RENEWAL: Case #NCR 2018-4-2015-02370 (Old Expiry: Oct 31, 2028 -> New: May 06, 2026)\nAmount: ₱67.00','2026-05-05 05:37:20'),
(538,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Updated Boundary Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 4591\nDriver: Randy Genchez\nNew Amount: ₱1,100.00 (Paid)','2026-05-05 05:38:32'),
(539,139,'Ria Jane Perocho','haha@gmail.comaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','manager','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 05:46:16'),
(540,137,'Clark Tiquison','clarkjasontiquison@gmail.com','secretary','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 05:46:29'),
(541,133,'Ria Jane Perocho','perochoriajane4@gmail.com','dispatcher','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 05:46:37'),
(542,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 05:46:48'),
(543,132,'Yana Santiago','dianesantiago879@gmail.com','manager','rejected','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Account archived by Robert Garcia','2026-05-05 05:47:00'),
(544,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Office Expense','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Category: Spare Parts Purchase\nDescription: Inventory STOCK: 5 pcs of Brake Pads\nAmount: ₱1,500.00','2026-05-05 05:49:59'),
(545,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recovered Unit','192.168.1.2','Mozilla/5.0 (Linux; Android 13; ELN-W09 Build/HONORELN-W09; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Safari/537.36','Unit: NFH 3664 recovered via Mobile App.','2026-05-05 05:53:05'),
(546,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nStatus changed to: Pending','2026-05-05 05:53:22'),
(547,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','Unbanned Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola has been unbanned and status set to Available.','2026-05-05 06:58:43'),
(548,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-05 08:43:16'),
(549,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-05 08:44:16'),
(550,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.52.6','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-05 09:08:49'),
(551,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','175.176.52.6','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUnit: AAQ 1743\nType: Accident\nSeverity: High','2026-05-05 09:15:17'),
(552,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','209.35.171.228','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Login via MFA device verification.','2026-05-05 10:42:11'),
(553,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-06 11:30:14'),
(554,141,'Ri Po','pepito@gmail.com','secretary','approved','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account restored by Robert Garcia','2026-05-06 11:38:29'),
(555,141,'Ri Po','pepito@gmail.com','secretary','rejected','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Account archived by Robert Garcia','2026-05-06 11:38:38'),
(556,141,'Ri Po','pepito@gmail.com','secretary','failed_login','127.0.0.1','Symfony','Login blocked: account archived/disabled.','2026-05-06 11:47:38'),
(557,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','failed_login','127.0.0.1','Symfony','Login blocked: account disabled. Reason: Temporarily disabled for security audit.','2026-05-06 11:47:39'),
(558,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','failed_login','127.0.0.1','Symfony','Login blocked: account inactive.','2026-05-06 11:47:39'),
(559,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 14:59:12'),
(560,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-06 15:08:00'),
(561,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 15:21:41'),
(562,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 15:22:12'),
(563,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 16:58:43'),
(564,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUpdated details and status to Available','2026-05-06 17:00:19'),
(565,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Preventive\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 17:35:00'),
(566,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUpdated details and status to Available','2026-05-06 17:35:19'),
(567,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUpdated details and status to Available','2026-05-06 17:35:45'),
(568,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUpdated details and status to Available','2026-05-06 17:36:30'),
(569,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Oliver Ariola\nUpdated details and status to Available','2026-05-06 17:36:43'),
(570,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Arwin Azarcon has been unbanned and status set to Available.','2026-05-06 17:36:58'),
(571,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Driver Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: Arwin Azarcon\nUpdated details and status to Available','2026-05-06 17:37:45'),
(572,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAC 4989\nType: Preventive\nCost: ₱500.00\nParts used: brake hose (x1)','2026-05-06 17:38:39'),
(573,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 17:47:08'),
(574,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAA 9555\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 17:48:25'),
(575,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-06 17:54:29'),
(576,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-06 17:54:31'),
(577,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 17:54:59'),
(578,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 8102\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 17:55:33'),
(579,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:02:43'),
(580,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9716\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:03:12'),
(581,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 1140\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:04:53'),
(582,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:08:55'),
(583,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NCN 8583\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:09:29'),
(584,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nStatus changed to: Pending','2026-05-06 18:09:48'),
(585,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEW 6279\nType: Preventive\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 18:10:14'),
(586,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NDG 7105\nType: Preventive\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 18:12:17'),
(587,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Emergency\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:12:51'),
(588,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:16:13'),
(589,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:17:58'),
(590,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAE 1247\nType: Emergency\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:18:41'),
(591,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:27:58'),
(592,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NGA 5044\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:28:34'),
(593,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nStatus changed to: Pending','2026-05-06 18:30:40'),
(594,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nStatus changed to: Pending','2026-05-06 18:30:43'),
(595,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAC 4989\nStatus changed to: Pending','2026-05-06 18:30:45'),
(596,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAX 5430\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:32:08'),
(597,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NBR 1341\nType: Preventive\nCost: ₱88.00\nParts used: bb (x1)','2026-05-06 18:32:34'),
(598,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAA 9555\nStatus changed to: Pending','2026-05-06 18:32:42'),
(599,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 8102\nStatus changed to: Pending','2026-05-06 18:32:48'),
(600,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NCN 8583\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:34:56'),
(601,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: VAA 9864\nType: Preventive\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 18:35:12'),
(602,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: DAZ 9769\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:35:38'),
(603,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:36:49'),
(604,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:36:52'),
(605,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAC 4989\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:36:57'),
(606,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9716\nStatus changed to: Pending','2026-05-06 18:36:59'),
(607,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 1140\nStatus changed to: Pending','2026-05-06 18:37:01'),
(608,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NGA 5044\nStatus changed to: Pending','2026-05-06 18:37:04'),
(609,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAE 1247\nStatus changed to: Pending','2026-05-06 18:37:08'),
(610,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Toggled Maintenance Status','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nStatus changed to: Pending','2026-05-06 18:37:10'),
(611,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAA 9555\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:14'),
(612,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 8102\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:18'),
(613,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAD 1140\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:21'),
(614,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAV 9716\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:23'),
(615,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NEW 6279\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:31'),
(616,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NCN 8583\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:34'),
(617,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NDG 7105\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:45'),
(618,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Emergency\nRecord archived and stock returned.','2026-05-06 18:37:48'),
(619,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAE 1247\nType: Emergency\nRecord archived and stock returned.','2026-05-06 18:37:50'),
(620,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NGA 5044\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:53'),
(621,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAX 5430\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:55'),
(622,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NBR 1341\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:37:58'),
(623,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NCN 8583\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:01'),
(624,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: VAA 9864\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:08'),
(625,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: DAZ 9769\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:12'),
(626,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:15'),
(627,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAA 4540\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:18'),
(628,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:21'),
(629,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAK 9196\nType: Corrective\nRecord archived and stock returned.','2026-05-06 18:38:24'),
(630,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 7471\nType: Corrective\nRecord archived and stock returned.','2026-05-06 18:38:26'),
(631,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABG 7479\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:29'),
(632,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nType: Preventive\nRecord archived and stock returned.','2026-05-06 18:38:33'),
(633,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:40:18'),
(634,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:40:32'),
(635,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:40:42'),
(636,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAX 5430\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:41:14'),
(637,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NGP 1877\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:41:58'),
(638,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 18:51:37'),
(639,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: DAU 9027\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 18:52:10'),
(640,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAE 1919\nType: Emergency\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 18:52:36'),
(641,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: dian Santiago Dian has been unbanned and status set to Available.','2026-05-06 18:53:30'),
(642,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Driver: dian Santiago Dian\nUnit: NEF 4940\nType: The vehicle unit was taken/stolen\nSeverity: Critical','2026-05-06 18:54:05'),
(643,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: NAN 1349\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-06 19:02:07'),
(644,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: EAD 7438\nType: Preventive\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-06 19:08:56'),
(645,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 19:15:43'),
(646,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 19:20:11'),
(647,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 19:54:41'),
(648,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 20:02:41'),
(649,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 20:14:38'),
(650,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 20:26:17'),
(651,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 20:45:53'),
(652,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 21:41:10'),
(653,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 21:52:58'),
(654,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 21:59:08'),
(655,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 21:59:26'),
(656,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 22:43:04'),
(657,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 22:47:10'),
(658,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 22:59:28'),
(659,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 13; RMX3430 Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-06 23:15:02'),
(660,NULL,NULL,NULL,NULL,'failed_login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-06 23:54:21'),
(661,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:00:50'),
(662,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:14:00'),
(663,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:19:04'),
(664,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:42:48'),
(665,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:50:43'),
(666,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 00:54:20'),
(667,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:58:28'),
(668,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:58:35'),
(669,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3989:2646::3d0:3','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 01:34:29'),
(670,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.53.12','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 01:44:33'),
(671,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','175.176.53.12','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 01:45:21'),
(672,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Franchise Case','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Case No: 201401287\nApplicant: EUROTAXI INC.\nType: FranchiseRenewalTransfer','2026-05-07 01:51:18'),
(673,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:12:00'),
(674,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: DAT 1367\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-07 02:12:53'),
(675,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:15:06'),
(676,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398c:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:27:11'),
(677,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3988:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:33:51'),
(678,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:40:24'),
(679,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398d:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:41:28'),
(680,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:43:51'),
(681,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:48:28'),
(682,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:50:56'),
(683,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:54:50'),
(684,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:57:35'),
(685,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 02:58:25'),
(686,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac1:31e0:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 03:03:26'),
(687,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3988:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 03:06:36'),
(688,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-07 03:08:24'),
(689,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Login via MFA device verification.','2026-05-07 21:56:59'),
(690,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: AAK 9196\nDriver: Rebbel Mortrl\nDate: 2026-05-07\nCollected: ₱1,000.00\nStatus: Paid','2026-05-07 23:45:25'),
(691,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: AAK 9196\nDriver: Rebbel Mortrl\nNew Amount: ₱1,000.00 (Paid)','2026-05-07 23:46:04'),
(692,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: AAK 9196\nDriver: Rebbel Mortrl\nNew Amount: ₱1,000.00 (Paid)','2026-05-07 23:46:27'),
(693,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-08 10:10:47'),
(694,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-08 10:18:03'),
(695,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-08 10:18:06'),
(696,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-08 10:18:58'),
(697,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-08 10:21:46'),
(698,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABF 2705\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-08 10:26:31'),
(699,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-08 12:02:38'),
(700,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: CAT 6073\nType: Emergency\nCost: ₱650.00\nParts used: ATF / CVT Transmission Fluid (1L) (x1)','2026-05-08 12:06:19'),
(701,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: DCQ 1551\nDriver: Almar Monarba\nDate: 2026-05-08\nCollected: ₱1,200.00\nStatus: Paid','2026-05-08 14:21:18'),
(702,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-08 14:23:16'),
(703,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Staff Record','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff: Ria Jane Calubayan Perocho56_ moved to archive.','2026-05-08 16:05:41'),
(704,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Staff Record','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Staff: Ria Jane Calubayan Perocho moved to archive.','2026-05-08 16:05:50'),
(705,129,'Shiella Marie Orilla','shiellamarie.sec@gmail.com','secretary','logout','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-08 17:27:00'),
(706,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-08 17:27:56'),
(707,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Failed login for: shiellamarie.sec@gmail.com','2026-05-08 18:41:15'),
(708,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-08 18:46:23'),
(709,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Login via MFA device verification.','2026-05-08 18:49:28'),
(710,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-08 18:50:30'),
(711,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Driver Account: Test Driver deleted from the system.','2026-05-08 19:07:57'),
(712,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: NCJ 7661\nDriver: rennel \nDate: 2026-05-08\nCollected: ₱1,400.00\nStatus: Paid','2026-05-08 23:58:43'),
(713,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: DCQ 1551\nDriver: Almar Monarba\nDate: 2026-05-09\nCollected: ₱1,100.00\nStatus: Paid','2026-05-09 00:09:00'),
(714,153,'Ruben Patajo','rennelgesto@gmail.com','driver','New Support Ticket','180.191.75.107','Mozilla/5.0 (Linux; Android 16; sdk_gphone64_x86_64 Build/BE2A.250530.026.D1; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.112 Mobile Safari/537.36','Driver Ruben Patajo submitted a new support ticket: testin','2026-05-09 14:02:55'),
(715,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Maintenance Record','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: AAQ 1743\nType: Emergency\nCost: ₱850.00\nParts used: Air Filter (Toyota Vios/Hiace) (x1)','2026-05-09 14:27:23'),
(716,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-09 15:45:52'),
(717,NULL,NULL,NULL,NULL,'failed_login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-09 15:45:53'),
(718,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-09 15:45:59'),
(719,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Shortage Settlement','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Processed ₱600.00 cash payment from Roberto Sunico to settle boundary shortage.','2026-05-09 19:49:41'),
(720,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Driver Account: Rennel deleted from the system.','2026-05-09 19:53:57'),
(721,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Item: Rennel was permanently wiped from the database.','2026-05-09 20:44:44'),
(722,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Driver Account: Almar Monarba deleted from the system.','2026-05-09 20:46:36'),
(723,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Item: Almar Monarba was permanently wiped from the database.','2026-05-09 20:47:11'),
(724,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Driver Account: Ruben Patajo deleted from the system.','2026-05-09 20:48:33'),
(725,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Item: Ruben Patajo was permanently wiped from the database.','2026-05-09 20:49:07'),
(726,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Item: Almar Monarba was permanently wiped from the database.','2026-05-09 22:04:44'),
(727,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','Unit: DCQ 1551\nDriver: \nNew Amount: ₱1,200.00 (Paid)','2026-05-09 22:13:38'),
(728,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Jose Camillotes deleted from the system.','2026-05-10 20:42:40'),
(729,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Jose Camillotes was permanently wiped from the database.','2026-05-10 20:43:00'),
(730,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando deleted from the system.','2026-05-10 21:03:29'),
(731,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Joel Sumando was permanently wiped from the database.','2026-05-10 21:04:32'),
(732,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted User','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Joel Sumando was permanently wiped from the database.','2026-05-10 21:21:47'),
(733,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando deleted from the system.','2026-05-10 21:25:44'),
(734,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored User','2001:fd8:cb6a:fb00:b0a4:ac01:8120:d6dc','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Joel Sumando was restored from the system archive.','2026-05-10 21:25:55'),
(735,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','175.176.52.21','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36','Unit: DCQ 1551\nDriver: Almar Monarba\nDate: 2026-05-11\nCollected: ₱600.00\nStatus: Paid','2026-05-11 07:32:35'),
(736,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: BDEHF376 moved to archive system.','2026-05-11 17:06:46'),
(737,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:8da3:6306:3388:7e62','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nDriver: Joel Sumando\nDate: 2026-05-11\nCollected: ₱1,300.00\nStatus: Paid','2026-05-11 18:14:37'),
(738,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:cb6a:fb00:8da3:6306:3388:7e62','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱550.00 (Paid)','2026-05-11 18:15:55'),
(739,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:8da3:6306:3388:7e62','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 9196\nDriver: Joel Sumando\nDate: 2026-05-11\nCollected: ₱1,000.00\nStatus: Paid','2026-05-11 18:17:38'),
(740,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD1256 moved to archive system.','2026-05-11 18:21:29'),
(741,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW moved to archive system.','2026-05-11 18:27:22'),
(742,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD1245 moved to archive system.','2026-05-11 18:39:34'),
(743,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ACD123 moved to archive system.','2026-05-11 18:46:23'),
(744,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Boundary','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: ID# 33 was restored from the system archive.','2026-05-11 18:54:46'),
(745,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: WWWWWWWW was restored from the system archive.','2026-05-11 19:02:27'),
(746,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: ACD1256 was restored from the system archive.','2026-05-11 19:02:30'),
(747,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: ABC12344 was restored from the system archive.','2026-05-11 19:02:32'),
(748,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: BDEHF376 was restored from the system archive.','2026-05-11 19:02:35'),
(749,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: ACD1245 was restored from the system archive.','2026-05-11 19:02:39'),
(750,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(751,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(752,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(753,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(754,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(755,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(756,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:19'),
(757,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Damage]: Driver returned unit with damage reported during boundary turnover. was restored from the system archive.','2026-05-11 19:23:20'),
(758,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:23'),
(759,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:23'),
(760,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:23'),
(761,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(762,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(763,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(764,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(765,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(766,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(767,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Item: Auto-logged [Breakdown]: Unit broke down immediately upon deployment. No boundary collected (No Boundary). was restored from the system archive.','2026-05-11 19:23:24'),
(768,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Reset Maintenance Health','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Manual health reset for Unit: AAK 4591. Counter restarted at 93,845 KM.','2026-05-11 19:59:24'),
(769,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: BDEHF376 moved to archive system.','2026-05-11 19:59:36'),
(770,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-11 20:05:02'),
(771,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: WWWWWWWW moved to archive system.','2026-05-11 20:13:41'),
(772,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Unit: ABC12344 moved to archive system.','2026-05-11 20:13:51'),
(773,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Incident','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','Type: Accident\nDriver: Oliver Ariola\nRecord moved to archive.','2026-05-11 20:14:38'),
(774,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:cb6a:fb00:8da3:6306:3388:7e62','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 4591\nDriver: Joel Sumando\nDate: 2026-05-12\nCollected: ₱1,000.00\nStatus: Shortage','2026-05-12 00:49:47'),
(775,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: Robertgarcia.owner@gmail.com','2026-05-18 13:39:38'),
(776,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-18 13:39:51'),
(777,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-18 13:39:55'),
(778,NULL,NULL,NULL,NULL,'failed_login','143.44.152.83','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-18 13:40:48'),
(779,NULL,NULL,NULL,NULL,'failed_login','143.44.152.83','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia@gmail.com','2026-05-18 13:41:20'),
(780,NULL,NULL,NULL,NULL,'failed_login','143.44.152.83','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia@gmail.com','2026-05-18 13:41:25'),
(781,NULL,NULL,NULL,NULL,'failed_login','143.44.152.83','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia@gmail.com','2026-05-18 13:41:59'),
(782,NULL,NULL,NULL,NULL,'failed_login','119.92.236.166','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 19:29:03'),
(783,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: Robertgarcia.owner@gmail.com','2026-05-19 21:38:52'),
(784,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:38:58'),
(785,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robert.owner@gmail.com','2026-05-19 21:39:15'),
(786,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:39:28'),
(787,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','created','119.92.236.166','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Staff account created by Robert Garcia with role: manager','2026-05-19 21:42:32'),
(788,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:48:37'),
(789,NULL,NULL,NULL,NULL,'failed_login','124.106.53.219','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:48:42'),
(790,NULL,NULL,NULL,NULL,'failed_login','124.106.53.219','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:48:48'),
(791,NULL,NULL,NULL,NULL,'failed_login','124.106.53.219','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:48:57'),
(792,NULL,NULL,NULL,NULL,'failed_login','124.106.53.219','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-05-19 21:49:08'),
(793,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','password_changed','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Staff forced password change completed.','2026-05-20 01:09:56'),
(794,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 01:09:56'),
(795,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 01:12:30'),
(796,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 01:12:44'),
(797,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 01:14:07'),
(798,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','49.144.210.255','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Login via MFA device verification.','2026-05-20 18:24:21'),
(799,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:24:38'),
(800,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:24:46'),
(801,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:25:00'),
(802,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:25:06'),
(803,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:25:12'),
(804,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','222.127.153.193','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:26:40'),
(805,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','124.105.200.176','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:59:03'),
(806,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','124.105.200.176','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:59:07'),
(807,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.66.143','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-23 14:28:52'),
(808,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.66.143','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-23 14:28:57'),
(809,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','136.158.66.143','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-23 14:29:01'),
(810,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.66.143','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-23 16:56:55'),
(811,NULL,NULL,NULL,NULL,'failed_login','104.198.37.4','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','Failed login for: Email or Username','2026-05-25 11:28:31'),
(812,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e253:1700:796d:6e50:ba58:c3d5','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 4591\nDriver: Almar Monarba\nDate: 2026-05-25\nCollected: ₱550.00\nStatus: Paid','2026-05-25 11:43:16'),
(813,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAK 4591\nCategory: Toyota Vios\nStatus: Active','2026-05-27 10:33:28'),
(814,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAA 4591\nCategory: Toyota Vios\nStatus: Active','2026-05-27 10:33:42'),
(815,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver: July Sunico has been unbanned and status set to Available.','2026-05-27 11:15:19'),
(816,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAA 4591\nCategory: Toyota Vios\nStatus: Active','2026-05-27 11:15:36'),
(817,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver: Roberto Sunico has been unbanned and status set to Available.','2026-05-27 11:16:53'),
(818,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Spare Part','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Part: Air Filter (Toyota Vios/Hiace)\nPrice: ₱850.00\nStock Added: +4 units (New total: 157)\nOffice Expense recorded: #58','2026-05-27 21:24:19'),
(820,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Spare Part','136.158.66.91','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Part: Brake Disk\nPrice: ₱1,500.00','2026-05-29 14:23:13'),
(821,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e253:1700:206f:337f:8360:794e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nDriver: Joel Sumando\nDate: 2026-05-29\nCollected: ₱1,300.00\nStatus: Paid','2026-05-29 20:39:53'),
(822,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e253:1700:b0b1:80d7:4b8a:2234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: DCQ 1551\nDriver: Almar Monarba\nDate: 2026-06-01\nCollected: ₱600.00\nStatus: Paid','2026-06-01 11:26:57'),
(823,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Created Unit','112.198.120.214','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAK 4592\nCategory: TOYOTA VIOS (2026)\nStatus: Coding','2026-06-01 16:46:38'),
(824,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Unit','112.198.120.214','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAK 4592 moved to archive system.','2026-06-01 16:46:48'),
(825,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Permanently Deleted Unit','112.198.120.214','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Item: AAK 4592 was permanently wiped from the database.','2026-06-01 16:47:57'),
(826,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Banned Driver','111.90.196.4','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver Oliver Ariola has been permanently banned. Reason: hhh','2026-06-02 18:10:53'),
(827,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','111.90.196.4','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver: Oliver Ariola has been unbanned and status set to Available.','2026-06-02 18:11:19'),
(828,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','111.90.231.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver: Oliver Ariola has been unbanned and status set to Available.','2026-06-02 18:53:52'),
(829,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Suspended Driver','111.90.231.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver Oliver Ariola has been suspended for 7 days. Reason: sssssssssssssssssssssssssssssssssssssssssssssssssss                                                                          dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd                                                                                                                                                                                                      ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd','2026-06-02 19:03:22'),
(830,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','124.105.29.172','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver: Arwin Azarcon has been unbanned and status set to Available.','2026-06-03 20:00:23'),
(831,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','110.54.155.56','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: NEF 4940\nCategory: Toyota Vios\nStatus: Active','2026-06-06 09:24:52'),
(832,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','216.247.89.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: NEF 4940\nCategory: Toyota Vios\nStatus: Active','2026-06-07 13:03:29'),
(833,NULL,NULL,NULL,NULL,'failed_login','124.105.29.172','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','Failed login for: robertgarcia.owner@gmail.com','2026-06-07 18:47:19'),
(834,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','112.208.168.90','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Login via MFA device verification.','2026-06-09 19:04:21'),
(835,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','222.127.153.193','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Login via MFA device verification.','2026-06-10 06:36:38'),
(836,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','222.127.153.193','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 06:43:38'),
(837,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','222.127.153.193','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 06:44:20'),
(838,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Login via MFA device verification.','2026-06-10 10:01:51'),
(839,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: CAV 2607\nDriver: Joel Sumando\nDate: 2026-06-10\nCollected: ₱300.00\nStatus: Shortage','2026-06-10 10:10:03'),
(840,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱600.00 (Shortage)','2026-06-10 10:11:02'),
(841,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: CAV 2607\nDriver: Joel Sumando\nDate: 2026-06-11\nCollected: ₱100.00\nStatus: Shortage','2026-06-10 10:12:01'),
(842,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: AAA 4591\nDriver: Almar Monarba\nDate: 2026-06-10\nCollected: ₱100.00\nStatus: Shortage','2026-06-10 10:14:51'),
(843,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱500.00 (Shortage)','2026-06-10 10:16:27'),
(844,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','124.106.165.55','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱100.00 (Shortage)','2026-06-10 10:34:37'),
(845,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','124.106.165.55','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','Driver Account: Almar Monarba deleted from the system.','2026-06-10 10:43:48'),
(846,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','logout','2405:8d40:4c8d:90cf:dc1d:be60:589d:623c','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 11:19:13'),
(847,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','logout','2405:8d40:4c8d:90cf:dc1d:be60:589d:623c','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 11:19:24'),
(848,160,'Clark Tiquison','tiquisonclark@gmail.com','manager','login','209.35.171.23','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-06-10 11:24:18'),
(849,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando moved to archive.','2026-06-11 11:13:13'),
(850,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored User','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Joel Sumando was restored from the system archive.','2026-06-11 11:13:33'),
(851,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deactivated Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando was Deactivated.','2026-06-11 16:25:31'),
(852,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deactivated Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando was Deactivated.','2026-06-11 16:38:32'),
(853,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Activated Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando was Activated.','2026-06-11 16:39:06'),
(854,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Activated Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando was Activated.','2026-06-11 16:44:46'),
(855,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Suspended Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Joel Sumando has been suspended for 7 days. Reason: TESTAAA','2026-06-11 16:45:59'),
(856,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver: Joel Sumando has been unbanned and status set to Available.','2026-06-11 16:46:48'),
(857,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Activated Mobile App Driver','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando was Activated.','2026-06-11 16:47:06'),
(858,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 9196\nCategory: Toyota Vios\nStatus: Maintenance','2026-06-11 17:28:18'),
(859,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱1,300.00 (Paid)','2026-06-11 17:31:41'),
(860,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Boundary Record','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nDriver: Joel Sumando\nNew Amount: ₱1,000.00 (Paid)','2026-06-11 17:37:21'),
(861,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 9196\nDriver: Joel Sumando\nDate: 2026-06-11\nCollected: ₱900.00\nStatus: Shortage','2026-06-11 17:39:12'),
(862,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAK 9196\nCategory: Toyota Vios\nStatus: Maintenance','2026-06-11 18:07:33'),
(863,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: NCW 5011\nCategory: Toyota Vios\nStatus: Active','2026-06-11 18:28:44'),
(864,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: NCW 5011\nCategory: Toyota Vios\nStatus: Active','2026-06-11 18:44:02'),
(865,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e253:1700:496c:5326:4769:142e','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: CAV 2607\nCategory: Toyota Vios\nStatus: Coding','2026-06-11 18:45:45'),
(866,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Deleted Mobile App Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: Joel Sumando deleted from the system.','2026-06-20 15:54:04'),
(867,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Archived Mobile App Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Account: July Sunico moved to archive.','2026-06-20 16:00:25'),
(868,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored User','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: July Sunico was restored from the system archive.','2026-06-20 16:00:35'),
(869,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Suspended Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Joel Sumando has been suspended for 3 days. Reason: tamad','2026-06-20 17:04:25'),
(870,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver: Joel Sumando has been unbanned and status set to Available.','2026-06-20 17:37:31'),
(871,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: ALA 3699\nCategory: Toyota Vios\nStatus: Vacant','2026-06-20 17:48:40'),
(872,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Banned Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Joel Sumando has been permanently banned. Reason: test lng sa app','2026-06-20 18:19:50'),
(873,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver: Joel Sumando has been unbanned and status set to Available.','2026-06-20 18:34:18'),
(874,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Restored User','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Item: Joel Sumando was restored from the system archive.','2026-06-20 18:47:25'),
(875,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: ALA 3699\nCategory: Toyota Vios\nStatus: Vacant','2026-06-20 18:48:08'),
(876,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Banned Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver Joel Sumando has been permanently banned. Reason: testing','2026-06-20 18:49:05'),
(877,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Driver: Joel Sumando has been unbanned and status set to Available.','2026-06-20 18:49:41'),
(878,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: ALA 3699\nCategory: Toyota Vios\nStatus: Vacant','2026-06-20 18:50:14'),
(879,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','111.90.198.145','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','Login via MFA device verification.','2026-06-21 10:01:52'),
(880,130,'Rea Remitra','remitra.manager1@gmail.com','manager','login','111.90.198.145','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36','Login via MFA device verification.','2026-06-21 10:09:24'),
(881,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0','Unit: AAA 4591\nDriver: Joel Sumando\nDate: 2026-06-21\nCollected: ₱900.00\nStatus: Paid','2026-06-21 20:13:20'),
(882,18,'Sunibertson R. Sunico','sonysunico02@gmail.com','Developer','failed_login','2001:fd8:e240:2100:2950:2836:1de8:8dd1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Login blocked: account archived/disabled.','2026-06-21 21:59:10'),
(883,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','login','136.158.66.58','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Login via MFA device verification.','2026-06-22 16:24:10'),
(884,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e240:2100:4412:9084:ddf4:884d','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Unit: AAA 4591\nCategory: Toyota Vios\nStatus: Active','2026-06-23 18:57:08'),
(885,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e240:2100:4412:9084:ddf4:884d','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Unit: AAK 9196\nCategory: Toyota Vios\nStatus: Maintenance','2026-06-23 19:16:12'),
(886,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Recorded Incident','2001:fd8:e25d:4800:3c94:45a2:4bbc:7898','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Driver: Joel Sumando\nUnit: ALA 3699\nType: Vehicle Damage\nSeverity: High','2026-06-24 16:17:04'),
(887,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','2001:fd8:e25d:4800:3c94:45a2:4bbc:7898','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Processed ₱1,900.00 cash payment from Joel Sumando for accident debt.','2026-06-24 16:18:19'),
(888,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Debt Payment','2001:fd8:e25d:4800:3c94:45a2:4bbc:7898','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Processed ₱44.00 cash payment from Joel Sumando for accident debt.','2026-06-24 16:19:30'),
(889,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Boundary Remittance','2001:fd8:e25d:4800:3c94:45a2:4bbc:7898','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Unit: ALA 3699\nDriver: Joel Sumando\nDate: 2026-06-24\nCollected: ₱1,000.00\nStatus: Shortage','2026-06-24 16:23:09'),
(890,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Banned Driver','2001:fd8:e25d:4800:304d:2cd:4dad:f389','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Driver Joel Sumando has been permanently banned. Reason: TESSSSSS','2026-06-25 17:54:32'),
(891,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Unbanned Driver','2001:fd8:e25d:4800:304d:2cd:4dad:f389','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Driver: Joel Sumando has been unbanned and status set to Available.','2026-06-25 17:55:50'),
(892,125,'Robert Garcia','robertgarcia.owner@gmail.com','super_admin','Updated Unit','2001:fd8:e25d:4800:304d:2cd:4dad:f389','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','Unit: ALA 3699\nCategory: Toyota Vios\nStatus: Vacant','2026-06-25 17:56:47');
/*!40000 ALTER TABLE `login_audit` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `maintenance_type` enum('preventive','corrective','emergency') NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `odometer_reading` int(11) DEFAULT NULL,
  `date_started` date NOT NULL,
  `date_completed` date DEFAULT NULL,
  `status` enum('pending','in_progress','in_shop','testing','completed','cancelled') DEFAULT 'pending',
  `mechanic_name` varchar(100) DEFAULT NULL,
  `parts_list` text DEFAULT NULL,
  `parts_used` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `maintenance_driver_id_foreign` (`driver_id`),
  CONSTRAINT `maintenance_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `maintenance` WRITE;
/*!40000 ALTER TABLE `maintenance` DISABLE KEYS */;
INSERT INTO `maintenance` VALUES
(1,160,65,'preventive','ARAY',850.00,NULL,'2026-04-13','2026-04-14','completed','Abran A. Oracion, Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-04-13 00:31:03','2026-04-14 02:48:31',18,18,NULL),
(2,160,64,'corrective','Automatic entry: Reported broken down immediately upon deployment (No Boundary).',0.00,NULL,'2026-04-14','2026-04-25','completed',NULL,NULL,NULL,'2026-04-14 02:45:39','2026-04-25 14:43:08',18,18,NULL),
(3,160,64,'corrective','Automatic entry: Reported broken down immediately upon deployment (No Boundary).',0.00,NULL,'2026-04-14','2026-04-25','completed',NULL,NULL,NULL,'2026-04-14 02:46:34','2026-04-25 14:42:56',18,18,NULL),
(4,133,29,'corrective','Automatic entry: Reported broken down immediately upon deployment (No Boundary).',0.00,NULL,'2026-04-14','2026-04-25','completed',NULL,NULL,NULL,'2026-04-14 04:20:58','2026-04-25 14:03:08',18,18,NULL),
(5,1,75,'emergency','Automatic entry: Reported broken down immediately upon deployment (No Boundary).\r\n\r\nDispatcher Notes:\r\nqwwwwwwwwwwwdddeqwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww',14830.00,NULL,'2026-04-14','2026-04-25','completed','Callito A.  Belmar','ATF / CVT Transmission Fluid (1L) (x1), Brake Shoes Rear (x1), Clutch Disc (Genuine) (x1), Shock Absorber Front (Pair) (x1), Wheel Hub / Bearing Front (x1), wdqfniwfninn3ifnini3nfi34nfi3nnffffffffffffffffffffffffffffffffffffffffffffffffffffffff',NULL,'2026-04-14 04:24:09','2026-04-25 14:43:15',18,18,NULL),
(6,12,51,'preventive',NULL,850.00,NULL,'2026-04-25','2026-04-25','pending','Joel H. Llouido','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-04-25 14:46:37','2026-04-25 14:46:37',18,18,NULL),
(7,5,68,'preventive',NULL,850.00,NULL,'2026-04-25',NULL,'pending','Nilo E. Dugu','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-04-25 14:51:06','2026-04-25 14:51:06',18,18,NULL),
(8,6,35,'preventive',NULL,850.00,NULL,'2026-04-26','2026-04-26','completed','Callito A.  Belmar, Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-04-26 09:21:55','2026-04-26 09:27:50',18,18,NULL),
(9,2,18,'corrective','Automatic entry: Reported broken down during boundary turnover (Half Boundary).\r\nComputation: 322.75 hrs x ₱45.83/hr',650.00,NULL,'2026-04-27',NULL,'testing','Callito A.  Belmar','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-04-27 00:55:32','2026-04-30 00:23:56',18,125,NULL),
(10,2,105,'corrective','Automatic entry: Reported broken down during boundary turnover (Half Boundary).\nComputation: 79.18 hrs x ₱45.83/hr',0.00,NULL,'2026-04-30',NULL,'pending',NULL,NULL,NULL,'2026-04-30 08:07:23','2026-05-04 22:30:56',125,125,NULL),
(11,5,37,'preventive',NULL,5351.00,NULL,'2026-04-30','2026-05-01','pending','Marlon P. Nalaluan','Air Filter (Toyota Vios/Hiace) (x2), ATF / CVT Transmission Fluid (1L) (x1), kupal',NULL,'2026-04-30 09:39:14','2026-05-01 16:05:16',125,125,NULL),
(12,133,29,'preventive',NULL,16500.00,NULL,'2026-04-30','2026-04-30','pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1), ATF / CVT Transmission Fluid (1L) (x1), labordaybukas',NULL,'2026-04-30 09:40:31','2026-04-30 09:40:31',125,125,NULL),
(13,12,35,'preventive',NULL,650850.00,NULL,'2026-04-30','2026-04-24','pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1), 222',NULL,'2026-04-30 10:18:13','2026-04-30 10:18:13',125,125,NULL),
(14,12,51,'preventive','huhu',1950.00,NULL,'2026-04-30','2026-04-29','pending','Willard A. Nialega','ATF / CVT Transmission Fluid (1L) (x3)',NULL,'2026-04-30 21:46:36','2026-04-30 21:46:36',125,125,NULL),
(15,116,5,'corrective','Automatic entry: Reported broken down during boundary turnover (Half Boundary).\nComputation: 416.72 hrs x ₱50.00/hr\n\nDispatcher Notes:\nhahahaaha',0.00,NULL,'2026-04-30',NULL,'pending',NULL,NULL,NULL,'2026-04-30 22:53:49','2026-04-30 22:53:49',125,125,NULL),
(16,1,106,'preventive','dddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd',1500.00,NULL,'2026-05-01',NULL,'pending','Callito A.  Belmar, Callito A.  Belmar, Callito A.  Belmar, Nilo E. Dugu, Nilo E. Dugu','Air Filter (Toyota Vios/Hiace) (x1), ATF / CVT Transmission Fluid (1L) (x1), eqwwwwwwwwwwwwwwwwwwwwwwwwwwww',NULL,'2026-05-01 17:05:50','2026-05-01 17:05:50',125,125,NULL),
(17,191,30,'preventive',NULL,850.00,NULL,'2026-05-02',NULL,'pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-02 20:23:21','2026-05-06 18:38:21',125,125,'2026-05-06 18:38:21'),
(18,1,106,'corrective','Automatic entry: Reported broken down immediately upon deployment (No Boundary).\nNote: Driver claimed \'Free Boundary\' but unit was out for 35.53 hrs.',0.00,NULL,'2026-05-02',NULL,'in_progress',NULL,NULL,NULL,'2026-05-02 20:28:25','2026-05-06 18:38:24',125,125,'2026-05-06 18:38:24'),
(19,12,37,'emergency','aa',650.00,NULL,'2026-05-02','2026-05-08','pending','ANGELA','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-02 20:31:33','2026-05-02 20:32:09',125,125,'2026-05-02 20:32:09'),
(20,113,109,'corrective','Automatic entry: Reported broken down immediately upon deployment (No Boundary).\nNote: Driver claimed \'Free Boundary\' but unit was out for 463.98 hrs.\n\nDispatcher Notes:\nhhhahah_hi',0.00,NULL,'2026-05-02',NULL,'pending',NULL,NULL,NULL,'2026-05-02 22:09:36','2026-05-06 18:38:26',125,125,'2026-05-06 18:38:26'),
(21,114,37,'preventive',NULL,650.00,NULL,'2026-05-02',NULL,'pending','Marlon P. Nalaluan','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-02 22:20:53','2026-05-06 18:38:29',125,125,'2026-05-06 18:38:29'),
(22,2,80,'preventive',NULL,0.00,NULL,'2026-05-02',NULL,'pending','Joel H. Llouido',NULL,NULL,'2026-05-02 22:43:51','2026-05-06 18:38:33',125,125,'2026-05-06 18:38:33'),
(23,1,107,'preventive',NULL,0.00,NULL,'2026-05-02',NULL,'pending','Callito A.  Belmar',NULL,NULL,'2026-05-02 22:44:03','2026-05-02 22:44:03',125,125,NULL),
(24,122,11,'preventive','bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb',1200.00,NULL,'2026-05-04','2026-05-05','pending','Abran A. Oracion','Brake Fluid (500ml) (x1), Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-04 08:56:17','2026-05-04 08:57:12',125,125,'2026-05-04 08:57:12'),
(25,137,34,'preventive','igyiiiiiiiiiiiiihuihihu                 9i9i-9iu-9 u08u087807',87.00,NULL,'2026-05-04','2026-05-05','completed','Abran A. Oracion','h (x1)',NULL,'2026-05-04 20:08:08','2026-05-06 18:38:18',125,125,'2026-05-06 18:38:18'),
(26,1,101,'preventive','wa',600.00,NULL,'2026-05-05',NULL,'pending',NULL,NULL,NULL,'2026-05-05 00:47:24','2026-05-06 18:38:15',125,125,'2026-05-06 18:38:15'),
(27,12,37,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 16:58:43','2026-05-06 18:36:49',125,125,'2026-05-06 18:36:49'),
(28,1,80,'preventive',NULL,650.00,NULL,'2026-05-06','2026-05-06','completed','Abran A. Oracion','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 17:35:00','2026-05-06 18:36:52',125,125,'2026-05-06 18:36:52'),
(29,146,48,'preventive','dad',500.00,NULL,'2026-05-06','2026-05-06','completed','Joel H. Llouido','brake hose (x1)',NULL,'2026-05-06 17:38:39','2026-05-06 18:36:57',125,125,'2026-05-06 18:36:57'),
(30,138,37,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Joel H. Llouido','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 17:48:25','2026-05-06 18:37:14',125,125,'2026-05-06 18:37:14'),
(31,154,57,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 17:55:33','2026-05-06 18:37:18',125,125,'2026-05-06 18:37:18'),
(32,125,17,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:03:12','2026-05-06 18:37:23',125,125,'2026-05-06 18:37:23'),
(33,8,37,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:04:53','2026-05-06 18:37:21',125,125,'2026-05-06 18:37:21'),
(34,151,53,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:09:29','2026-05-06 18:37:34',125,125,'2026-05-06 18:37:34'),
(35,171,85,'preventive',NULL,650.00,NULL,'2026-05-06',NULL,'pending','Rembert V. Tortogo','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 18:10:14','2026-05-06 18:37:31',125,125,'2026-05-06 18:37:31'),
(36,157,60,'preventive',NULL,650.00,NULL,'2026-05-06',NULL,'pending','Callito A.  Belmar','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 18:12:17','2026-05-06 18:37:45',125,125,'2026-05-06 18:37:45'),
(37,1,60,'emergency',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:12:51','2026-05-06 18:37:48',125,125,'2026-05-06 18:37:48'),
(38,140,38,'emergency',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:18:41','2026-05-06 18:37:50',125,125,'2026-05-06 18:37:50'),
(39,174,89,'preventive',NULL,850.00,NULL,'2026-05-06','2026-05-06','completed','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:28:34','2026-05-06 18:37:53',125,125,'2026-05-06 18:37:53'),
(40,51,84,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:32:08','2026-05-06 18:37:55',125,125,'2026-05-06 18:37:55'),
(41,148,50,'preventive',NULL,88.00,NULL,'2026-05-06',NULL,'pending','Joel H. Llouido','bb (x1)',NULL,'2026-05-06 18:32:34','2026-05-06 18:37:58',125,125,'2026-05-06 18:37:58'),
(42,151,53,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:34:56','2026-05-06 18:38:01',125,125,'2026-05-06 18:38:01'),
(43,185,103,'preventive',NULL,650.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 18:35:12','2026-05-06 18:38:08',125,125,'2026-05-06 18:38:08'),
(44,132,28,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:35:38','2026-05-06 18:38:12',125,125,'2026-05-06 18:38:12'),
(45,51,37,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:41:14','2026-05-06 18:41:14',125,125,NULL),
(46,180,97,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:41:58','2026-05-06 18:41:58',125,125,NULL),
(47,131,25,'emergency',NULL,650.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 18:52:10','2026-05-06 18:52:10',125,125,NULL),
(48,141,40,'emergency',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 18:52:36','2026-05-06 18:52:36',125,125,NULL),
(49,186,35,'emergency',NULL,650.00,NULL,'2026-05-06',NULL,'pending','Joel H. Llouido','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-06 19:02:07','2026-05-06 19:02:07',125,125,NULL),
(50,17,35,'preventive',NULL,850.00,NULL,'2026-05-06',NULL,'pending','Abran A. Oracion','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-06 19:08:56','2026-05-06 19:08:56',125,125,NULL),
(51,129,23,'emergency',NULL,650.00,NULL,'2026-05-07',NULL,'pending','Callito A.  Belmar','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-07 02:12:53','2026-05-07 02:12:53',125,125,NULL),
(52,12,62,'emergency',NULL,650.00,NULL,'2026-05-08',NULL,'pending','Joel H. Llouido','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-08 10:26:31','2026-05-08 10:26:31',125,125,NULL),
(53,121,10,'emergency',NULL,650.00,NULL,'2026-05-08',NULL,'pending','Abran A. Oracion','ATF / CVT Transmission Fluid (1L) (x1)',NULL,'2026-05-08 12:06:19','2026-05-08 12:06:19',125,125,NULL),
(54,2,62,'emergency',NULL,850.00,NULL,'2026-05-09',NULL,'pending','Callito A.  Belmar','Air Filter (Toyota Vios/Hiace) (x1)',NULL,'2026-05-09 14:27:23','2026-05-09 14:27:23',125,125,NULL);
/*!40000 ALTER TABLE `maintenance` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `maintenance_parts`
--

DROP TABLE IF EXISTS `maintenance_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `maintenance_id` int(11) NOT NULL,
  `part_id` bigint(20) unsigned DEFAULT NULL,
  `part_name` varchar(191) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_parts_maintenance_id_foreign` (`maintenance_id`),
  CONSTRAINT `maintenance_parts_maintenance_id_foreign` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_parts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `maintenance_parts` WRITE;
/*!40000 ALTER TABLE `maintenance_parts` DISABLE KEYS */;
INSERT INTO `maintenance_parts` VALUES
(5,1,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-13 03:22:38','2026-04-13 03:22:38'),
(11,5,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(12,5,4,'Brake Shoes Rear',1,1650.00,1650.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(13,5,15,'Clutch Disc (Genuine)',1,3200.00,3200.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(14,5,18,'Shock Absorber Front (Pair)',1,5500.00,5500.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(15,5,17,'Wheel Hub / Bearing Front',1,3500.00,3500.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(16,5,NULL,'wdqfniwfninn3ifnini3nfi34nfi3nnffffffffffffffffffffffffffffffffffffffffffffffffffffffff',1,330.00,330.00,'2026-04-14 04:27:07','2026-04-14 04:27:07'),
(17,6,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-25 14:46:37','2026-04-25 14:46:37'),
(18,7,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-25 14:51:06','2026-04-25 14:51:06'),
(19,8,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-26 09:21:55','2026-04-26 09:21:55'),
(20,9,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-04-27 00:57:34','2026-04-27 00:57:34'),
(24,12,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-30 09:40:31','2026-04-30 09:40:31'),
(25,12,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-04-30 09:40:31','2026-04-30 09:40:31'),
(26,12,NULL,'labordaybukas',1,15000.00,15000.00,'2026-04-30 09:40:31','2026-04-30 09:40:31'),
(27,13,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-04-30 10:18:13','2026-04-30 10:18:13'),
(28,13,NULL,'222',1,650000.00,650000.00,'2026-04-30 10:18:13','2026-04-30 10:18:13'),
(29,14,14,'ATF / CVT Transmission Fluid (1L)',3,650.00,1950.00,'2026-04-30 21:46:36','2026-04-30 21:46:36'),
(30,11,2,'Air Filter (Toyota Vios/Hiace)',2,850.00,1700.00,'2026-05-01 16:05:16','2026-05-01 16:05:16'),
(31,11,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-01 16:05:16','2026-05-01 16:05:16'),
(32,11,NULL,'kupal',1,3001.00,3001.00,'2026-05-01 16:05:16','2026-05-01 16:05:16'),
(33,16,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-01 17:05:50','2026-05-01 17:05:50'),
(34,16,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-01 17:05:50','2026-05-01 17:05:50'),
(35,16,NULL,'eqwwwwwwwwwwwwwwwwwwwwwwwwwwww',1,0.00,0.00,'2026-05-01 17:05:50','2026-05-01 17:05:50'),
(36,17,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-02 20:23:21','2026-05-02 20:23:21'),
(38,19,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-02 20:32:00','2026-05-02 20:32:00'),
(39,21,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-02 22:20:53','2026-05-02 22:20:53'),
(40,24,13,'Brake Fluid (500ml)',1,350.00,350.00,'2026-05-04 08:56:17','2026-05-04 08:56:17'),
(41,24,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-04 08:56:17','2026-05-04 08:56:17'),
(42,25,29,'h',1,87.00,87.00,'2026-05-04 20:08:08','2026-05-04 20:08:08'),
(43,27,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 16:58:43','2026-05-06 16:58:43'),
(44,28,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 17:35:00','2026-05-06 17:35:00'),
(45,29,27,'brake hose',1,500.00,500.00,'2026-05-06 17:38:39','2026-05-06 17:38:39'),
(46,30,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 17:48:25','2026-05-06 17:48:25'),
(47,31,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 17:55:33','2026-05-06 17:55:33'),
(48,32,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:03:12','2026-05-06 18:03:12'),
(49,33,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:04:53','2026-05-06 18:04:53'),
(50,34,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:09:29','2026-05-06 18:09:29'),
(51,35,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 18:10:14','2026-05-06 18:10:14'),
(52,36,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 18:12:17','2026-05-06 18:12:17'),
(53,37,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:12:51','2026-05-06 18:12:51'),
(54,38,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:18:41','2026-05-06 18:18:41'),
(55,39,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:28:34','2026-05-06 18:28:34'),
(56,40,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:32:08','2026-05-06 18:32:08'),
(57,41,23,'bb',1,88.00,88.00,'2026-05-06 18:32:34','2026-05-06 18:32:34'),
(58,42,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:34:56','2026-05-06 18:34:56'),
(59,43,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 18:35:12','2026-05-06 18:35:12'),
(60,44,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:35:38','2026-05-06 18:35:38'),
(61,45,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:41:14','2026-05-06 18:41:14'),
(62,46,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:41:58','2026-05-06 18:41:58'),
(63,47,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 18:52:10','2026-05-06 18:52:10'),
(64,48,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 18:52:36','2026-05-06 18:52:36'),
(65,49,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-06 19:02:07','2026-05-06 19:02:07'),
(66,50,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-06 19:08:56','2026-05-06 19:08:56'),
(67,51,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-07 02:12:53','2026-05-07 02:12:53'),
(68,52,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-08 10:26:31','2026-05-08 10:26:31'),
(69,53,14,'ATF / CVT Transmission Fluid (1L)',1,650.00,650.00,'2026-05-08 12:06:19','2026-05-08 12:06:19'),
(70,54,2,'Air Filter (Toyota Vios/Hiace)',1,850.00,850.00,'2026-05-09 14:27:23','2026-05-09 14:27:23');
/*!40000 ALTER TABLE `maintenance_parts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `maintenance_records`
--

DROP TABLE IF EXISTS `maintenance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned NOT NULL,
  `type` enum('preventive','corrective','breakdown','inspection') NOT NULL DEFAULT 'preventive',
  `description` text NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mechanic_name` varchar(100) DEFAULT NULL,
  `maintenance_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_records`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `maintenance_records` WRITE;
/*!40000 ALTER TABLE `maintenance_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_records` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `managed_expenses`
--

DROP TABLE IF EXISTS `managed_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `managed_expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `billing_month` tinyint(4) NOT NULL,
  `billing_year` int(11) NOT NULL,
  `date_paid` date NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `managed_expenses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `managed_expenses` WRITE;
/*!40000 ALTER TABLE `managed_expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `managed_expenses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
(5,'2024_01_01_000001_create_eurotaxi_tables',2),
(9,'2024_01_01_000002_fix_boundaries_table',6),
(10,'2024_01_01_000003_add_status_to_franchise_cases',6),
(11,'2026_03_16_101713_create_units_table',6),
(12,'2026_03_16_102827_create_expenses_table',6),
(13,'2026_03_16_103033_create_drivers_table',6),
(14,'2026_03_16_103348_create_maintenance_table',6),
(15,'2026_03_16_130521_create_gps_tracking_table',6),
(16,'2026_03_18_183437_create_sessions_table',6),
(17,'2026_03_18_201840_add_github_columns_to_users_table',6),
(18,'2026_03_19_203137_create_system_alerts_table',7),
(19,'2026_03_20_190859_add_details_to_boundaries_table',8),
(20,'2026_03_23_141825_change_role_to_string_on_users_table',9),
(21,'2026_03_23_153918_add_tracking_columns_to_tables',10),
(22,'2026_03_24_133100_fix_franchise_cases_status',11),
(23,'2026_03_24_000000_create_franchise_case_units_table',12),
(24,'2026_03_24_135200_fix_infinityfree_database',12),
(25,'2026_03_25_000001_add_gps_imei_to_units_table',13),
(26,'2026_03_25_083116_rename_gps_imei_to_gps_link_on_units_table',14),
(27,'2026_03_25_124128_create_staff_table',15),
(28,'2026_03_25_160000_add_user_profile_fields',16),
(29,'2026_03_25_174500_add_profile_image_to_users_table',17),
(30,'2026_03_27_224504_add_soft_deletes_to_multiple_tables',18),
(31,'2026_03_27_225253_add_soft_deletes_to_users_table',19),
(32,'2026_03_28_123336_create_coding_records_table',20),
(33,'2026_03_28_185943_make_email_nullable_on_users_table',21),
(34,'2026_03_28_211209_add_parts_list_to_maintenance_if_missing',22),
(35,'2026_03_28_211230_add_audit_columns_to_maintenance_if_missing',23),
(36,'2026_04_05_000000_add_suffix_and_phone_to_users_table',24),
(37,'2026_04_05_175540_add_otp_fields_to_users_table',24),
(38,'2026_04_08_110126_create_user_recognized_devices_table',25),
(39,'2026_04_08_112144_add_constraints_to_user_verified_browsers_table',26),
(40,'2026_04_08_214628_create_boundary_rules_table',27),
(41,'2026_04_09_124319_relax_unit_number_constraint_on_units_table',28),
(43,'2026_04_09_225925_add_names_to_drivers_table',29),
(44,'2026_04_10_000000_remove_unit_number_from_units_table',30),
(45,'2026_04_10_113434_add_vacant_to_unit_status_enum',31),
(46,'2026_04_10_142229_drop_fuel_status_from_units_table',32),
(47,'2026_04_10_144928_add_imei_to_units_table',33),
(48,'2026_04_12_141601_add_updated_at_to_gps_tracking_table',34),
(49,'2026_04_12_161111_add_daily_stats_to_gps_tracking_table',35),
(50,'2026_04_12_235100_align_boundary_status_enum',36),
(51,'2026_04_13_021301_add_is_extra_driver_to_boundaries_table',37),
(52,'2026_04_13_074218_create_spare_parts_table',38),
(53,'2026_04_13_074257_create_maintenance_parts_table',39),
(54,'2026_04_13_080927_add_driver_id_to_maintenance_table',40),
(55,'2026_04_13_103731_alter_units_table_swap_color_for_motor_chassis',41),
(56,'2026_04_13_120015_sync_motor_chassis_data_v2',42),
(57,'2026_04_13_203105_create_coding_violations_table',43),
(58,'2026_04_13_220807_add_swapping_fields_to_units_and_boundaries',44),
(59,'2026_04_13_225815_add_shift_deadline_to_units',45),
(60,'2026_04_14_092300_add_vehicle_damaged_to_boundaries',46),
(61,'2026_04_14_121315_fix_foreign_key_on_driver_behavior_table',47),
(62,'2026_04_14_170000_add_accident_fields_to_driver_behavior',48),
(63,'2026_04_14_170001_add_incentive_tracking_to_boundaries',48),
(64,'2026_04_14_173000_add_incentive_released_at_to_driver_behavior',49),
(65,'2026_04_14_183000_add_is_absent_to_boundaries',50),
(67,'2026_04_14_203036_create_driver_behaviors_table',52),
(68,'2026_04_14_205413_create_driver_debts_table',52),
(69,'2026_04_14_205414_add_debt_payment_to_boundaries',52),
(70,'2026_04_14_201942_create_incident_advanced_tables',53),
(71,'2026_04_14_222616_create_incident_deep_records_tables',54),
(72,'2026_04_14_230001_create_god_level_accident_tables',55),
(73,'2026_04_14_230002_add_comprehensive_debt_fields',55),
(74,'2026_04_20_090545_add_is_charged_to_driver_to_incident_parts_estimates',56),
(75,'2026_04_22_114057_add_soft_deletes_to_driver_behavior_table',57),
(76,'2026_04_22_000000_relax_driver_behavior_columns',58),
(77,'2026_04_24_112000_repair_incident_involved_parties_column',58),
(78,'2026_04_24_161321_add_is_pinned_missing_to_units_table',58),
(79,'2026_04_24_165332_add_surveillance_to_unit_status_enum',59),
(80,'2026_04_25_174753_add_stock_and_supplier_to_spare_parts_table',60),
(81,'2026_04_25_180304_create_suppliers_table',61),
(82,'2026_04_25_220445_add_soft_deletes_to_spare_parts',62),
(83,'2026_04_25_224513_make_description_nullable_in_maintenance_table',63),
(84,'2026_04_26_072100_rename_surveillance_to_at_risk_in_units_status',64),
(85,'2026_04_26_192131_add_inventory_link_to_expenses',65),
(86,'2026_04_27_000001_add_super_admin_columns_to_users_table',66),
(87,'2026_04_27_000002_create_login_audit_table',66),
(88,'2026_04_27_104336_create_activity_logs_table',67),
(89,'2026_04_28_033707_add_must_change_password_to_users_table',68),
(90,'2026_04_27_001600_add_vendor_and_payment_to_expenses',69),
(91,'2026_04_27_002000_convert_expenses_category_to_string',69),
(92,'2026_04_27_011935_add_in_shop_to_maintenance_status_enum',69),
(93,'2026_04_28_162443_create_incident_classifications_table',69),
(94,'2026_04_28_170947_add_soft_deletes_to_incident_classifications_table',70),
(96,'2026_04_29_112509_add_sub_classification_to_driver_behavior_table',71),
(97,'2026_04_30_125848_create_roles_table',72),
(98,'2026_04_28_030000_add_source_to_salaries_table',73),
(99,'2026_04_28_051833_change_action_to_string_on_login_audit_table',73),
(100,'2026_04_29_141949_add_contact_address_deleted_at_to_staff_table',73),
(101,'2026_04_29_145016_add_emergency_phone_to_staff_table',73),
(102,'2026_04_30_134000_add_disable_fields_to_users_table',73),
(103,'2026_04_30_140603_create_system_settings_table',74),
(104,'2026_04_30_165010_add_category_to_spare_parts_table',75),
(105,'2026_04_30_181629_add_approval_columns_to_expenses_table',76),
(106,'2026_05_02_064451_add_banned_to_driver_status_enum_in_drivers_table',77),
(107,'2026_05_02_104800_update_behavior_mode_enum_on_classifications',78),
(108,'2026_05_02_105100_add_missing_to_units_status_enum',79),
(109,'2026_05_02_113305_add_show_not_at_fault_to_incident_classifications',80),
(110,'2026_05_03_004909_add_franchise_case_id_to_expenses_table',81),
(111,'2026_05_03_011958_add_last_service_odo_gps_to_units_table',82),
(112,'2026_05_04_000000_add_days_missing_to_driver_behavior_table',83),
(113,'2026_05_04_023000_create_admin_activity_logs_table',84),
(114,'2026_05_04_141234_add_email_change_fields_to_users_table',85),
(115,'2026_05_04_164247_fix_jesus_duero_massive_debt',86),
(116,'2026_05_05_120000_add_manual_stolen_report_fields_to_driver_behavior',87),
(117,'2026_05_06_170000_add_fcm_token_to_users_table',88),
(118,'2026_05_06_214500_create_rescue_requests_table',89),
(119,'2026_05_06_215000_add_device_info_to_verified_browsers',89),
(120,'2026_05_08_113430_create_sessions_table',90),
(121,'2026_05_07_225207_make_license_fields_nullable_in_drivers_table',91),
(122,'2026_05_08_204852_create_support_tickets_table',99),
(123,'2026_05_08_205528_add_document_photos_to_drivers_table',100),
(124,'2026_05_09_140227_create_support_messages_table',101),
(125,'2026_05_10_205500_make_driver_user_id_nullable_and_prevent_cascade',102),
(128,'2026_05_11_112120_add_license_id_to_units_table',103),
(129,'2026_05_11_113013_rename_license_id_to_unit_driver_id_in_units_table',103),
(130,'2026_05_14_222800_create_announcements_table',104),
(131,'2026_05_14_225300_add_tracking_meta_to_gps_tracking_table',105),
(132,'2026_05_18_171226_update_announcements_table',106),
(133,'2026_05_20_152855_add_title_to_announcements_table',107),
(134,'2026_05_26_152000_add_hidden_columns_to_support_messages',108),
(135,'2026_05_26_172224_add_unique_constraint_to_boundaries',109),
(136,'2026_05_26_200000_create_push_subscriptions_table',110),
(137,'2026_05_26_200001_create_chat_messages_table',110),
(138,'2026_06_02_175044_add_suspension_fields_to_drivers_table',111),
(139,'2026_06_02_182619_change_notes_to_text_in_login_audit_table',112),
(140,'2026_06_03_200500_make_unit_id_nullable_in_driver_behavior_table',113),
(141,'2026_06_05_164800_add_gps_provider_to_units_table',114),
(142,'2026_06_05_205523_add_engine_status_to_units_table',114),
(143,'2026_06_08_162106_add_suspension_fields_to_drivers_table',115),
(144,'2026_06_08_165740_add_suspension_columns_to_drivers_table',115),
(145,'2026_06_20_210000_add_accident_fields_to_rescue_requests',116);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(18,'App\\Models\\User',125,'mobile_app','524ba7218bf38cf022952b73f779ec8372d8113b5f43cc33caccda4409139081','[\"*\"]','2026-05-03 12:19:42',NULL,'2026-05-03 11:44:15','2026-05-03 12:19:42'),
(19,'App\\Models\\User',125,'mobile_app','bb0bce7778b9ecd46b32cb926b55221ad0a8f9a7d998c2fb318496c81f809c7b','[\"*\"]','2026-05-03 11:57:33',NULL,'2026-05-03 11:56:53','2026-05-03 11:57:33'),
(20,'App\\Models\\User',125,'mobile_app','c4f15f90bd41f306f419290dba3577928ec29d2989613ae56b813ba99772f25e','[\"*\"]','2026-05-03 12:48:30',NULL,'2026-05-03 12:26:43','2026-05-03 12:48:30'),
(21,'App\\Models\\User',125,'mobile_app','0219d140c7a58c8d4e7dbd9dfe9ab0bd70b0e5358c15be7cbfd1a5af1bf05a85','[\"*\"]','2026-05-04 03:27:35',NULL,'2026-05-03 13:04:28','2026-05-04 03:27:35'),
(22,'App\\Models\\User',125,'mobile_app','f167880fe6a2587b44d55ef2adaa4ec50a0d9d012e3ace5134260b1494230cd6','[\"*\"]','2026-05-03 13:15:33',NULL,'2026-05-03 13:06:47','2026-05-03 13:15:33'),
(23,'App\\Models\\User',125,'mobile_app','129369ece89f10865e5aeadb8db1b9fb2109ecfa0d3207c2b2bb8d328e981e05','[\"*\"]','2026-05-03 13:43:47',NULL,'2026-05-03 13:16:31','2026-05-03 13:43:47'),
(24,'App\\Models\\User',125,'mobile_app','3658f6e85d3e6608351dba2e8f5408015d9c41cd51bcd9926a6ee5daa3f0e829','[\"*\"]','2026-05-03 17:19:38',NULL,'2026-05-03 13:51:15','2026-05-03 17:19:38'),
(25,'App\\Models\\User',125,'mobile_app','23ca7df21303a15d6436dea11b327631e4f0a82da0b9ac65078543a0f618c4ce','[\"*\"]','2026-05-03 17:23:26',NULL,'2026-05-03 14:56:15','2026-05-03 17:23:26'),
(26,'App\\Models\\User',125,'mobile_app','43424cc79b275275186a4c3fda80acf7ea7cc3db6c0c53f3039cd9b3a4ea03d1','[\"*\"]','2026-05-06 14:52:14',NULL,'2026-05-03 17:21:35','2026-05-06 14:52:14'),
(28,'App\\Models\\User',125,'mobile_app','3f281174d0759e1adb576ffd119ed8ece4d2b12b11109570b412c2a104f2b2c3','[\"*\"]',NULL,NULL,'2026-05-03 17:37:12','2026-05-03 17:37:12'),
(31,'App\\Models\\User',125,'mobile_app','6dd0edf45cc0254e3822545db08fc40b122cf4d37dc765c3b97673c40f4594fd','[\"*\"]','2026-05-04 03:10:35',NULL,'2026-05-04 02:49:47','2026-05-04 03:10:35'),
(32,'App\\Models\\User',125,'mobile_app','2c9fc9581c13e9144119808ce12ace74e67bfa36cedcc21456b69ccaceabced3','[\"*\"]','2026-05-04 03:34:44',NULL,'2026-05-04 03:12:49','2026-05-04 03:34:44'),
(33,'App\\Models\\User',125,'mobile_app','a6be0cdbed5c4b0c53ce3c40829107b88d6153714234528b1c64f38b7e211e7d','[\"*\"]','2026-05-04 17:55:49',NULL,'2026-05-04 03:18:06','2026-05-04 17:55:49'),
(34,'App\\Models\\User',125,'mobile_app','7164d2576b72ee452cba0a643fd8f896283ad8a3626d4676c47a5a8ae21ec54b','[\"*\"]','2026-05-04 03:24:19',NULL,'2026-05-04 03:21:05','2026-05-04 03:24:19'),
(35,'App\\Models\\User',125,'mobile_app','2a876e8dc7c5db2df60e59d8179c698ea5e65e20eaf5e91462d7365a49d7fc52','[\"*\"]','2026-05-04 03:35:45',NULL,'2026-05-04 03:34:08','2026-05-04 03:35:45'),
(37,'App\\Models\\User',125,'mobile_app','ce363106b5d1c670e41d1d3bfae5ef75bd8c279dbbe6bcd095e997580f925709','[\"*\"]','2026-05-04 12:26:34',NULL,'2026-05-04 07:12:11','2026-05-04 12:26:34'),
(38,'App\\Models\\User',125,'mobile_app','23562f177086cc678b653c2cf43c0e439dcf38c56e1b0b014a3f702b101fbd85','[\"*\"]','2026-05-04 12:31:17',NULL,'2026-05-04 12:27:24','2026-05-04 12:31:17'),
(39,'App\\Models\\User',125,'mobile_app','dae288955f7bb12d486d2494dd8600bf41a84f002a19437d19d0b402d8eeb504','[\"*\"]','2026-05-04 17:01:39',NULL,'2026-05-04 12:31:55','2026-05-04 17:01:39'),
(40,'App\\Models\\User',125,'mobile_app','3c2f77a7bc9a02a8c49d39202e4f99a1a47777de1379796f0d7103c6a48b7dbe','[\"*\"]','2026-05-04 19:37:59',NULL,'2026-05-04 17:02:50','2026-05-04 19:37:59'),
(41,'App\\Models\\User',125,'mobile_app','c0d60cb5ab3010079a0ac51c9097fff6fbbc2eff09220b76f92bb65ccb722662','[\"*\"]','2026-05-04 20:36:07',NULL,'2026-05-04 19:41:05','2026-05-04 20:36:07'),
(42,'App\\Models\\User',125,'mobile_app','ef4d3476538ee1fc110bab793c77a61bc427762134e33fb88ec5eab14ef9f4f6','[\"*\"]','2026-05-04 20:51:02',NULL,'2026-05-04 20:00:14','2026-05-04 20:51:02'),
(43,'App\\Models\\User',125,'mobile_app','aefaff458862356e13bd780c954b1ba0f66962130da09e896bee60907e885552','[\"*\"]',NULL,NULL,'2026-05-04 20:56:30','2026-05-04 20:56:30'),
(44,'App\\Models\\User',125,'mobile_app','9ecd33888d337ad91db758cebd00b91b1d7e0eeaba3cf9f3111e0548ba0fe2e9','[\"*\"]',NULL,NULL,'2026-05-04 20:57:26','2026-05-04 20:57:26'),
(45,'App\\Models\\User',125,'mobile_app','c306e733d1ff3ee6fdf158b3e79cb4fedaa5422bb65f2d25647606b51d1a2b6f','[\"*\"]','2026-05-05 07:58:03',NULL,'2026-05-04 21:01:10','2026-05-05 07:58:03'),
(46,'App\\Models\\User',125,'mobile_app','6715479b12ab7e70fb526f877edb74c5f0aa2f46b69d875137a729386bf0b872','[\"*\"]','2026-05-05 01:58:59',NULL,'2026-05-04 21:29:21','2026-05-05 01:58:59'),
(47,'App\\Models\\User',125,'mobile_app','d866edbc89c2aad44bba73d9889d517bc13f05aacc46157a9ad966c04e9b7a67','[\"*\"]','2026-05-05 03:57:26',NULL,'2026-05-05 02:09:50','2026-05-05 03:57:26'),
(48,'App\\Models\\User',125,'mobile_app','088b4a94ae70578af2bb20873400cb71f6785ac3513acdd29f958cdf754d118e','[\"*\"]','2026-05-05 04:04:31',NULL,'2026-05-05 04:04:13','2026-05-05 04:04:31'),
(49,'App\\Models\\User',125,'mobile_app','3acdafee223c4a003c753770be8d8c030e26f5a801f05131e4039f2e40ee13ba','[\"*\"]','2026-05-05 04:13:16',NULL,'2026-05-05 04:05:14','2026-05-05 04:13:16'),
(50,'App\\Models\\User',125,'mobile_app','fb727a2140f2b389bcd2552443b1df8a59a96bc8fe34b820aa3828fd3132b524','[\"*\"]','2026-05-05 04:14:41',NULL,'2026-05-05 04:14:28','2026-05-05 04:14:41'),
(51,'App\\Models\\User',125,'mobile_app','1281fc22907bf819693a09bfe1d820cf5bdbf7cdd2d4a1eddb052a78f4bbb283','[\"*\"]','2026-05-05 04:27:39',NULL,'2026-05-05 04:16:00','2026-05-05 04:27:39'),
(52,'App\\Models\\User',125,'mobile_app','99e76d4f9dcfe9258e2bbb783c1d2994b8cdf50e5d9b54e2b9b91d61d8f3d426','[\"*\"]','2026-05-05 04:33:36',NULL,'2026-05-05 04:33:15','2026-05-05 04:33:36'),
(53,'App\\Models\\User',125,'mobile_app','ee47e6bc9d1152bdd79b04f38b2cdd8816ffaaea346ee9de220f9d37e2a278ad','[\"*\"]','2026-05-05 04:36:26',NULL,'2026-05-05 04:36:11','2026-05-05 04:36:26'),
(54,'App\\Models\\User',125,'mobile_app','1ec9c155fec0bfffb7618faaa9ef5ba6bf656ff136c15148224b6fdb642cc838','[\"*\"]','2026-05-05 05:48:13',NULL,'2026-05-05 04:39:49','2026-05-05 05:48:13'),
(55,'App\\Models\\User',125,'mobile_app','9893e45487c4430aa7cd36929d24d10dd2b142741fc8c149f592d403a700392e','[\"*\"]','2026-05-05 17:46:38',NULL,'2026-05-05 06:17:43','2026-05-05 17:46:38'),
(56,'App\\Models\\User',125,'mobile_app','dd1996519759b6a2c39b9279e95120c86a6d1f87892ba45b62b3213759847808','[\"*\"]','2026-05-05 09:03:42',NULL,'2026-05-05 08:37:31','2026-05-05 09:03:42'),
(57,'App\\Models\\User',125,'EuroTaxi Mobile','6df9cd18f76b661b3f7a647b49b0543549a87dd993d72f6f129fddc07c57c070','[\"*\"]','2026-05-06 15:27:27',NULL,'2026-05-06 14:47:19','2026-05-06 15:27:27'),
(58,'App\\Models\\User',125,'EuroTaxi Mobile (robertgarcia.owner@gmail.com)','5246c58bebc64d366f29c6b16e8f418f4c91b667ad6b49c8b4191cb1aa4ad205','[\"*\"]','2026-05-31 00:37:21',NULL,'2026-05-06 15:48:37','2026-05-31 00:37:21'),
(59,'App\\Models\\User',125,'mobile_app','07dbfca2c26080a8196f3a5d5deb9a5ea963824d294950b3969d9382455c0bf6','[\"*\"]','2026-05-06 18:11:51',NULL,'2026-05-06 18:11:51','2026-05-06 18:11:51'),
(64,'App\\Models\\User',125,'mobile_app','846241965422d51fe94a4e184f0dea9fba2571b89266aaf09396201c97eaa86f','[\"*\"]','2026-05-08 14:19:50',NULL,'2026-05-08 13:59:22','2026-05-08 14:19:50'),
(87,'App\\Models\\User',125,'mobile_app','29993e466d85b7d737199d5dba5bfc4e772f68e7427c52ec8b246b3aeddfc198','[\"*\"]','2026-05-09 21:34:58',NULL,'2026-05-09 20:14:46','2026-05-09 21:34:58'),
(88,'App\\Models\\User',154,'ITEL itel S665L','317f12178dde87b6682bff819f3299a5eaf63e46eaa5ecb32724a9456c7ce1a5','[\"*\"]',NULL,NULL,'2026-05-09 21:40:06','2026-05-09 21:40:06'),
(89,'App\\Models\\User',154,'ITEL itel S665L','14c603b56c2599feae0e7d01a29b00c387a789bc252596171a0c861416693a81','[\"*\"]',NULL,NULL,'2026-05-09 21:45:26','2026-05-09 21:45:26'),
(90,'App\\Models\\User',154,'ITEL itel S665L','e35039515271d1331012fb6239d5fcbcdb10c3fcf475e5a435ef30fbcae3c6a7','[\"*\"]','2026-05-09 22:03:53',NULL,'2026-05-09 21:49:09','2026-05-09 22:03:53'),
(96,'App\\Models\\User',158,'ITEL itel S665L','f59c528382b615f610015e48149991a6145e72f93a89b55f0728e1306e7c4431','[\"*\"]','2026-05-10 21:20:58',NULL,'2026-05-10 21:19:30','2026-05-10 21:20:58'),
(143,'App\\Models\\User',161,'ITEL itel S665L','1d00f33abcfc37c22122098f1b3c0d242d291ade838c56d8e6e9c2cecff5b2d0','[\"*\"]','2026-06-11 18:55:53',NULL,'2026-06-11 16:47:10','2026-06-11 18:55:53'),
(149,'App\\Models\\User',162,'ITEL itel S665L','a906c0402a943aeebde8c06e86ed8bbf9b4fbee3de6ff167dd305649915a3b6c','[\"*\"]','2026-06-20 16:44:17',NULL,'2026-06-20 16:02:44','2026-06-20 16:44:17'),
(153,'App\\Models\\User',164,'HONOR HEY3-W00','d3288d544cac9003bd8aaf5b08b56cb0d2aeb774b2f10b25c3fd5fa38801f597','[\"*\"]','2026-06-24 20:11:46',NULL,'2026-06-24 20:10:35','2026-06-24 20:11:46'),
(154,'App\\Models\\User',163,'ITEL itel S665L','3b78610bde43f5e7e57028356acfab69f46b1282e575db244838880d4bf70472','[\"*\"]','2026-06-25 18:10:58',NULL,'2026-06-25 17:56:01','2026-06-25 18:10:58');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `push_subscriptions`
--

DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `public_key` text DEFAULT NULL,
  `auth_token` text DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_endpoint` (`user_id`,`endpoint`),
  CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_subscriptions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `push_subscriptions` WRITE;
/*!40000 ALTER TABLE `push_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `rescue_requests`
--

DROP TABLE IF EXISTS `rescue_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rescue_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'rescue',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('pending','responding','resolved','cancelled') NOT NULL DEFAULT 'pending',
  `acknowledged_by` bigint(20) unsigned DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `photo_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rescue_requests_driver_id_index` (`driver_id`),
  KEY `rescue_requests_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rescue_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `rescue_requests` WRITE;
/*!40000 ALTER TABLE `rescue_requests` DISABLE KEYS */;
INSERT INTO `rescue_requests` VALUES
(1,11,7,'accident',NULL,NULL,'responding',125,'2026-06-21 12:18:48','Damage Level: moderate\nDescription: ggg','uploads/accident_photos/ykDvMARyIeYNwQO9szALFtvJYPJ8ZC6rTjkwzfoI.jpg','2026-06-21 12:08:02','2026-06-21 22:26:08','2026-06-21 22:26:08'),
(2,11,7,'accident',14.5616290,120.9919560,'responding',125,'2026-06-21 12:38:15','SOS Emergency Alert triggered by driver',NULL,'2026-06-21 12:37:57','2026-06-21 22:26:16','2026-06-21 22:26:16'),
(3,11,7,'accident',14.6475710,121.0599470,'responding',125,'2026-06-21 13:28:40','SOS Emergency Alert triggered by driver',NULL,'2026-06-21 13:28:11','2026-06-21 22:26:03','2026-06-21 22:26:03'),
(4,11,7,'accident',14.6526190,121.0793420,'responding',125,'2026-06-21 21:15:41','SOS Emergency Alert triggered by driver\n\n--- ACCIDENT REPORT ---\nDamage Level: minor\nDescription: test','uploads/accident_photos/FowP95RolZ8IxRhHA3YfZBOMYZlffo2vrRM5dNDO.jpg','2026-06-21 21:15:15','2026-06-21 22:25:57','2026-06-21 22:25:57'),
(5,11,7,'accident',14.6526190,121.0793420,'responding',125,'2026-06-21 22:17:42','SOS Emergency Alert triggered by driver\n\n--- ACCIDENT REPORT ---\nDamage Level: minor\nDescription: g','uploads/accident_photos/1782051512_accident.jpg','2026-06-21 22:17:18','2026-06-21 22:25:44','2026-06-21 22:25:44'),
(6,11,7,'accident',14.6526190,121.0793420,'responding',125,'2026-06-21 22:24:51','SOS Emergency Alert triggered by driver',NULL,'2026-06-21 22:24:13','2026-06-21 22:25:33','2026-06-21 22:25:33'),
(7,11,7,'accident',14.6526190,121.0793420,'responding',125,'2026-06-21 22:35:34','SOS Emergency Alert triggered by driver',NULL,'2026-06-21 22:35:25','2026-06-21 22:35:34',NULL),
(8,11,7,'accident',14.6526190,121.0793420,'responding',125,'2026-06-21 23:08:37','SOS Emergency Alert triggered by driver',NULL,'2026-06-21 23:08:25','2026-06-21 23:08:37',NULL),
(9,11,7,'accident',14.6112590,121.0283640,'responding',125,'2026-06-22 10:51:11','Emergency Alert triggered by driver',NULL,'2026-06-22 10:50:56','2026-06-22 10:51:11',NULL),
(10,11,7,'accident',14.6487710,121.0485690,'responding',125,'2026-06-23 19:17:54','Emergency Alert triggered by driver\n\n--- ACCIDENT REPORT ---\nDamage Level: Not specified\nDescription: tt','uploads/accident_photos/1782213498_accident.jpg','2026-06-23 19:17:41','2026-06-23 19:18:18',NULL),
(11,11,7,'accident',14.6512960,121.0784530,'responding',125,'2026-06-24 19:52:11','Emergency Alert triggered by driver\n\n--- ACCIDENT REPORT ---\nDamage Level: Not specified\nDescription: test','uploads/accident_photos/1782301921_accident.jpg','2026-06-24 19:51:37','2026-06-24 19:52:11',NULL);
/*!40000 ALTER TABLE `rescue_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `label` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'manager','Manager',NULL,'2026-04-30 04:59:21','2026-04-30 04:59:21',NULL),
(2,'dispatcher','Dispatcher',NULL,'2026-04-30 04:59:21','2026-05-03 13:30:35',NULL),
(3,'secretary','Secretary',NULL,'2026-04-30 04:59:21','2026-04-30 04:59:21',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `salaries`
--

DROP TABLE IF EXISTS `salaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `salaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `source` varchar(10) DEFAULT 'user',
  `employee_type` varchar(191) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `overtime_pay` decimal(10,2) DEFAULT 0.00,
  `holiday_pay` decimal(10,2) DEFAULT 0.00,
  `night_differential` decimal(10,2) DEFAULT 0.00,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `total_salary` decimal(10,2) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `pay_date` date NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_employee` (`employee_id`),
  KEY `idx_month_year` (`month`,`year`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salaries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `salaries` WRITE;
/*!40000 ALTER TABLE `salaries` DISABLE KEYS */;
INSERT INTO `salaries` VALUES
(2,6,'staff','Mechanic',999.98,0.00,0.00,0.00,0.00,999.98,8,2026,'2026-04-27',18,'2026-04-26 18:02:30','2026-05-04 12:15:54');
/*!40000 ALTER TABLE `salaries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('9IEDnqqDnMnXRi4Eb2v3SLnH80iLUr6fTOA56NND',125,'136.158.67.252','Mozilla/5.0 (Linux; Android 13; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Mobile Safari/537.36','YTo2OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo2NzoiaHR0cHM6Ly9ldXJvdGF4aXN5c3RlbS5zaXRlL3dlYi1ub3RpZmljYXRpb25zL25hdGl2ZS1wb2xsP3VzZXJfaWQ9MSI7fXM6NjoiX3Rva2VuIjtzOjQwOiJMdHdsTTlxakRRZEVVdmRpOGxHZ3BaVmdwcm1zb0dUaVA5cHpOOHR1IjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMjU7czoxNjoibGFzdF9tb25pdG9yX3J1biI7czoxOToiMjAyNi0wNS0wOCAxMzo0NToyMCI7fQ==',1778221178),
('A2ykmXKUihGlgj5sY2D0FdGrcUQ8KZL3KyjnrvKH',125,'136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUzRKWVFwVTlnWnd1TlZ2VEZucnExT2JDY21aVlUxMGNHMzltM3VWUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vZXVyb3RheGlzeXN0ZW0uc2l0ZS9zdGFmZiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyNTtzOjE2OiJsYXN0X21vbml0b3JfcnVuIjtzOjE5OiIyMDI2LTA1LTA4IDE3OjQ5OjU4Ijt9',1778233801),
('bUEK9cBGjskcesAMpJzq0T0fhQ9ql6La25JTwF0Z',NULL,'136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cHM6Ly9ldXJvdGF4aXN5c3RlbS5zaXRlL2xvZ2luIjt9czo2OiJfdG9rZW4iO3M6NDA6Im5aeG1WdU1SZ2lvUUJNMllkdUtETHhDNEFSbE9PU25XZlhwTThuMHAiO30=',1778230160),
('g55YGNnMel0UrSO4UyKD06Ky1GOXLA9ktpyvnL3O',NULL,'157.55.39.201','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjQ1OiJodHRwczovL3d3dy5ldXJvdGF4aXN5c3RlbS5zaXRlL2xpdmUtdHJhY2tpbmciO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cHM6Ly93d3cuZXVyb3RheGlzeXN0ZW0uc2l0ZS9sb2dpbiI7fXM6NjoiX3Rva2VuIjtzOjQwOiJkR1FWc1I2TG1NdE1ESDQ2aVExTlkzdzhhN3U3WGhXNVU5aWc1ZkVmIjt9',1778229725),
('mM8VvhTgctX5ErTzPodF0rKoXrXPGG4MBgzlWOX3',NULL,'207.46.13.107','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36','YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cHM6Ly9ldXJvdGF4aXN5c3RlbS5zaXRlL2xvZ2luIjt9czo2OiJfdG9rZW4iO3M6NDA6IkljV0VncXZVV1NoS2MwUEVkbG9CWW5lSENFZDExRUhPUmQ1b1JPd2ciO30=',1778224829),
('NgA8sfnw0QvAGzS0Yw5A9FBmkb9kp2fDYELEKMzk',125,'136.158.67.252','Mozilla/5.0 (Linux; Android 13; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Mobile Safari/537.36','YTo2OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo2NzoiaHR0cHM6Ly9ldXJvdGF4aXN5c3RlbS5zaXRlL3dlYi1ub3RpZmljYXRpb25zL25hdGl2ZS1wb2xsP3VzZXJfaWQ9MSI7fXM6NjoiX3Rva2VuIjtzOjQwOiJTR2w5b294Z3dHN2I1TE40TFNsSmhZdFNoaTRud0N6NWJzdEdMck50IjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMjU7czoxNjoibGFzdF9tb25pdG9yX3J1biI7czoxOToiMjAyNi0wNS0wOCAxNzozMzoyOSI7fQ==',1778234387),
('PTQIPzguZFHlAGHM1FOH4fPDjIRcsUDE3Vj9Zyx3',NULL,'66.249.70.71','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.7727.137 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjI3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cHM6Ly9ldXJvdGF4aXN5c3RlbS5zaXRlL2xvZ2luIjt9czo2OiJfdG9rZW4iO3M6NDA6InZtOWhNSldVZURYNldieFhsMFRoM2g3bXpzTGRNemdCVUJRbDh2YTUiO30=',1778225814),
('qHVoiadnXMbn3Df6eN221Y2GmmvqtjgkM6FoNLlI',NULL,'66.249.64.229','Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.7727.137 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','YTo0OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjMxOiJodHRwczovL3d3dy5ldXJvdGF4aXN5c3RlbS5zaXRlIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vd3d3LmV1cm90YXhpc3lzdGVtLnNpdGUvbG9naW4iO31zOjY6Il90b2tlbiI7czo0MDoiUDA2TThiWnJ1eDVYOTF1MnZCMEVUR2Q0a2Y1MDQzc3JETFEyRW82UCI7fQ==',1778217663),
('vragsCkgdJUQdu6woqs3oDPjxTw2KPB3RG5aB44K',125,'2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTVJyd3FXaThEOTB1ZDdZU1VCalcySjJlR3RzY3hNWXhuV2Y0Ymd3NiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI1O3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUvc3RhZmYiO31zOjE2OiJsYXN0X21vbml0b3JfcnVuIjtzOjE5OiIyMDI2LTA1LTA4IDE0OjM3OjQ5Ijt9',1778229338),
('Xt7k50c8sHba8Gj856jfjQTIkseXpmIpsx6TSUq2',125,'136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTkZYMGk2Ylc5eXVjN1I4Um0wdWs3MmVOaXZHQllYVFhUUFF2RzMyNyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI1O3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwczovL2V1cm90YXhpc3lzdGVtLnNpdGUvZGVjaXNpb24tbWFuYWdlbWVudCI7fXM6MTY6Imxhc3RfbW9uaXRvcl9ydW4iO3M6MTk6IjIwMjYtMDUtMDggMTY6MDY6MzIiO30=',1778231425);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `spare_parts`
--

DROP TABLE IF EXISTS `spare_parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `spare_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `category` varchar(191) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `supplier` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spare_parts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `spare_parts` WRITE;
/*!40000 ALTER TABLE `spare_parts` DISABLE KEYS */;
INSERT INTO `spare_parts` VALUES
(1,'Toyota Genuine Oil Filter',NULL,450.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(2,'Air Filter (Toyota Vios/Hiace)',NULL,850.00,157,'A. BONIFACIO AUTO','2026-04-12 23:50:06','2026-05-27 21:24:19',NULL),
(3,'Brake Pads Front (Genuine)',NULL,2450.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(4,'Brake Shoes Rear',NULL,1650.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(5,'Iridium Spark Plugs (Set of 4)',NULL,1800.00,0,NULL,'2026-04-12 23:50:06','2026-05-04 20:09:01',NULL),
(6,'Fully Synthetic Engine Oil (4L)',NULL,2200.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(7,'Toyota Super Long Life Coolant (1L)',NULL,450.00,0,NULL,'2026-04-12 23:50:06','2026-04-30 08:18:36',NULL),
(8,'Toyota Genuine Wiper Blade (Set)',NULL,750.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(9,'Fuel Filter (Genuine)',NULL,2800.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(10,'Cabin/AC Filter',NULL,450.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(11,'Serpentine Belt',NULL,950.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(12,'Motolite Gold Battery (NS40)',NULL,4800.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(13,'Brake Fluid (500ml)',NULL,350.00,1,'AMONLATHE WORKS','2026-04-12 23:50:06','2026-04-26 14:28:40',NULL),
(14,'ATF / CVT Transmission Fluid (1L)',NULL,650.00,14,NULL,'2026-04-12 23:50:06','2026-05-05 05:34:25',NULL),
(15,'Clutch Disc (Genuine)',NULL,3200.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(16,'Release Bearing (Genuine)',NULL,1200.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(17,'Wheel Hub / Bearing Front',NULL,3500.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(18,'Shock Absorber Front (Pair)',NULL,5500.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(19,'Shock Absorber Rear (Pair)',NULL,4200.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(20,'Tie Rod End (Pair)',NULL,1800.00,0,NULL,'2026-04-12 23:50:06','2026-04-12 23:50:06',NULL),
(21,'Brake Pads',NULL,1500.00,5,NULL,'2026-04-22 02:30:50','2026-05-05 05:49:59',NULL),
(23,'bb',NULL,88.00,23,'A. BONIFACIO AUTO','2026-04-22 11:39:23','2026-05-04 20:05:53','2026-05-04 20:05:53'),
(26,'jj',NULL,9.00,9,'ABC AUTO PARTS','2026-04-26 15:33:49','2026-05-04 20:05:57','2026-05-04 20:05:57'),
(27,'brake hose',NULL,500.00,10,'A. BONIFACIO AUTO','2026-04-30 09:34:50','2026-04-30 09:34:50',NULL),
(28,'Dggdgdgd',NULL,222.00,0,'213','2026-05-04 08:15:03','2026-05-04 20:06:09','2026-05-04 20:06:09'),
(29,'h',NULL,87.00,0,NULL,'2026-05-04 20:07:47','2026-05-04 20:08:25','2026-05-04 20:08:25'),
(30,'Brake Disk',NULL,1500.00,0,NULL,'2026-05-29 14:23:13','2026-05-29 14:23:13',NULL);
/*!40000 ALTER TABLE `spare_parts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `role` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `emergency_phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES
(1,'Callito A.  Belmar','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:10:20','2026-04-12 23:10:20',NULL),
(2,'Nilo E. Dugu','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:11:05','2026-04-12 23:11:05',NULL),
(3,'Joel H. Llouido','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:12:00','2026-04-12 23:12:00',NULL),
(4,'Marlon P. Nalaluan','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:12:53','2026-04-12 23:13:20',NULL),
(5,'Willard A. Nialega','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:13:53','2026-04-12 23:13:53',NULL),
(6,'Abran A. Oracion','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:14:24','2026-04-12 23:14:24',NULL),
(7,'Rembert V. Tortogo','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:15:05','2026-04-12 23:15:05',NULL),
(8,'Mark Ben  O. Arguelles','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:15:43','2026-04-12 23:15:43',NULL),
(9,'Manuel M. Lusanta','Mechanic',NULL,NULL,NULL,NULL,'active','2026-04-12 23:16:04','2026-04-12 23:16:04',NULL),
(10,'Pagay A. Salvador','Guard',NULL,NULL,NULL,NULL,'active','2026-04-12 23:16:38','2026-04-12 23:16:38',NULL),
(11,'Romy M. Tomas','Guard Dispatcher',NULL,NULL,NULL,NULL,'active','2026-04-12 23:17:33','2026-04-12 23:17:33',NULL),
(13,'Ria Jane Calubayan Perocho','Mechanic','09814444055','Ria Jane Calubayan Perocho','09814444055','0049 Liwag st','active','2026-04-30 23:45:29','2026-05-08 16:05:50','2026-05-08 16:05:50'),
(14,'Ria Jane Calubayan Perocho56_','Guard','09814444055a89','Ria Jane Calubayan Perocho','09814444055','0049 Liwag st','active','2026-05-02 22:37:34','2026-05-08 16:05:41','2026-05-08 16:05:41'),
(15,'A B C D E F G','Mechanic','ABC123456789012345',NULL,NULL,NULL,'active','2026-05-03 21:56:49','2026-05-03 23:02:05','2026-05-03 23:02:05');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES
(1,'APOLLO ZONE',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(2,'MEGA GRANDIS',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(3,'LUCKY TWO',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(4,'SHARON HUNG',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(5,'A. BONIFACIO AUTO',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-05-02 19:14:54'),
(6,'NELSON PROVIDO',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(7,'SAUYO MACHINE SHOP',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(8,'Q.C. TOYORAMA MOTOR CORP.',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(9,'WYL MOTORS',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(10,'ABC AUTO PARTS',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(11,'AMONLATHE WORKS',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(12,'VISCO MOTOR SUPPLY',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(13,'T.A. FRESCO CORP.',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(14,'TRACKSPEED',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(15,'BEST COLT',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(16,'WEST ELM TREE',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(17,'AUTOPHIL ZONE SALES',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(18,'REGASCO GASOLINE',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-04-25 10:04:13'),
(19,'we',NULL,NULL,NULL,NULL,'2026-04-25 10:04:13','2026-05-04 08:06:46'),
(20,'qweqwewq',NULL,NULL,NULL,NULL,'2026-05-04 08:06:57','2026-05-04 08:06:57'),
(21,'qwe','qweqwe','09123231231',NULL,'2026-05-04 08:09:54','2026-05-04 08:07:42','2026-05-04 08:09:54'),
(22,'213','edqewa','09213123123','213',NULL,'2026-05-04 08:09:07','2026-05-04 08:09:07'),
(23,'nnn','jjjj','09909090909',NULL,NULL,'2026-05-04 19:28:13','2026-05-04 19:28:13');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `support_messages`
--

DROP TABLE IF EXISTS `support_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `driver_id` int(11) NOT NULL,
  `sender_type` enum('driver','admin') NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `hidden_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `hidden_by_driver` tinyint(1) NOT NULL DEFAULT 0,
  `attachment` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_messages_driver_id_foreign` (`driver_id`),
  CONSTRAINT `support_messages_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_messages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `support_messages` WRITE;
/*!40000 ALTER TABLE `support_messages` DISABLE KEYS */;
INSERT INTO `support_messages` VALUES
(212,163,'driver',163,'',1,0,0,'uploads/support_attachments/1782212134_1000050255.jpg','2026-06-23 18:55:34','2026-06-23 18:55:38'),
(213,163,'driver',163,'',1,0,0,'uploads/support_attachments/1782213410_photo_1782213406863.jpeg','2026-06-23 19:16:50','2026-06-23 19:17:28');
/*!40000 ALTER TABLE `support_messages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subject` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `category` varchar(191) NOT NULL DEFAULT 'general',
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `admin_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
INSERT INTO `support_tickets` VALUES
(1,153,'testin','Ya yayaya','general','pending',NULL,NULL,'2026-05-09 14:02:55','2026-05-09 14:02:55');
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `system_alerts`
--

DROP TABLE IF EXISTS `system_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_alerts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(191) NOT NULL DEFAULT 'info',
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_alerts_is_resolved_created_at_index` (`is_resolved`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_alerts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `system_alerts` WRITE;
/*!40000 ALTER TABLE `system_alerts` DISABLE KEYS */;
INSERT INTO `system_alerts` VALUES
(1,'Today\'s Coding Units','There are 16 units restricted today (Monday).','coding_notice',0,NULL,'2026-04-13 13:28:53','2026-04-13 13:28:53'),
(2,'Today\'s Coding Units','There are 18 units restricted today (Tuesday).','coding_notice',0,NULL,'2026-04-14 05:24:18','2026-04-14 05:24:18'),
(3,'Today\'s Coding Units','There are 18 units restricted today (Tuesday).','coding_notice',0,NULL,'2026-04-21 02:56:43','2026-04-21 02:56:43'),
(4,'Today\'s Coding Units','There are 19 units restricted today (Wednesday).','coding_notice',0,NULL,'2026-04-22 01:33:07','2026-04-22 01:33:07'),
(5,'Accident Reported: AAK 9196','Driver Sismundo Candelaria reported an accident. Fault: NO. Charge: ₱1,500.00','danger',1,NULL,'2026-04-22 02:34:12','2026-05-09 19:54:44'),
(6,'Today\'s Coding Units','There are 22 units restricted today (Friday).','coding_notice',0,NULL,'2026-04-24 01:42:55','2026-04-24 01:42:55'),
(7,'🚨 Missing Unit: AAK 9196','Unit AAK 9196 has not remitted a boundary for 8 day(s). The last driver on record is Unknown Driver.','missing_unit',1,NULL,'2026-04-24 11:20:21','2026-05-01 08:55:47'),
(8,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-04-26 16:25:26','2026-04-26 16:25:26'),
(9,'Accident Reported: AAK 4591','Driver Jesus Duero reported an accident. Fault: YES. Charge: ₱1,200.00','danger',1,NULL,'2026-04-27 01:00:03','2026-05-02 11:05:26'),
(10,'Accident Reported: DAZ 9769','Driver Roel Peñol reported an accident. Fault: YES. Charge: ₱0.00','danger',0,NULL,'2026-04-27 06:41:27','2026-04-27 06:41:27'),
(11,'Accident Reported: NEF 4940','Driver July Sunico reported an accident. Fault: YES. Charge: ₱0.00','danger',1,NULL,'2026-04-27 07:24:05','2026-05-03 22:20:33'),
(12,'🚨 Missing Unit: NEF 4940','Unit NEF 4940 has not remitted a boundary for 5 day(s). The last driver on record is July Sunico.','missing_unit',1,NULL,'2026-04-27 07:45:57','2026-05-01 09:29:22'),
(13,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-04-27 18:22:43','2026-04-27 18:22:43'),
(14,'🚨 Missing Unit: CAV 9662','Unit CAV 9662 has not remitted a boundary for 6 day(s). The last driver on record is Rodel Gundran.','missing_unit',1,NULL,'2026-04-28 07:06:14','2026-05-04 00:12:16'),
(15,'Today\'s Unit Coding','There are 19 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-04-29 03:43:31','2026-04-29 03:43:31'),
(16,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-04-30 01:31:09','2026-04-30 01:31:09'),
(17,'Accident Reported: NEF 4940','Driver sunibertson sunico reported an accident. Fault: YES. Charge: ₱17,000.00','danger',1,NULL,'2026-04-30 10:01:09','2026-05-03 22:20:33'),
(18,'Accident Reported: AAK 9196','Driver Arwin Azarcon reported an accident. Fault: YES. Charge: ₱1,700.00','danger',1,NULL,'2026-04-30 22:00:26','2026-05-09 19:54:44'),
(19,'Today\'s Unit Coding','There are 22 units on coding today (Friday).','coding_notice',0,NULL,'2026-05-01 00:11:01','2026-05-01 00:11:01'),
(20,'Accident Reported: CAX 5430','Driver Elmer Andrade reported an accident. Fault: YES. Charge: ₱850.00','danger',0,NULL,'2026-05-01 11:39:15','2026-05-01 11:39:15'),
(21,'🚫 AUTO-BAN: dian Santiago Dian','Driver dian Santiago Dian has been automatically banned due to a Contracting / passenger complaint violation on unit AAK 9196.','danger',0,NULL,'2026-05-02 07:03:28','2026-05-02 07:03:28'),
(22,'🚨 STOLEN/TAKEN VEHICLE: AAK 4591','CRITICAL: Unit AAK 4591 has been reported as TAKEN/STOLEN by Jesus Duero. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-02 10:52:58','2026-05-02 11:05:26'),
(23,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by July Sunico. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-02 10:57:23','2026-05-03 22:20:33'),
(24,'Accident Reported: ACH 5774','Driver Arwin Azarcon reported an accident. Fault: YES. Charge: ₱0.00','danger',0,NULL,'2026-05-02 12:26:11','2026-05-02 12:26:11'),
(25,'Accident Reported: AAQ 1743','Driver Norlando Fernandez reported an accident. Fault: YES. Charge: ₱0.00','danger',1,NULL,'2026-05-02 12:50:41','2026-05-04 17:41:27'),
(26,'Accident Reported: NAC 4989','Driver Gerse Matallano reported an accident. Fault: YES. Charge: ₱650.00','danger',0,NULL,'2026-05-02 12:52:14','2026-05-02 12:52:14'),
(27,'Accident Reported: CBM 1979','Driver Felimon Evangilista reported an accident. Fault: YES. Charge: ₱21,110.00','danger',1,NULL,'2026-05-02 13:07:54','2026-05-11 20:15:09'),
(28,'🚫 AUTO-BAN: Lito Ayag','Driver Lito Ayag has been automatically banned due to a Contracting / passenger complaint violation on unit NAD 8102.','danger',0,NULL,'2026-05-02 13:59:53','2026-05-02 13:59:53'),
(29,'🚫 AUTO-BAN: Sanjali Untal','Driver Sanjali Untal has been automatically banned due to a Contracting / passenger complaint violation on unit ABG 7479.','danger',0,NULL,'2026-05-02 22:23:40','2026-05-02 22:23:40'),
(30,'🚨 Missing Unit: NEF 4940','Unit NEF 4940 has not remitted a boundary for 1 day(s). The last driver on record is Roberto Sunico.','missing_unit',1,NULL,'2026-05-03 11:42:56','2026-05-03 22:20:33'),
(31,'🚨 Missing Unit: NFH 3664','Unit NFH 3664 has not remitted a boundary for 2 day(s). The last driver on record is Edward Nieva.','missing_unit',1,NULL,'2026-05-03 13:46:40','2026-05-05 05:53:05'),
(32,'🚨 Missing Unit: ASA 6135','Unit ASA 6135 has not remitted a boundary for 2 day(s). The last driver on record is Jose Camillotes.','missing_unit',1,NULL,'2026-05-03 20:10:21','2026-05-04 19:03:55'),
(33,'🚨 Missing Unit: CBM 1979','Unit CBM 1979 has not remitted a boundary for 9 day(s). The last driver on record is Norlando Fernandez.','missing_unit',1,NULL,'2026-05-03 20:10:21','2026-05-11 20:15:09'),
(34,'🚨 Missing Unit: NEF 4940','Unit NEF 4940 has not remitted a boundary for 1 day(s). The last driver on record is Roberto Sunico.','missing_unit',1,NULL,'2026-05-03 22:37:31','2026-05-04 00:29:34'),
(35,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-05-04 00:11:11','2026-05-04 00:11:11'),
(36,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Roberto Sunico. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 00:22:04','2026-05-04 00:29:34'),
(37,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Elmer Andrade. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 00:39:48','2026-05-04 00:40:09'),
(38,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Arwin Azarcon. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 00:41:06','2026-05-04 01:04:27'),
(39,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Oliver Ariola. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 03:38:44','2026-05-05 01:13:01'),
(40,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Marlito Baguioro. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 03:39:33','2026-05-05 01:13:01'),
(41,'🚨 STOLEN/TAKEN VEHICLE: AAK 9196','CRITICAL: Unit AAK 9196 has been reported as TAKEN/STOLEN by yanzkie ramos. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 16:02:05','2026-05-09 19:54:44'),
(42,'🚨 STOLEN/TAKEN VEHICLE: AAQ 1743','CRITICAL: Unit AAQ 1743 has been reported as TAKEN/STOLEN by Felix Ausa. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-04 16:44:22','2026-05-04 17:41:27'),
(43,'🚨 Missing Unit: ASA 6135','Unit ASA 6135 has not remitted a boundary for 7 day(s). The last driver on record is Jose Camillotes.','missing_unit',1,NULL,'2026-05-04 19:44:05','2026-05-09 19:39:58'),
(44,'🚨 Missing Unit: NCW 5011','Unit NCW 5011 has not remitted a boundary for 38 day(s). The last driver on record is Unknown Driver.','missing_unit',0,NULL,'2026-05-04 20:31:12','2026-06-11 18:32:28'),
(45,'Today\'s Unit Coding','There are 19 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-05-05 00:18:36','2026-05-05 00:18:36'),
(46,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Francisco Baja. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-05 00:51:58','2026-05-05 01:13:01'),
(47,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by Henry Belen. Vehicle is now in LOCKDOWN (Missing status).','danger',1,NULL,'2026-05-05 01:12:11','2026-05-05 01:13:01'),
(48,'Accident Reported: AAQ 1743','Driver Oliver Ariola reported an accident. Fault: YES. Charge: ₱850.00','danger',0,NULL,'2026-05-05 09:15:17','2026-05-05 09:15:17'),
(49,'Today\'s Unit Coding','There are 22 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-05-06 11:30:15','2026-05-06 11:30:15'),
(50,'🚨 Missing Unit: NFH 3664','Unit NFH 3664 has not remitted a boundary for 4 day(s). The last driver on record is Edward Nieva.','missing_unit',1,NULL,'2026-05-06 11:30:15','2026-05-09 19:40:03'),
(51,'Expired Franchise','Case NCR 2014-01299 (EUROTAXI INC.) expired on Oct 31, 2024','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(52,'Expired Franchise','Case NCR 2014-01286 (EUROTAXI INC.) expired on Oct 31, 2025','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(53,'Expired Franchise','Case NCR 2014-01303 (EUROTAXI INC.) expired on Oct 31, 2024','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(54,'Expired Franchise','Case NCR 2014-01304 (EUROTAXI INC.) expired on Feb 27, 2026','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(55,'Expired Franchise','Case NCR 2014-01149 (EUROTAXI INC.) expired on Oct 31, 2024','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(56,'Expired Franchise','Case NCR 2014-01233 (EUROTAXI INC.) expired on Oct 31, 2025','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(57,'Expired Franchise','Case NCR 2014-01234 (EUROTAXI INC.) expired on Jul 11, 2025','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(58,'Expired Franchise','Case NCR 2014-01232 (EUROTAXI INC.) expired on Oct 18, 2025','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(59,'Expired Franchise','Case NCR 2014-01150 (EUROTAXI INC.) expired on Dec 08, 2025','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(60,'Franchise Renewal','Case NCR 2014-01152 (EUROTAXI INC.) expires on Oct 31, 2026','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(61,'Franchise Renewal','Case NCR 2014-01153 (EUROTAXI INC.) expires on Oct 31, 2026','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(62,'Expired Franchise','Case NCR 2018-4-2015-02370 (RQG TRANSPORT) expired on May 06, 2026','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(63,'Expired Franchise','Case NCR 2018-4-2015-02364 (RQG TRANSPORT) expired on Oct 31, 2023','case_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(64,'Maintenance Today','Unit CAX 5430 schedule: Preventive','maintenance_today',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(65,'Maintenance Today','Unit NGP 1877 schedule: Preventive','maintenance_today',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(66,'⚠ OUT OF STOCK: Toyota Genuine Oil Filter','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(67,'⚠ OUT OF STOCK: Brake Pads Front (Genuine)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(68,'⚠ OUT OF STOCK: Brake Shoes Rear','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(69,'⚠ OUT OF STOCK: Iridium Spark Plugs (Set of 4)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(70,'⚠ OUT OF STOCK: Fully Synthetic Engine Oil (4L)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(71,'⚠ OUT OF STOCK: Toyota Super Long Life Coolant (1L)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(72,'⚠ OUT OF STOCK: Toyota Genuine Wiper Blade (Set)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(73,'⚠ OUT OF STOCK: Fuel Filter (Genuine)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(74,'⚠ OUT OF STOCK: Cabin/AC Filter','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(75,'⚠ OUT OF STOCK: Serpentine Belt','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(76,'⚠ OUT OF STOCK: Motolite Gold Battery (NS40)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(77,'⚠ Low Stock: Brake Fluid (500ml)','Stock: 1 items. Source: AMONLATHE WORKS','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(78,'⚠ OUT OF STOCK: Clutch Disc (Genuine)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(79,'⚠ OUT OF STOCK: Release Bearing (Genuine)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(80,'⚠ OUT OF STOCK: Wheel Hub / Bearing Front','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(81,'⚠ OUT OF STOCK: Shock Absorber Front (Pair)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(82,'⚠ OUT OF STOCK: Shock Absorber Rear (Pair)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(83,'⚠ OUT OF STOCK: Tie Rod End (Pair)','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(84,'⚠ Low Stock: Brake Pads','Stock: 5 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(85,'⚠ OUT OF STOCK: Dggdgdgd','Stock: 0 items. Source: 213','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(86,'⚠ OUT OF STOCK: h','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(87,'🚫 Expired License: Oliver Ariola','Oliver Ariola\'s license expired on Feb 14, 2025. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(88,'🚫 Expired License: sunibertson sunico','sunibertson sunico\'s license expired on Apr 30, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(89,'🚫 Expired License: dian Santiago Dian','dian Santiago Dian\'s license expired on Apr 28, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(90,'🚫 Expired License: yanzkie ramos','yanzkie ramos\'s license expired on Apr 08, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(91,'🚫 Expired License: Ria Perocho','Ria Perocho\'s license expired on Apr 30, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(92,'🚫 Expired License: Mary Anne Santos','Mary Anne Santos\'s license expired on May 01, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(93,'🚫 Expired License: RI RO','RI RO\'s license expired on May 03, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(94,'🚫 Expired License: Ria Jane Perocho','Ria Jane Perocho\'s license expired on Apr 02, 2026. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(95,'🚫 Expired License: Rebbel Mortrl','Rebbel Mortrl\'s license expired on May 04, 2011. Please update the record.','license_expiry',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(96,'🔧 Service Due: ALA 3699','Unit ALA 3699 has reached 20,746 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(97,'🔧 Service Due: NCJ 7661','Unit NCJ 7661 has reached 86,504 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(98,'🔧 Service Due: AAK 4591','Unit AAK 4591 has reached 93,845 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',1,NULL,'2026-05-06 18:49:13','2026-05-11 19:59:51'),
(99,'🔧 Service Due: ABL 1667','Unit ABL 1667 has reached 43,537 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(100,'🔧 Service Due: ABP 7643','Unit ABP 7643 has reached 72,935 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(101,'🔧 Service Due: ADY 2598','Unit ADY 2598 has reached 41,014 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(102,'🔧 Service Due: ASA 6135','Unit ASA 6135 has reached 75,140 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',1,NULL,'2026-05-06 18:49:13','2026-05-09 19:39:58'),
(103,'🔧 Service Due: DCQ 1551','Unit DCQ 1551 has reached 107,681 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(104,'🔧 Service Due: NBW 7071','Unit NBW 7071 has reached 60,182 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(105,'🔧 Service Due: NCW 5011','Unit NCW 5011 has reached 50,754 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(106,'🔧 Service Due: NDC 7363','Unit NDC 7363 has reached 95,096 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(107,'🔧 Service Due: NAN 1349','Unit NAN 1349 has reached 116,332 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',1,NULL,'2026-05-06 18:49:13','2026-05-06 19:02:08'),
(108,'🔧 Service Due: ABP 2705','Unit ABP 2705 has reached 79,846 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-06 18:49:13','2026-05-06 18:49:13'),
(109,'Maintenance Today','Unit DAU 9027 schedule: Emergency','maintenance_today',0,NULL,'2026-05-06 18:52:10','2026-05-06 18:52:10'),
(110,'Maintenance Today','Unit EAE 1919 schedule: Emergency','maintenance_today',0,NULL,'2026-05-06 18:52:36','2026-05-06 18:52:36'),
(111,'🚨 STOLEN/TAKEN VEHICLE: NEF 4940','CRITICAL: Unit NEF 4940 has been reported as TAKEN/STOLEN by dian Santiago Dian. Vehicle is now in LOCKDOWN (Missing status).','danger',0,NULL,'2026-05-06 18:54:05','2026-05-06 18:54:05'),
(112,'Maintenance Today','Unit NAN 1349 schedule: Emergency','maintenance_today',0,NULL,'2026-05-06 19:02:08','2026-05-06 19:02:08'),
(113,'Maintenance Today','Unit EAD 7438 schedule: Preventive','maintenance_today',0,NULL,'2026-05-06 19:08:56','2026-05-06 19:08:56'),
(114,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-05-07 00:00:51','2026-05-07 00:00:51'),
(115,'Franchise Renewal','Case 201401287 (EUROTAXI INC.) expires on Jul 16, 2026','case_expiry',0,NULL,'2026-05-07 01:51:19','2026-05-07 01:51:19'),
(116,'Maintenance Today','Unit DAT 1367 schedule: Emergency','maintenance_today',0,NULL,'2026-05-07 02:12:53','2026-05-07 02:12:53'),
(117,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:27:58','2026-05-07 02:27:58'),
(118,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:28:48','2026-05-07 02:28:48'),
(119,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:28:59','2026-05-07 02:28:59'),
(120,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:29:31','2026-05-07 02:29:31'),
(121,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:29:46','2026-05-07 02:29:46'),
(122,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:30:41','2026-05-07 02:30:41'),
(123,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:33:58','2026-05-07 02:33:58'),
(124,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:34:09','2026-05-07 02:34:09'),
(125,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:34:21','2026-05-07 02:34:21'),
(126,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:34:30','2026-05-07 02:34:30'),
(127,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:34:39','2026-05-07 02:34:39'),
(128,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:35:09','2026-05-07 02:35:09'),
(129,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:35:36','2026-05-07 02:35:36'),
(130,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:35:45','2026-05-07 02:35:45'),
(131,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:36:11','2026-05-07 02:36:11'),
(132,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:36:27','2026-05-07 02:36:27'),
(133,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:36:53','2026-05-07 02:36:53'),
(134,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:36:58','2026-05-07 02:36:58'),
(135,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:37:13','2026-05-07 02:37:13'),
(136,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:40:36','2026-05-07 02:40:36'),
(137,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:40:44','2026-05-07 02:40:44'),
(138,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:40:55','2026-05-07 02:40:55'),
(139,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:41:38','2026-05-07 02:41:38'),
(140,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:41:42','2026-05-07 02:41:42'),
(141,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:43:59','2026-05-07 02:43:59'),
(142,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:44:48','2026-05-07 02:44:48'),
(143,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:44:52','2026-05-07 02:44:52'),
(144,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:48:35','2026-05-07 02:48:35'),
(145,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:48:47','2026-05-07 02:48:47'),
(146,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:48:52','2026-05-07 02:48:52'),
(147,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:49:07','2026-05-07 02:49:07'),
(148,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:49:20','2026-05-07 02:49:20'),
(149,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:49:28','2026-05-07 02:49:28'),
(150,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:49:49','2026-05-07 02:49:49'),
(151,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:51:25','2026-05-07 02:51:25'),
(152,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:51:28','2026-05-07 02:51:28'),
(153,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:51:34','2026-05-07 02:51:34'),
(154,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:51:49','2026-05-07 02:51:49'),
(155,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:54:57','2026-05-07 02:54:57'),
(156,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:55:11','2026-05-07 02:55:11'),
(157,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:55:49','2026-05-07 02:55:49'),
(158,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:55:53','2026-05-07 02:55:53'),
(159,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:57:42','2026-05-07 02:57:42'),
(160,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:57:51','2026-05-07 02:57:51'),
(161,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:58:35','2026-05-07 02:58:35'),
(162,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:58:39','2026-05-07 02:58:39'),
(163,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 02:59:20','2026-05-07 02:59:20'),
(164,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:01:02','2026-05-07 03:01:02'),
(165,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:01:16','2026-05-07 03:01:16'),
(166,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:01:26','2026-05-07 03:01:26'),
(167,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:01:39','2026-05-07 03:01:39'),
(168,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:03:45','2026-05-07 03:03:45'),
(169,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:03:49','2026-05-07 03:03:49'),
(170,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:06:42','2026-05-07 03:06:42'),
(171,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:06:49','2026-05-07 03:06:49'),
(172,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:07:00','2026-05-07 03:07:00'),
(173,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:08:35','2026-05-07 03:08:35'),
(174,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:08:40','2026-05-07 03:08:40'),
(175,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 03:08:50','2026-05-07 03:08:50'),
(176,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 22:52:58','2026-05-07 22:52:58'),
(177,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-07 22:53:13','2026-05-07 22:53:13'),
(178,'Today\'s Unit Coding','There are 23 units on coding today (Friday).','coding_notice',0,NULL,'2026-05-08 10:10:47','2026-05-08 10:10:47'),
(179,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 10:22:38','2026-05-08 10:22:38'),
(180,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 10:23:09','2026-05-08 10:23:09'),
(181,'Maintenance Today','Unit ABF 2705 schedule: Emergency','maintenance_today',0,NULL,'2026-05-08 10:26:31','2026-05-08 10:26:31'),
(182,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:03:38','2026-05-08 12:03:38'),
(183,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:03:45','2026-05-08 12:03:45'),
(184,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:03:57','2026-05-08 12:03:57'),
(185,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:04:03','2026-05-08 12:04:03'),
(186,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:05:32','2026-05-08 12:05:32'),
(187,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:05:48','2026-05-08 12:05:48'),
(188,'Maintenance Today','Unit CAT 6073 schedule: Emergency','maintenance_today',0,NULL,'2026-05-08 12:06:20','2026-05-08 12:06:20'),
(189,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:12:14','2026-05-08 12:12:14'),
(190,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:16:31','2026-05-08 12:16:31'),
(191,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:16:44','2026-05-08 12:16:44'),
(192,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:22:24','2026-05-08 12:22:24'),
(193,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:23:27','2026-05-08 12:23:27'),
(194,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:23:39','2026-05-08 12:23:39'),
(195,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:29:22','2026-05-08 12:29:22'),
(196,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 12:30:13','2026-05-08 12:30:13'),
(197,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 14:27:30','2026-05-08 14:27:30'),
(198,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 14:27:59','2026-05-08 14:27:59'),
(199,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 14:28:03','2026-05-08 14:28:03'),
(200,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 15:11:04','2026-05-08 15:11:04'),
(201,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 15:11:13','2026-05-08 15:11:13'),
(202,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 15:11:16','2026-05-08 15:11:16'),
(203,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 16:00:16','2026-05-08 16:00:16'),
(204,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 17:16:11','2026-05-08 17:16:11'),
(205,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 17:33:01','2026-05-08 17:33:01'),
(206,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 18:01:11','2026-05-08 18:01:11'),
(207,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-08 18:07:55','2026-05-08 18:07:55'),
(208,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 12:51:56','2026-05-09 12:51:56'),
(209,'Maintenance Today','Unit AAQ 1743 schedule: Emergency','maintenance_today',0,NULL,'2026-05-09 14:27:45','2026-05-09 14:27:45'),
(210,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 14:49:25','2026-05-09 14:49:25'),
(211,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 14:49:47','2026-05-09 14:49:47'),
(212,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 14:49:50','2026-05-09 14:49:50'),
(213,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 14:49:54','2026-05-09 14:49:54'),
(214,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 15:39:33','2026-05-09 15:39:33'),
(215,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 15:40:05','2026-05-09 15:40:05'),
(216,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 15:46:04','2026-05-09 15:46:04'),
(217,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 15:50:49','2026-05-09 15:50:49'),
(218,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 16:08:04','2026-05-09 16:08:04'),
(219,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 16:16:19','2026-05-09 16:16:19'),
(220,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:03:56','2026-05-09 18:03:56'),
(221,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:03:59','2026-05-09 18:03:59'),
(222,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:05:08','2026-05-09 18:05:08'),
(223,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:05:17','2026-05-09 18:05:17'),
(224,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:05:18','2026-05-09 18:05:18'),
(225,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:17:15','2026-05-09 18:17:15'),
(226,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:17:20','2026-05-09 18:17:20'),
(227,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:17:25','2026-05-09 18:17:25'),
(228,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:17:28','2026-05-09 18:17:28'),
(229,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 18:17:49','2026-05-09 18:17:49'),
(230,'🔧 Service Due: ASA 6135','Unit ASA 6135 has reached 75,761 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-09 19:40:11','2026-05-09 19:40:11'),
(231,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 19:53:03','2026-05-09 19:53:03'),
(232,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 19:53:08','2026-05-09 19:53:08'),
(233,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:08:26','2026-05-09 20:08:26'),
(234,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:08:28','2026-05-09 20:08:28'),
(235,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:08:30','2026-05-09 20:08:30'),
(236,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:08:32','2026-05-09 20:08:32'),
(237,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:14:59','2026-05-09 20:14:59'),
(238,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 20:31:14','2026-05-09 20:31:14'),
(239,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-09 22:18:42','2026-05-09 22:18:42'),
(240,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-10 21:30:09','2026-05-10 21:30:09'),
(241,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-05-11 07:19:38','2026-05-11 07:19:38'),
(242,'🚨 Missing Unit: DCQ 1551','Unit DCQ 1551 has not remitted a boundary for 1 day(s). The last driver on record is Unknown Driver.','missing_unit',1,NULL,'2026-05-11 07:19:38','2026-05-11 07:32:59'),
(243,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-11 16:47:05','2026-05-11 16:47:05'),
(244,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-12 00:01:31','2026-05-12 00:01:31'),
(245,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-05-12 00:05:54','2026-05-12 00:05:54'),
(246,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-12 09:22:33','2026-05-12 09:22:33'),
(247,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-12 19:38:10','2026-05-12 19:38:10'),
(248,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-12 19:38:14','2026-05-12 19:38:14'),
(249,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-05-14 22:23:55','2026-05-14 22:23:55'),
(250,'🚨 Missing Unit: DCQ 1551','Unit DCQ 1551 has not remitted a boundary for 17 day(s). The last driver on record is Almar Monarba.','missing_unit',1,NULL,'2026-05-14 22:23:55','2026-06-01 16:30:35'),
(251,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-14 22:24:01','2026-05-14 22:24:01'),
(252,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-18 17:06:47','2026-05-18 17:06:47'),
(253,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-05-19 19:28:03','2026-05-19 19:28:03'),
(254,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:10:11','2026-05-20 01:10:11'),
(255,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:11:58','2026-05-20 01:11:58'),
(256,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:12:50','2026-05-20 01:12:50'),
(257,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:12:58','2026-05-20 01:12:58'),
(258,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:13:10','2026-05-20 01:13:10'),
(259,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 01:13:32','2026-05-20 01:13:32'),
(260,'Today\'s Unit Coding','There are 21 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-05-20 18:24:23','2026-05-20 18:24:23'),
(261,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-20 18:44:53','2026-05-20 18:44:53'),
(262,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-05-25 10:38:37','2026-05-25 10:38:37'),
(263,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-05-26 11:35:40','2026-05-26 11:35:40'),
(264,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-26 18:30:38','2026-05-26 18:30:38'),
(265,'Today\'s Unit Coding','There are 21 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-05-27 10:19:29','2026-05-27 10:19:29'),
(266,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-27 11:17:45','2026-05-27 11:17:45'),
(267,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:25:50','2026-05-27 16:25:50'),
(268,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:25:54','2026-05-27 16:25:54'),
(269,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:25:59','2026-05-27 16:25:59'),
(270,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:27:30','2026-05-27 16:27:30'),
(271,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:27:32','2026-05-27 16:27:32'),
(272,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:27:34','2026-05-27 16:27:34'),
(273,'Engine RESTORE: ALA 3699','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-05-27 16:33:07','2026-05-27 16:33:07'),
(274,'Engine RESTORE: AAA 4591','Remote engine restore command delivered successfully via Tracksolid.','success',1,NULL,'2026-05-28 08:35:48','2026-06-01 16:47:03'),
(275,'Engine RESTORE: AAA 4591','Remote engine restore command delivered successfully via Tracksolid.','success',1,NULL,'2026-05-28 08:49:39','2026-06-01 16:47:03'),
(276,'Engine RESTORE: AAA 4591','Remote engine restore command delivered successfully via Tracksolid.','success',1,NULL,'2026-05-28 08:49:43','2026-06-01 16:47:03'),
(277,'Engine RESTORE: AAA 4591','Remote engine restore command delivered successfully via Tracksolid.','success',1,NULL,'2026-05-28 08:49:49','2026-06-01 16:47:03'),
(278,'Engine RESTORE: AAA 4591','Remote engine restore command delivered successfully via Tracksolid.','success',1,NULL,'2026-05-28 08:53:03','2026-06-01 16:47:03'),
(279,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-05-28 09:43:45','2026-05-28 09:43:45'),
(280,'🚨 Missing Unit: AAA 4591','Unit AAA 4591 has not remitted a boundary for 6 day(s). The last driver on record is Unknown Driver.','missing_unit',1,NULL,'2026-05-28 09:43:46','2026-06-01 16:47:03'),
(281,'Today\'s Unit Coding','There are 22 units on coding today (Friday).','coding_notice',0,NULL,'2026-05-29 13:30:33','2026-05-29 13:30:33'),
(282,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 13:44:57','2026-05-29 13:44:57'),
(283,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 13:45:18','2026-05-29 13:45:18'),
(284,'⚠ OUT OF STOCK: Brake Disk','Stock: 0 items. Source: Unspecified','low_stock',0,NULL,'2026-05-29 14:23:33','2026-05-29 14:23:33'),
(285,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 14:52:40','2026-05-29 14:52:40'),
(286,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 14:52:43','2026-05-29 14:52:43'),
(287,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 14:52:45','2026-05-29 14:52:45'),
(288,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 15:10:21','2026-05-29 15:10:21'),
(289,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 15:10:25','2026-05-29 15:10:25'),
(290,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 19:25:36','2026-05-29 19:25:36'),
(291,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-05-29 19:25:59','2026-05-29 19:25:59'),
(292,'🔧 Service Due: EAB 8186','Unit EAB 8186 has reached 5,128 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-05-31 09:59:34','2026-05-31 09:59:34'),
(293,'🔧 Service Due: EAA 9555','Unit EAA 9555 has reached 5,161 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-06-01 11:25:03','2026-06-01 11:25:03'),
(294,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-06-01 16:30:35','2026-06-01 16:30:35'),
(295,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-01 16:30:41','2026-06-01 16:30:41'),
(296,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-01 16:30:56','2026-06-01 16:30:56'),
(297,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-06-02 08:28:46','2026-06-02 08:28:46'),
(298,'Today\'s Unit Coding','There are 21 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-06-03 21:17:04','2026-06-03 21:17:04'),
(299,'🚨 Missing Unit: DCQ 1551','Unit DCQ 1551 has not remitted a boundary for 23 day(s). The last driver on record is Almar Monarba.','missing_unit',0,NULL,'2026-06-03 21:17:04','2026-06-25 14:07:26'),
(300,'Today\'s Unit Coding','There are 22 units on coding today (Friday).','coding_notice',0,NULL,'2026-06-05 16:55:06','2026-06-05 16:55:06'),
(301,'Engine RESTORE: AAK 9196','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-06-05 21:02:13','2026-06-05 21:02:13'),
(302,'Engine KILL: VAA 9864','Remote engine kill command delivered successfully via Tracksolid.','danger',0,NULL,'2026-06-07 12:59:29','2026-06-07 12:59:29'),
(303,'Engine RESTORE: VAA 9864','Remote engine restore command delivered successfully via Tracksolid.','success',0,NULL,'2026-06-07 12:59:47','2026-06-07 12:59:47'),
(304,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-07 19:38:53','2026-06-07 19:38:53'),
(305,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 19:40:27','2026-06-07 19:40:27'),
(306,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-07 19:40:53','2026-06-07 19:40:53'),
(307,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 19:46:55','2026-06-07 19:46:55'),
(308,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 19:46:55','2026-06-07 19:46:55'),
(309,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 19:47:13','2026-06-07 19:47:13'),
(310,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-07 19:50:47','2026-06-07 19:50:47'),
(311,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 19:51:50','2026-06-07 19:51:50'),
(312,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-07 22:20:14','2026-06-07 22:20:14'),
(313,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-07 22:21:23','2026-06-07 22:21:23'),
(314,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 08:33:37','2026-06-08 08:33:37'),
(315,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 08:59:56','2026-06-08 08:59:56'),
(316,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 09:34:28','2026-06-08 09:34:28'),
(317,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 10:52:58','2026-06-08 10:52:58'),
(318,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-08 10:53:11','2026-06-08 10:53:11'),
(319,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 10:54:29','2026-06-08 10:54:29'),
(320,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 10:55:09','2026-06-08 10:55:09'),
(321,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 11:05:19','2026-06-08 11:05:19'),
(322,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-08 11:08:53','2026-06-08 11:08:53'),
(323,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 11:09:47','2026-06-08 11:09:47'),
(324,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 11:25:27','2026-06-08 11:25:27'),
(325,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 11:49:56','2026-06-08 11:49:56'),
(326,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-06-08 13:00:34','2026-06-08 13:00:34'),
(327,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-08 21:51:24','2026-06-08 21:51:24'),
(328,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-06-09 08:11:34','2026-06-09 08:11:34'),
(329,'Today\'s Unit Coding','There are 21 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-06-10 01:00:00','2026-06-10 01:00:00'),
(330,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-10 06:36:53','2026-06-10 06:36:53'),
(331,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-10 06:41:21','2026-06-10 06:41:21'),
(332,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-10 10:03:16','2026-06-10 10:03:16'),
(333,'🔊 Test Sound Broadcast','Lodi! Sumisigaw na ang chime sa phone mo! Gumagana na ang real-time push bypass! 🔥','test_chime_alert',0,NULL,'2026-06-10 10:55:33','2026-06-10 10:55:33'),
(334,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-10 18:41:04','2026-06-10 18:41:04'),
(335,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-10 18:41:44','2026-06-10 18:41:44'),
(336,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-10 18:42:46','2026-06-10 18:42:46'),
(337,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-06-11 01:00:00','2026-06-11 01:00:00'),
(338,'🔧 Service Due: CAV 2607','Unit CAV 2607 has reached 7,827 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-06-11 18:45:59','2026-06-11 18:45:59'),
(339,'Today\'s Unit Coding','There are 22 units on coding today (Friday).','coding_notice',0,NULL,'2026-06-12 01:00:00','2026-06-12 01:00:00'),
(340,'🚨 Missing Unit: AAA 4591','Unit AAA 4591 has not remitted a boundary for 10 day(s). The last driver on record is July Sunico.','missing_unit',1,NULL,'2026-06-12 11:52:56','2026-06-21 22:12:42'),
(341,'🚨 Missing Unit: CAV 2607','Unit CAV 2607 has not remitted a boundary for 9 day(s). The last driver on record is Joel Sumando.','missing_unit',0,NULL,'2026-06-12 11:52:56','2026-06-20 15:10:54'),
(342,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-06-16 01:00:00','2026-06-16 01:00:00'),
(343,'🔧 Service Due: AAA 4591','Unit AAA 4591 has reached 5,111 KM since last service. Maintenance is now REQUIRED.','odo_maint_due',0,NULL,'2026-06-16 12:30:16','2026-06-16 12:30:16'),
(344,'Today\'s Unit Coding','There are 16 units on coding today (Monday).','coding_notice',0,NULL,'2026-06-22 01:00:00','2026-06-22 01:00:00'),
(345,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-22 11:49:22','2026-06-22 11:49:22'),
(346,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-22 11:49:51','2026-06-22 11:49:51'),
(347,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-22 17:27:50','2026-06-22 17:27:50'),
(348,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-22 17:28:18','2026-06-22 17:28:18'),
(349,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-22 17:30:08','2026-06-22 17:30:08'),
(350,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-22 17:31:28','2026-06-22 17:31:28'),
(351,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-22 17:37:04','2026-06-22 17:37:04'),
(352,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-22 17:37:44','2026-06-22 17:37:44'),
(353,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-22 17:38:16','2026-06-22 17:38:16'),
(354,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-22 17:38:54','2026-06-22 17:38:54'),
(355,'Today\'s Unit Coding','There are 18 units on coding today (Tuesday).','coding_notice',0,NULL,'2026-06-23 01:00:00','2026-06-23 01:00:00'),
(356,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-23 06:56:13','2026-06-23 06:56:13'),
(357,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-23 06:57:09','2026-06-23 06:57:09'),
(358,'Engine KILL: NEF 4940','Remote engine kill command delivered successfully via AKSH GPS.','danger',0,NULL,'2026-06-23 07:28:32','2026-06-23 07:28:32'),
(359,'Engine RESTORE: NEF 4940','Remote engine restore command delivered successfully via AKSH GPS.','success',0,NULL,'2026-06-23 07:29:33','2026-06-23 07:29:33'),
(360,'🚨 Missing Unit: AAA 4591','Unit AAA 4591 has not remitted a boundary for 2 day(s). The last driver on record is July Sunico.','missing_unit',0,NULL,'2026-06-23 20:53:58','2026-06-25 14:07:26'),
(361,'Accident Reported: ALA 3699','Driver Joel Sumando reported an accident. Fault: YES. Charge: ₱1,944.00','danger',0,NULL,'2026-06-24 16:17:04','2026-06-24 16:17:04'),
(362,'Today\'s Unit Coding','There are 21 units on coding today (Wednesday).','coding_notice',0,NULL,'2026-06-24 01:00:00','2026-06-24 01:00:00'),
(363,'Today\'s Unit Coding','There are 16 units on coding today (Thursday).','coding_notice',0,NULL,'2026-06-25 01:00:00','2026-06-25 01:00:00');
/*!40000 ALTER TABLE `system_alerts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(191) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES
(1,'archive_deletion_password','$2y$10$SIeSgKqpNvGv/A7j7027K.EwI5rTT.arKtoD9z7QixIgbckKNip22','security','2026-04-30 10:25:04','2026-04-30 10:25:04');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `unit_assignments`
--

DROP TABLE IF EXISTS `unit_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `assignment_type` enum('permanent','temporary','relal') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `monthly_target` decimal(10,2) DEFAULT 0.00,
  `commission_rate` decimal(5,2) DEFAULT 0.00,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_unit` (`unit_id`),
  KEY `idx_driver` (`driver_id`),
  KEY `idx_dates` (`start_date`,`end_date`),
  KEY `idx_status` (`status`),
  CONSTRAINT `unit_assignments_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unit_assignments_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_assignments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `unit_assignments` WRITE;
/*!40000 ALTER TABLE `unit_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `unit_assignments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(20) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `motor_no` varchar(191) DEFAULT NULL,
  `chassis_no` varchar(191) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `status` enum('active','maintenance','coding','retired','vacant','at_risk','missing') NOT NULL DEFAULT 'active',
  `last_service_odo_gps` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_gps_odo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_pinned_missing` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True if unit is under surveillance/missing',
  `driver_id` int(11) DEFAULT NULL,
  `secondary_driver_id` int(11) DEFAULT NULL,
  `current_turn_driver_id` bigint(20) unsigned DEFAULT NULL,
  `last_swapping_at` timestamp NULL DEFAULT NULL,
  `shift_deadline_at` timestamp NULL DEFAULT NULL,
  `boundary_rate` decimal(10,2) DEFAULT 1100.00,
  `purchase_date` date DEFAULT NULL,
  `purchase_cost` decimal(12,2) DEFAULT NULL,
  `roi_achieved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unit_type` enum('new','old') DEFAULT 'new',
  `device_installed` tinyint(1) DEFAULT 0,
  `device_installation_date` date DEFAULT NULL,
  `gps_device_count` int(11) DEFAULT 0,
  `gps_link` text DEFAULT NULL,
  `imei` varchar(20) DEFAULT NULL COMMENT 'Tracksolid Pro device identifier',
  `gps_provider` varchar(20) DEFAULT 'tracksolid' COMMENT 'GPS provider type: tracksolid or aksh',
  `gps_password` varchar(50) DEFAULT NULL COMMENT 'Custom GPS device password, falls back to env default',
  `engine_status` varchar(191) DEFAULT NULL,
  `dashcam_device_count` int(11) DEFAULT 0,
  `coding_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') DEFAULT NULL,
  `is_coding_exempt` tinyint(1) DEFAULT 0,
  `coding_updated_at` timestamp NULL DEFAULT NULL,
  `max_drivers` int(11) DEFAULT 2,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plate_number` (`plate_number`),
  KEY `driver_id` (`driver_id`),
  KEY `idx_units_roi` (`roi_achieved`),
  KEY `fk_units_secondary_driver` (`secondary_driver_id`),
  KEY `units_imei_index` (`imei`),
  CONSTRAINT `units_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `units_secondary_driver_id_foreign` FOREIGN KEY (`secondary_driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES
(1,'AAK 9196','Toyota','Vios','2NZ7307868','NCP151-2031009',2015,'maintenance',15686.38,21380.64,0,112,NULL,11,'2026-06-11 17:39:12','2026-06-12 17:39:12',1000.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097285388','tracksolid',NULL,'restored',0,'Wednesday',0,NULL,2,NULL,125,NULL),
(2,'AAQ 1743','Toyota','Vios','2NZ7160776','NCP151-2022506',2014,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-05-04 13:47:42','2026-05-04 17:41:27',900.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-05-09 14:27:23','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(3,'ABL 6901','Toyota','Vios','2NZ7400896','NCP151-2037524',2015,'vacant',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(4,'ADY 2597','Toyota','Vios',NULL,NULL,2023,'vacant',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:29:12','2026-04-14 01:14:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(5,'ADY 2599','Toyota','Vios',NULL,NULL,2023,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:29:12','2026-05-01 16:05:16','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(6,'AEA 9630','Toyota','Vios','2NZ7301579','NCP151-2030436',2015,'active',0.00,0.00,0,NULL,NULL,NULL,'2026-04-26 09:19:56',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-05-04 16:11:34','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(7,'ALA 3699','Toyota','Vios','2NZ7384223','NCP151-2036531',2015,'vacant',0.00,24267.40,0,11,NULL,11,'2026-06-24 16:23:09','2026-06-25 16:23:09',1200.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-06-25 17:56:47','new',0,NULL,0,NULL,'352503097294869','tracksolid',NULL,NULL,0,'Friday',0,NULL,2,NULL,125,NULL),
(8,'NAD 1140','Toyota','Vios','1NRX093367','PA1B19F36G4016559',2017,'active',41516.23,44650.30,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:29:12','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097292061','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(12,'ABF 2705','Toyota','Vios',NULL,NULL,2023,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1200.00,NULL,NULL,0,'2026-04-10 03:41:13','2026-05-08 10:26:31','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(17,'EAD 7438','Toyota','Vios','1NRX511105','PA1B13F30K4102617',2020,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:41:13','2026-05-06 19:08:56','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(19,'NAM 1610','Toyota','Vios','1NRX265877','PA1B19F33J4055018',2017,'vacant',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:41:13','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(20,'NCJ 7661','Toyota','Vios',NULL,NULL,2023,'active',0.00,96654.40,0,NULL,NULL,114,'2026-05-08 23:58:43','2026-05-09 23:58:43',1400.00,NULL,NULL,0,'2026-04-10 03:41:13','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097253287','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(21,'NDA 8102','Toyota','Vios','1NRX399793','PA1B13F30J4076793',2019,'vacant',0.00,0.00,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:41:13','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(22,'VFL 543','Toyota','Vios','2NZ6564244','NCP92-964857',2013,'vacant',0.00,0.00,0,NULL,NULL,NULL,'2026-05-01 17:52:25','2026-05-02 17:52:25',1100.00,NULL,500000.00,0,'2026-04-10 03:41:13','2026-05-01 17:52:25','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(51,'CAX 5430','Toyota','Vios','1NRX765584','PA1B18F37N4171824',2022,'maintenance',0.00,0.00,0,NULL,NULL,18,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:49:10','2026-05-06 18:41:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(112,'AAA 4591','Toyota','Vios','2NZ7307868','NCP15122031009',2014,'active',93844.95,101695.39,0,64,6,64,'2026-06-21 20:13:20','2026-06-22 20:13:20',1100.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503096887481','tracksolid',NULL,NULL,0,'Monday',0,NULL,2,NULL,125,NULL),
(113,'ABF 7471','Toyota','Vios','2NZ7470861','NCP151-2042785',2015,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-05-02 22:09:36',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-05-02 22:21:37','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(114,'ABG 7479','Toyota','Vios','2NZ7494105','NCP151-2043398',2015,'active',0.00,0.00,0,NULL,NULL,3,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-05-02 22:23:40','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(115,'ABL 1667','Toyota','Vios','2NZ7542383','NCP151-2046832',2015,'vacant',0.00,43550.73,0,4,NULL,4,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097303199','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(116,'ABP 7643','Toyota','Vios','2NZ7541411','NCP151-2046789',2015,'active',0.00,76350.28,0,NULL,NULL,5,'2026-04-30 22:53:49',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503096872566','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(117,'ACH 5774','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,NULL,NULL,6,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:37','2026-05-04 00:41:06','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(118,'ADY 2598','Toyota','Vios',NULL,NULL,2023,'vacant',0.00,41157.88,0,7,NULL,7,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:37','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097292152','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(119,'AOA 8917','Toyota','Vios','2NZ7263141','NCP151-2028527',2015,'active',0.00,0.00,0,8,NULL,8,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:37','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(120,'ASA 6135','Toyota','Vios',NULL,NULL,2023,'active',0.00,82371.00,0,NULL,NULL,9,'2026-05-01 17:53:08',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:37','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097284233','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(121,'CAT 6073','Toyota','Vios','1NRX519089','PA1B18F37K4105320',2020,'maintenance',0.00,0.00,0,10,NULL,10,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:37','2026-05-08 12:06:19','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(122,'CAV 2607','Toyota','Vios','1NRX573855','PA1B18F3XL4116880',2020,'coding',116822.23,126399.16,0,NULL,NULL,11,'2026-06-10 10:12:01','2026-06-11 10:12:01',1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097246554','tracksolid',NULL,NULL,0,'Thursday',0,NULL,2,NULL,125,NULL),
(123,'CAV 6803','Toyota','Vios','1NRX591797','PA1B18F34L4123081',2021,'active',0.00,0.00,0,12,13,12,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(124,'CAV 9662','Toyota','Vios','1NRX622805','PA1B18F33L4126120',2021,'active',0.00,0.00,0,14,15,15,'2026-04-26 06:57:54',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-04 00:19:57','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(125,'CAV 9716','Toyota','Vios','1NRX622596','PA1B18F33L4125985',2021,'active',0.00,0.00,0,16,17,16,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-06 18:36:59','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(126,'CBM 1979','Toyota','Vios','1NRX665295','PA1B18F3XM4139156',2021,'active',0.00,0.00,0,19,20,20,'2026-05-01 17:50:00',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-11 20:15:09','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(127,'DAD 7555','Toyota','Vios','1NRX128495','PA1B19F32H4024496',2017,'active',0.00,0.00,0,21,NULL,21,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(128,'DAJ 7468','Toyota','Vios','1NRX364595','PA1B13F35J4069838',2019,'active',0.00,0.00,0,22,NULL,22,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(129,'DAT 1367','Toyota','Vios','1NRX586443','PA1B18F37L4121129',2021,'maintenance',0.00,0.00,0,23,NULL,23,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-07 02:12:53','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(130,'DAT 2657','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,24,NULL,24,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-04-14 01:14:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(131,'DAU 9027','Toyota','Vios','1NRX669745','PA1B18F39M4140346',2021,'maintenance',0.00,0.00,0,25,26,25,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-06 18:52:10','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(132,'DAZ 9769','Toyota','Vios','1NRX539051','PA1B18F35L4109741',2020,'maintenance',0.00,0.00,0,27,28,27,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-05-06 18:35:38','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(133,'DBA 1887','Toyota','Vios','1NRX544017','PA1B118F30L4110974',2020,'maintenance',0.00,0.00,0,29,NULL,29,'2026-04-14 04:20:58',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-04-30 09:40:31','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,18,NULL),
(134,'DBA 2302','Toyota','Vios','1NRX530110','PA1B18F38K4108095',2020,'active',0.00,0.00,0,30,NULL,30,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-05-05 00:51:58','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(135,'DBA 5420','Toyota','Vios','1NRX554443','PA1B18F3XL4112067',2020,'active',0.00,0.00,0,32,NULL,32,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(136,'DCQ 1551','Toyota','Vios','1NRX049858','PA1B19F37G4007336',2017,'active',0.00,108787.95,0,124,NULL,124,'2026-06-01 11:26:57','2026-06-02 11:26:57',1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503096888661','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(137,'EAA 4540','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,34,NULL,34,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-05-05 01:13:59','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(138,'EAA 9555','Toyota','Vios',NULL,NULL,2023,'active',103183.16,112427.19,0,35,NULL,35,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097289034','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(139,'EAB 8186','Toyota','Vios',NULL,NULL,2023,'active',97885.26,107127.54,0,36,NULL,36,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097248097','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(140,'EAE 1247','Toyota','Vios','1NRX570523','PA1B18F35L4115295',2020,'active',0.00,0.00,0,37,38,37,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-05-06 18:37:08','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(141,'EAE 1919','Toyota','Vios','1NRX684775','PA1B18F354143793',2021,'maintenance',0.00,0.00,0,39,40,39,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-06 18:52:36','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(142,'EAE 4949','Toyota','Vios','1NRX728802','PA1B18F33M4156266',2021,'active',0.00,0.00,0,41,42,41,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(143,'EAE 5883','Toyota','Vios','1NRX735643','PA1B18F3XM4159021',2021,'active',0.00,0.00,0,43,44,43,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(144,'EAF 6347','Toyota','Vios','1NRX587947','PA1B18F34L4121976',2021,'active',0.00,0.00,0,45,NULL,45,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(145,'EAF 7245','Toyota','Vios','1NRX592060','PA1B18F34L4123212',2021,'active',0.00,0.00,0,46,NULL,46,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(146,'NAC 4989','Toyota','Vios','1NRX072072','PA1B19F3XG4012319',2017,'active',0.00,0.00,0,47,48,47,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-05-06 18:30:45','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(147,'NAE 7193','Toyota','Vios','1NRX118001','PA1B19F35H4021382',2017,'active',0.00,0.00,0,49,NULL,49,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(148,'NBR 1341','Toyota','Vios',NULL,NULL,2023,'maintenance',0.00,0.00,0,50,NULL,50,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-05-06 18:32:34','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(149,'NBW 7071','Toyota','Vios','2NZ7666502','NCP151-2055742',2016,'active',0.00,64169.82,0,51,NULL,51,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503096885121','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(150,'NBX 4348','Toyota','Vios','1NRX136597','PA1B19F31H4026529',2017,'active',0.00,0.00,0,52,NULL,52,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(151,'NCN 8583','Toyota','Vios','1NRX142517','PA1B119F30H4027929',2017,'maintenance',0.00,19286.15,0,53,NULL,53,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097297284','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(152,'NCW 5011','Toyota','Vios','1NRX288337','PA1B19F31J060654',2018,'active',0.00,53784.93,0,NULL,NULL,54,'2026-05-02 20:29:54','2026-05-03 20:29:54',1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097285396','tracksolid',NULL,NULL,0,'Monday',0,NULL,2,NULL,125,NULL),
(153,'NDA 5429','Toyota','Vios','1NRX382535','PA1B13F37J4074295',2019,'vacant',0.00,0.00,0,55,NULL,55,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(154,'NAD 8102','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,NULL,57,56,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-05-06 18:32:48','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(155,'NDA 8106','Toyota','Vios','1NRX400695','PA1B13F38J4076895',2019,'active',0.00,0.00,0,58,NULL,58,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(156,'NDC 7363','Toyota','Vios',NULL,NULL,2023,'active',0.00,101895.96,0,59,NULL,59,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097248055','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(157,'NDG 7105','Toyota','Vios','1NRX074746','PA1B19F32G4012928',2017,'maintenance',0.00,44266.17,0,60,NULL,60,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097303249','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(158,'NDI 2585','Toyota','Vios','1NRX428966','PA1B13F37K4083631',2019,'active',0.00,0.00,0,NULL,62,61,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-05-04 16:44:22','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(159,'NEA 1292','Toyota','Vios','1NRX399472','PA1B13F38J4076640',2019,'active',0.00,0.00,0,63,NULL,63,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(160,'NEF 4940','Toyota','Vios','1NRX507225','PA1B13F31K4102013',2020,'active',0.00,0.00,0,65,NULL,NULL,'2026-05-04 20:01:06',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'17026288091','aksh',NULL,'restored',0,'Friday',0,NULL,2,NULL,125,NULL),
(161,'NEI 4883','Toyota','Vios','1NRX428108','PA1B119F33K4083254',2019,'active',0.00,0.00,0,66,NULL,66,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(162,'NEN 2955','Toyota','Vios','1NRX479141','PA1B13F39K4095280',2019,'active',0.00,0.00,0,67,NULL,67,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(163,'NEN 2957','Toyota','Vios','1NRX478775','PA1B13F37K4095102',2019,'active',0.00,0.00,0,68,69,68,'2026-04-13 14:10:10',NULL,1300.00,NULL,500000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(164,'NEO 67116','Toyota','Vios',NULL,NULL,2021,'active',0.00,0.00,0,70,71,70,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(165,'NEP 2440','Toyota','Vios','1NRX662804','PA1B18F32M4138437',2021,'active',0.00,0.00,0,72,NULL,72,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-04 03:39:33','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(166,'NEP 9750','Toyota','Vios','1NRX670488','PA1B18F33M4140536',2021,'active',0.00,0.00,0,74,75,74,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(167,'NET 6100','Toyota','Vios',NULL,NULL,2021,'active',0.00,0.00,0,76,77,76,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(168,'NEU 5546','Toyota','Vios','1NRX494346','PA1B13F39K4098339',2020,'active',0.00,0.00,0,78,79,78,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(169,'NEV 5065','Toyota','Vios','1NRX728865','PA1B18F35M4156270',2021,'active',0.00,0.00,0,80,81,80,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(170,'NEW 3821','Toyota','Vios','1NRX699044','PA1B18F32M4147994',2021,'active',0.00,0.00,0,82,83,82,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(171,'NEW 6279','Toyota','Vios','1NRX711080','PA1B18F33M4150502',2021,'maintenance',0.00,0.00,0,84,85,84,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-06 18:10:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(172,'NFH 3664','Toyota','Vios','1NRX758930','PA1B18F35N4169456',2022,'active',0.00,0.00,0,NULL,87,87,'2026-05-01 13:22:14',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-05-09 19:40:03','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,125,NULL),
(173,'NFZ 8295','Toyota','Vios','1NRX563284','PA1B18F33L4114131',2020,'active',0.00,0.00,0,88,NULL,88,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(174,'NGA 5044','Toyota','Vios','1NRX513727','PA1B13F32K4103414',2020,'active',0.00,0.00,0,89,89,89,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-05-06 18:37:04','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(175,'NGA 7736','Toyota','Vios','1NRX585027','PA1B18F33L4120575',2021,'active',0.00,0.00,0,90,NULL,90,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(176,'NGB 2854','Toyota','Vios','1NRX593170','PA1B18F36L4123549',2021,'active',0.00,0.00,0,91,NULL,91,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(177,'NGB 6033','Toyota','Vios','1NRX617160','PA1B18F3XL4124719',2021,'active',0.00,0.00,0,92,NULL,92,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(178,'NGF 1484','Toyota','Vios','1NRX505510','PA134K4101664',2020,'active',0.00,0.00,0,93,NULL,93,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(179,'NGO 2629','Toyota','Vios','1NRX587826','PA1B18F37L4121826',2021,'active',0.00,0.00,0,94,95,94,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-04-26 19:42:02','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(180,'NGP 1877','Toyota','Vios',NULL,NULL,2021,'maintenance',0.00,0.00,0,96,97,96,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-05-06 18:41:58','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(181,'ULO 884','Toyota','Vios',NULL,NULL,2023,'vacant',0.00,0.00,0,NULL,99,98,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-04-29 13:16:05','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(182,'UWD 421','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,100,NULL,100,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-04-14 01:14:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(183,'UWD 431','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,101,NULL,101,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-04-14 01:14:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(184,'UWN 226','Toyota','Vios',NULL,NULL,2023,'active',0.00,0.00,0,102,NULL,102,'2026-04-13 14:10:10',NULL,1400.00,NULL,NULL,0,'2026-04-10 03:53:38','2026-04-14 01:14:14','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(185,'VAA 9864','Toyota','Vios','1NRX676394','PA1B18F39M4141920',2021,'maintenance',0.00,69871.99,0,103,NULL,103,'2026-04-13 14:10:10',NULL,1400.00,NULL,590000.00,0,'2026-04-10 03:53:38','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503097295197','tracksolid',NULL,'restored',0,NULL,0,NULL,2,NULL,NULL,NULL),
(186,'NAN 1349','Toyota','Vios','1NRX560364','PA1B18F35L4113725',2020,'maintenance',0.00,120319.72,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1300.00,NULL,550000.00,0,'2026-04-10 04:28:45','2026-06-25 14:08:19','new',0,NULL,0,NULL,'865784053415173','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(187,'ABP 2705','Toyota','Vios','2NZ7557953','NCP151-2048091',2015,'vacant',0.00,79995.88,0,NULL,NULL,NULL,'2026-04-13 14:10:10',NULL,1200.00,NULL,500000.00,0,'2026-04-10 04:28:45','2026-06-25 14:08:19','new',0,NULL,0,NULL,'352503096881435','tracksolid',NULL,NULL,0,NULL,0,NULL,2,NULL,NULL,NULL),
(190,'TX-00122','TOYOTA','VIOS',NULL,NULL,2026,'active',0.00,0.00,0,105,NULL,NULL,NULL,NULL,1100.00,'2026-04-28',500000.00,0,'2026-04-30 07:42:39','2026-04-30 07:43:34','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Monday',0,NULL,2,125,125,'2026-04-30 07:43:34'),
(191,'ACD123','TOYOTA','VIOS',NULL,NULL,2026,'maintenance',0.00,0.00,0,NULL,NULL,NULL,'2026-05-02 20:24:42','2026-05-03 20:24:42',1100.00,'2026-04-30',1000.00,0,'2026-04-30 23:03:23','2026-05-11 18:46:23','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Tuesday',0,NULL,2,125,125,'2026-05-11 18:46:23'),
(192,'ACD1245','HONDA','VIOS',NULL,NULL,2026,'active',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-30',12000.00,0,'2026-04-30 23:10:34','2026-05-11 19:02:39','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Wednesday',0,NULL,2,125,125,NULL),
(193,'WWWWWWWW','3123FWEFWE','EQWEQWEQWEF2234','QW122','123123312',2026,'coding',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-05-01',1000000.00,0,'2026-05-01 10:07:57','2026-05-11 20:13:41','new',0,NULL,0,NULL,'221312312333333','tracksolid',NULL,NULL,0,'Friday',0,NULL,2,125,125,'2026-05-11 20:13:41'),
(194,'ACD1256','HONDA','CIVIC','2R24151812','NC3456789',2026,'active',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-04-30',1700.00,0,'2026-05-01 13:09:53','2026-05-11 19:02:30','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Wednesday',0,NULL,2,133,125,NULL),
(195,'ABC12344','TOYOTA','CIVIC','2R24151812','NC3456786',2026,'active',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-05-01',120.00,0,'2026-05-02 21:17:22','2026-05-11 20:13:51','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Tuesday',0,NULL,2,125,125,'2026-05-11 20:13:51'),
(196,'BDEHF376','RNGRNGJTNGTGJRN','BGTBRGJRBGKJRGJ','7584583586357645783475745','5843758437593859438598435',2026,'active',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.01,NULL,0.02,0,'2026-05-03 22:24:49','2026-05-11 19:59:36','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Wednesday',0,NULL,2,125,125,'2026-05-11 19:59:36'),
(197,'ABC2425','LAMBORGINI','HAKDOG','2NR3456777','NCP4D',2026,'active',0.00,0.00,0,2,NULL,NULL,NULL,NULL,1100.00,'2026-05-03',34568.00,0,'2026-05-04 08:34:01','2026-05-04 08:41:55','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Wednesday',0,NULL,2,125,125,'2026-05-04 08:41:55'),
(198,'ABCC 123','LAMBORGINI','WOWERS','2P5555','NCT77777',2026,'active',0.00,0.00,0,NULL,NULL,NULL,NULL,NULL,1100.00,'2026-05-04',2545.00,0,'2026-05-04 08:37:31','2026-05-04 08:41:37','new',0,NULL,0,NULL,NULL,'tracksolid',NULL,NULL,0,'Tuesday',0,NULL,2,125,125,'2026-05-04 08:41:37');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `user_recognized_devices`
--

DROP TABLE IF EXISTS `user_recognized_devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_recognized_devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `device_token` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_recognized_devices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `user_recognized_devices` WRITE;
/*!40000 ALTER TABLE `user_recognized_devices` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_recognized_devices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_token` (`session_token`),
  KEY `user_id` (`user_id`),
  KEY `idx_session_token` (`session_token`),
  CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `user_sessions` WRITE;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `user_verified_browsers`
--

DROP TABLE IF EXISTS `user_verified_browsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_verified_browsers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `browser_token` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_verified_browsers_browser_token_unique` (`browser_token`),
  KEY `user_verified_browsers_user_id_browser_token_index` (`user_id`,`browser_token`),
  CONSTRAINT `user_verified_browsers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_verified_browsers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `user_verified_browsers` WRITE;
/*!40000 ALTER TABLE `user_verified_browsers` DISABLE KEYS */;
INSERT INTO `user_verified_browsers` VALUES
(1,18,'5d422c028ac811c1d99686da1a7239361aaa8965b5fe088b70b88b404f15991c','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36',NULL,'2026-04-13 05:34:52','2026-04-26 20:00:48','2026-04-13 05:34:52','2026-04-26 20:00:48'),
(2,18,'3d44d5bf1c3c6f133c0a35f3c9d133966e3a1e3eb88c641e1e159351b31a73e3','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36',NULL,'2026-04-13 11:43:51','2026-04-27 18:45:58','2026-04-13 11:43:51','2026-04-27 18:45:58'),
(3,18,'7b70b784689280b7e3d51ad646a729b12111f12ebf63f71512c0ab34b31e6351','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-26 17:31:34','2026-04-26 17:31:34','2026-04-26 17:31:34','2026-04-26 17:31:34'),
(4,125,'ca59afe01f4e5adc3c420388e150d2313cb3c3af29177e71eb51601c611895fe','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 01:57:56','2026-04-27 01:57:56','2026-04-27 01:57:56','2026-04-27 01:57:56'),
(5,18,'ef61a22519b0fe2a58c1cd346df4c49b9faddb3c660e0955d1dce832f63c3928','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:04:58','2026-04-27 02:04:58','2026-04-27 02:04:58','2026-04-27 02:04:58'),
(6,125,'94957a613f2ed3bfde9b0bd228d31c6e155b5fb9d7561cb3394d01709855543e','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:10:14','2026-04-27 02:10:14','2026-04-27 02:10:14','2026-04-27 02:10:14'),
(7,18,'5b11f102f42bc92ab891faf757c6f9f66ccdcf29f060df19692e6af129099e6c','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:13:40','2026-04-27 02:13:40','2026-04-27 02:13:40','2026-04-27 02:13:40'),
(8,125,'6f6cf2802f3e97d0091f72b29f803404478a4f38344f43e9342933bdfb9868f3','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 02:15:56','2026-04-27 02:15:56','2026-04-27 02:15:56','2026-04-27 02:15:56'),
(9,18,'7479849e564a17ef2702ace087a0a9b12c6435c2c51b12083898762e2372f29c','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 18:43:09','2026-04-27 18:43:09','2026-04-27 18:43:09','2026-04-27 18:43:09'),
(10,125,'b0a9ac89ad89142b0aeb6a46163b04480eaff0959febc1da8fc88f60fc1c7aa1','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-27 18:44:34','2026-04-27 19:32:34','2026-04-27 18:44:34','2026-04-27 19:32:34'),
(14,131,'4035b34e0eca60261b659f548d5c366a25d4bfa49f472c8dedd63f060a3b828f','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 11:04:23','2026-04-30 11:04:23','2026-04-30 11:04:23','2026-04-30 11:04:23'),
(15,125,'45a357aa88d5cf91596813511c8cafce29327d521bdc9c66129ea66001b88fcf','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 21:38:38','2026-04-30 21:38:38','2026-04-30 21:38:38','2026-04-30 21:38:38'),
(16,125,'d90ba4e8c697d80606b60671ba3224962d13047e72bd7d47a1be4c254a2047c3','139.135.200.132','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 21:42:41','2026-04-30 21:42:41','2026-04-30 21:42:41','2026-04-30 21:42:41'),
(17,125,'21ae5141acba1bb2000bd6bcb6997cc0c2e5f6c47a769b95aa8373d7214caae9','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 21:58:57','2026-04-30 21:58:57','2026-04-30 21:58:57','2026-04-30 21:58:57'),
(18,132,'ebb0af19fd2b1a096c50edb915e1425c4882d4c83c4a970522239d38b00dbf90','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:31:54','2026-04-30 22:31:54','2026-04-30 22:31:54','2026-04-30 22:31:54'),
(19,125,'8c4f6a0598811d383c91b5d0f75ce3cfbc547c277d6f39b90ceb81f8ebd53e63','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:40:37','2026-04-30 22:40:37','2026-04-30 22:40:37','2026-04-30 22:40:37'),
(20,132,'247d2efca64e2f412092eaeeaedd0cc0c8c5e81848bb3b27738455015587a2ce','139.135.200.210','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:45:35','2026-04-30 22:45:35','2026-04-30 22:45:35','2026-04-30 22:45:35'),
(21,125,'b26f5f3f094d20b13c1c24aa3de9948aca66453367f4157da5a458d10c20da7d','139.135.75.246','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-04-30 22:59:40','2026-04-30 22:59:40','2026-04-30 22:59:40','2026-04-30 22:59:40'),
(22,125,'da3aaccbcaeb33ec785a98c30532e2386c483b99247d6dd54a3604162afd2cb5','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:42:29','2026-05-01 12:42:29','2026-05-01 12:42:29','2026-05-01 12:42:29'),
(23,129,'22ebb734d1c70f9316758dfd765370c483fbdde5613da160cc5f5517425c8e79','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 12:57:25','2026-05-01 12:57:25','2026-05-01 12:57:25','2026-05-01 12:57:25'),
(24,133,'5294765afe57c6a0bd501d44d59573390b0c6b663eaadb3cd79a5b9532641ac8','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 13:06:18','2026-05-01 13:06:18','2026-05-01 13:06:18','2026-05-01 13:06:18'),
(25,125,'ff34c384dc370584fa3d2baac91c9d6b0ea8c61aff432312ed1df0b8d148e093','103.148.60.153','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 17:24:57','2026-05-01 17:24:57','2026-05-01 17:24:57','2026-05-01 17:24:57'),
(26,125,'ef4b0167ad30fe8806a0fae86a1571cb6c4bb7c0c9882750e495f4d3da3f16a1','122.54.198.253','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-01 18:37:06','2026-05-01 18:37:06','2026-05-01 18:37:06','2026-05-01 18:37:06'),
(27,125,'9b640219366e232b559a2dfc911342234e79add0260fe187f2d09d56378592dd','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 09:45:39','2026-05-02 09:45:39','2026-05-02 09:45:39','2026-05-02 09:45:39'),
(28,125,'b520f86ab98deb07bed4b6642013021793c4c8c0f24c46f3746081417d549e3e','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 14:30:50','2026-05-02 14:30:50','2026-05-02 14:30:50','2026-05-02 14:30:50'),
(29,125,'193b68f44c0c34faeeb3417c84843bfe063e8bba78e9673d54f68a462550b5b7','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 17:00:36','2026-05-02 17:00:36','2026-05-02 17:00:36','2026-05-02 17:00:36'),
(30,125,'525a7a7183e38b515924f6524d69174193d178a218a2348d8852d21fedf76e55','103.148.60.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-02 20:08:10','2026-05-02 20:08:10','2026-05-02 20:08:10','2026-05-02 20:08:10'),
(31,125,'ec8f00bb09e2cf60b4f12a872feaea27a7acdcc06db21612f76e8ea5dd3bbdaf','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 01:07:55','2026-05-03 01:07:55','2026-05-03 01:07:55','2026-05-03 01:07:55'),
(32,125,'7abb98bd169a9567990f8196e5b17548e57fcf977cfba83765479294a0f9790d','180.195.65.92','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 01:40:46','2026-05-03 01:40:46','2026-05-03 01:40:46','2026-05-03 01:40:46'),
(33,125,'5d6de106985a157204da55d0784c35a132efad5353f88faa93e075012d476191','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 03:00:07','2026-05-03 03:00:07','2026-05-03 03:00:07','2026-05-03 03:00:07'),
(34,125,'00b8aa90120f4712dab413fffdb75a6342216681810be26cf8a20c764ee9f6cc','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 03:21:21','2026-05-03 03:21:21','2026-05-03 03:21:21','2026-05-03 03:21:21'),
(35,125,'e54818bb92bab4862ec4c9802b386a42fcb712c20fde4157e68bcdce192473c5','136.158.67.35','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36',NULL,'2026-05-03 03:49:59','2026-05-03 03:49:59','2026-05-03 03:49:59','2026-05-03 03:49:59'),
(36,125,'8f9ef37b49c14d549d6a7cc5094bec2ed46077c4cd3ee57fc0db3bb5ad98014d','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36',NULL,'2026-05-03 11:44:15','2026-05-03 11:44:15','2026-05-03 11:44:15','2026-05-03 11:44:15'),
(37,129,'927ac2737c4db1c14805d892cec8a1d5aed5c0e775d5c911b93f73a9f098f710','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 11:46:46','2026-05-03 11:46:46','2026-05-03 11:46:46','2026-05-03 11:46:46'),
(38,137,'21db9c036c3ab2dffca8537968fa089c8de46affbd1fc2a978cf57c2b1f7db07','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 11:49:40','2026-05-03 11:49:40','2026-05-03 11:49:40','2026-05-03 11:49:40'),
(39,125,'8b7f0b65d62b8b3a83af0363f03976f8679eb8a85341fc0940b0e535c136d10b','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 12:45:37','2026-05-03 12:45:37','2026-05-03 12:45:37','2026-05-03 12:45:37'),
(40,125,'b6e5fe2e74a45ddd9410938cc71b668fdc99121f021ceb6c27ee7d4b62d88049','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-03 22:00:24','2026-05-03 22:00:24','2026-05-03 22:00:24','2026-05-03 22:00:24'),
(41,125,'4ff65e01883d25950f1cfd9f4eb851c5bfafaa39d140450f74d2dc794cdec0d7','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 02:18:33','2026-05-04 02:18:33','2026-05-04 02:18:33','2026-05-04 02:18:33'),
(42,125,'ecbf69b2827e04f96ffef2e85a248f3deb860f8cb29505f2711546eff50ae9e5','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 03:21:08','2026-05-04 03:21:08','2026-05-04 03:21:08','2026-05-04 03:21:08'),
(43,130,'ee6b0ef03ad7c5707ff5b66f92228f88edd9725910a9a70a42c79e319634aa51','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 03:48:30','2026-05-04 03:48:30','2026-05-04 03:48:30','2026-05-04 03:48:30'),
(44,130,'2588618521c7c312925e81e898cf494755bae7509adc2264b75be067bbc5e182','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 07:46:38','2026-05-04 07:46:38','2026-05-04 07:46:38','2026-05-04 07:46:38'),
(45,129,'6df63abaa5d8da9451e5ae968a0c5a4c4b3c7af175d35cc67a92adbff8abefd8','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 08:03:50','2026-05-04 08:03:50','2026-05-04 08:03:50','2026-05-04 08:03:50'),
(46,129,'fc5f4578afa263a4f5c26132d7feae9549e0b4f1e5e449ace284e95ef0d2c05a','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 13:30:08','2026-05-04 13:30:08','2026-05-04 13:30:08','2026-05-04 13:30:08'),
(47,131,'ccad6020ee2f02b8e545b9d8773feb0bb226b83e7d96aaaf4499fa30931ec4a8','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 13:31:36','2026-05-04 13:31:36','2026-05-04 13:31:36','2026-05-04 13:31:36'),
(48,125,'8bbb1965f10efb11fcf44a0f5e64f401d5e31a53414fa97c4d74f41e9b0d090d','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 14:15:03','2026-05-04 14:15:03','2026-05-04 14:15:03','2026-05-04 14:15:03'),
(49,129,'0f151674e8f414e4e5894a24a11d20ec3907ed9cd8430948fd65bcd13a7ffdce','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-04 20:29:02','2026-05-04 20:29:02','2026-05-04 20:29:02','2026-05-04 20:29:02'),
(50,125,'7d50d0f52cc0bce53b4101c60c92f7aed086e8fbbf97387075310f6716f2feb0','175.176.52.6','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-05 09:08:49','2026-05-05 09:08:49','2026-05-05 09:08:49','2026-05-05 09:08:49'),
(51,125,'144dbf8af7fc5cf38f8344ad65babac5eac560e25a4d7f002f3fa59f61176379','209.35.171.228','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0',NULL,'2026-05-05 10:42:11','2026-05-05 10:42:11','2026-05-05 10:42:11','2026-05-05 10:42:11'),
(52,125,'198b5055a27f5d140dffa23d2c038d4c05d79de59376de1db6d5ee6b9fa18f9a','136.158.67.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-06 11:30:14','2026-05-06 11:30:14','2026-05-06 11:30:14','2026-05-06 11:30:14'),
(53,125,'91b5ccd2645afeb68dc6104dc378744b925fa41c53b02936c40c991c3286aeac','2001:fd8:cb6a:fb00:cb2:3f7b:207d:7339','Mozilla/5.0 (Linux; Android 12; itel S665L Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36',NULL,'2026-05-06 14:47:19','2026-05-06 14:47:19','2026-05-06 14:47:19','2026-05-06 14:47:19'),
(54,125,'1c88f87b4ddafe78abcfc4fa53cea8667ddf1ab2903fcd3e92629bdbfd5adcee','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 14:59:12','2026-05-06 14:59:12','2026-05-06 14:59:12','2026-05-06 14:59:12'),
(60,125,'f68df75bd9a010b3f28bc46f80b586daa8ea5b7c67a215aef4c046f43315ee0c','2001:fd8:cb6a:fb00:cb2:3f7b:207d:7339','Mozilla/5.0 (Linux; Android 12; itel S665L Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.111 Mobile Safari/537.36',NULL,'2026-05-06 15:48:37','2026-05-06 15:48:37','2026-05-06 15:48:37','2026-05-06 15:48:37'),
(61,125,'dc5711dcef8e6af6ee1e776e18fa8f129c9a7337d475b796a63f9ddba3d1a299','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 17:47:08','2026-05-06 17:47:08','2026-05-06 17:47:08','2026-05-06 17:47:08'),
(62,125,'6b7f284f3fe430b89e26117abc5ced86c38f618173145c59adf7032233e0b09d','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 17:54:59','2026-05-06 17:54:59','2026-05-06 17:54:59','2026-05-06 17:54:59'),
(63,125,'8a2218ae9befea8f6c5edf5355e7f37321750c3fbaa6b5759366dc3238675ad4','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:02:43','2026-05-06 18:02:43','2026-05-06 18:02:43','2026-05-06 18:02:43'),
(64,125,'4472674d014632062d4fa22dd8dd5907b4432dbfbc597d22500afbeb7a282316','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:08:55','2026-05-06 18:08:55','2026-05-06 18:08:55','2026-05-06 18:08:55'),
(65,125,'729d366526076cbfc0168e8cbf32c8d953d409eb19d6ad18b2658e88a8848514','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:16:13','2026-05-06 18:16:13','2026-05-06 18:16:13','2026-05-06 18:16:13'),
(66,125,'b7cecb4efe8cc3de2fc9dcabbd55e1a72107bc3221443695808c03f9a8e4799f','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:17:58','2026-05-06 18:17:58','2026-05-06 18:17:58','2026-05-06 18:17:58'),
(67,125,'fe4e5850643b370d10878aef5155a34b0a806040f8e35586499934f67fd4ec4a','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:27:58','2026-05-06 18:27:58','2026-05-06 18:27:58','2026-05-06 18:27:58'),
(68,125,'0dc6b3bca8b1276098c4b029cbb3899493bf273af27f3313b42af17e06bebb56','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 18:51:37','2026-05-06 18:51:37','2026-05-06 18:51:37','2026-05-06 18:51:37'),
(69,125,'12cf9efd0e3da0f7f1b928babbd2835535785b9dcb780c4cedd31a6f1fda067d','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 19:15:43','2026-05-06 19:15:43','2026-05-06 19:15:43','2026-05-06 19:15:43'),
(70,125,'0c20e04dd0508ae88c244445bb07c106ec9034fa74d70a2f8e618d2b39219ee7','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 19:20:11','2026-05-06 19:20:11','2026-05-06 19:20:11','2026-05-06 19:20:11'),
(71,125,'fb76620106ace37cec477f395f98d7e6cbf4992cbd8e0a494cc30ddc82641e61','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 19:54:41','2026-05-06 19:54:41','2026-05-06 19:54:41','2026-05-06 19:54:41'),
(72,125,'9efb094d07f2e8b0c4e9416269d04611465ea8d1af08f2844902ce12e1c950d2','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 20:02:41','2026-05-06 20:02:41','2026-05-06 20:02:41','2026-05-06 20:02:41'),
(73,125,'ea2aee8fb1d80fe850669c5958bedc756c66922a48989e038b01ecf85a2856d2','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 20:14:38','2026-05-06 20:14:38','2026-05-06 20:14:38','2026-05-06 20:14:38'),
(74,125,'00b74bd8db977c5201514f6ea8199008e1142ed1d399761888c335268975cba4','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 20:26:17','2026-05-06 20:26:17','2026-05-06 20:26:17','2026-05-06 20:26:17'),
(75,125,'e77ba2b7ef1da6bd78f7980e408cfafa4ca311a6adeaec6a7fc96314206ab761','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 20:45:53','2026-05-06 20:45:53','2026-05-06 20:45:53','2026-05-06 20:45:53'),
(76,125,'7766f9391518d3d8498f419e5d5871ef0ab18adfddc4806affe88a11677dce5a','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 21:41:10','2026-05-06 21:41:10','2026-05-06 21:41:10','2026-05-06 21:41:10'),
(77,125,'cb6bca80521e472fe4e450267551271109f16419e5ddb28051d9743503a3a787','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 21:52:58','2026-05-06 21:52:58','2026-05-06 21:52:58','2026-05-06 21:52:58'),
(78,125,'592e482b02a8f9f6344c0e9b09e3237a7c59c6552c624e385fee02873d04024c','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 22:43:04','2026-05-06 22:43:04','2026-05-06 22:43:04','2026-05-06 22:43:04'),
(79,125,'2eb363816de3919a7133dee805e8e7d49adcc41583eba570b62749cf177805bb','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 22:47:10','2026-05-06 22:47:10','2026-05-06 22:47:10','2026-05-06 22:47:10'),
(80,125,'5a3f3006a178b0021f73bb7744f58b7a713b341ab8c82172ecb434597893fdc7','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 22:59:28','2026-05-06 22:59:28','2026-05-06 22:59:28','2026-05-06 22:59:28'),
(81,125,'092c5d5e15b2980c38bd534e4fbae4965f0aba7761cbe2375ef1cc26a0319467','136.158.67.35','Mozilla/5.0 (Linux; Android 13; RMX3430 Build/SP1A.210812.016; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-06 23:15:02','2026-05-06 23:15:02','2026-05-06 23:15:02','2026-05-06 23:15:02'),
(82,125,'0a9c3acc10de49470f6a361ea9ee6f65a2f89573214001a32f440fe0f1e840b6','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:00:50','2026-05-07 00:00:50','2026-05-07 00:00:50','2026-05-07 00:00:50'),
(83,125,'eff68358b1a4e248babfdca22dec97a529213b67c1f7fddcef7409fea2103088','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:14:00','2026-05-07 00:14:00','2026-05-07 00:14:00','2026-05-07 00:14:00'),
(84,125,'c12c1dcff543df106858764ea042409c66868e40030103a983e5390ce4347d8d','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:19:04','2026-05-07 00:19:04','2026-05-07 00:19:04','2026-05-07 00:19:04'),
(85,125,'d93eb24321f9a667acf797b432f926cad1c565fd2de84f580b10ff6f93b91e01','136.158.67.35','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:42:48','2026-05-07 00:42:48','2026-05-07 00:42:48','2026-05-07 00:42:48'),
(86,125,'2a8aea8ce0115c4d2bdcd26741db219b107aefbbaa4d38afc1aecb23269391e4','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:50:43','2026-05-07 00:50:43','2026-05-07 00:50:43','2026-05-07 00:50:43'),
(87,125,'60ea15eb08a456526def3243cbae528218a96b9b9272da01f1d6f60c4ca5dd16','175.176.52.169','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 00:54:20','2026-05-07 00:54:20','2026-05-07 00:54:20','2026-05-07 00:54:20'),
(88,125,'0393e2a899e0ce2501bec93eb18a523f0c46cfa237db180e78d04ebc056b150f','2a09:bac5:3989:2646::3d0:3','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 01:34:29','2026-05-07 01:34:29','2026-05-07 01:34:29','2026-05-07 01:34:29'),
(89,125,'cf7f97789d855441f44177873728e7bd2a1501aafbd0c6f79f9403d9103fb46a','175.176.53.12','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 01:44:33','2026-05-07 01:44:33','2026-05-07 01:44:33','2026-05-07 01:44:33'),
(90,125,'a1c43eeee85d44ac2d35c1a87df1fd86f9a8828bb6030b0c1a22d7c8d5a5a81e','175.176.53.12','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 01:45:21','2026-05-07 01:45:21','2026-05-07 01:45:21','2026-05-07 01:45:21'),
(91,125,'04130845c366ca475f8e3ee30ac0be47532ff46ff1fd366686951401aacd9c2f','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:12:00','2026-05-07 02:12:00','2026-05-07 02:12:00','2026-05-07 02:12:00'),
(92,125,'a91fb1106e68d74828bcc6383fd045e4730f6f85e5dc60c1c3a33ccf379c4417','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:15:06','2026-05-07 02:15:06','2026-05-07 02:15:06','2026-05-07 02:15:06'),
(93,125,'7fb1d2648788b0535e2deea3c07a8592847a5fee6ff1c551917a3ae991e816ea','2a09:bac5:398c:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:27:11','2026-05-07 02:27:11','2026-05-07 02:27:11','2026-05-07 02:27:11'),
(94,125,'21f042640d7dc85ad8045ecb07cee1f69604f445d54ca44010795e16cccd7486','2a09:bac5:3988:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:33:51','2026-05-07 02:33:51','2026-05-07 02:33:51','2026-05-07 02:33:51'),
(95,125,'f72702b6e354ec1b79b8060c3876fea86f465a11b9b6c62a879bd0c7ff9d01e1','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:40:24','2026-05-07 02:40:24','2026-05-07 02:40:24','2026-05-07 02:40:24'),
(96,125,'f771a2c1a3287a0929b6779a3334f2ba7164c7acf6b1032206e3ee911d24e061','2a09:bac5:398d:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:41:28','2026-05-07 02:41:28','2026-05-07 02:41:28','2026-05-07 02:41:28'),
(97,125,'46c66aca40a8b61081e9e7ddba01cfa0d2aabf737c670704d9bcd26bb3e11b4b','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:43:51','2026-05-07 02:43:51','2026-05-07 02:43:51','2026-05-07 02:43:51'),
(98,125,'8362418ffe98f5a6662594dce939c21baf914bf4c10460ab31949ec9b30aeefa','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:48:28','2026-05-07 02:48:28','2026-05-07 02:48:28','2026-05-07 02:48:28'),
(99,125,'b51b3a3f06863da2093ac0316c87ee1944119e79e323aaa057c03f52eec26d5e','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:50:56','2026-05-07 02:50:56','2026-05-07 02:50:56','2026-05-07 02:50:56'),
(100,125,'125f05f0067b5afd316ba148128d3f89b461e98f056227ed5ceb7cf0b7dc9ace','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:54:50','2026-05-07 02:54:50','2026-05-07 02:54:50','2026-05-07 02:54:50'),
(101,125,'befd9f7ef72e887a105b0bb30796b4c1d6dfe89c6988a11cdddd74c3f2b0bed0','2a09:bac1:3180:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:57:35','2026-05-07 02:57:35','2026-05-07 02:57:35','2026-05-07 02:57:35'),
(102,125,'981828d59a0c4030efd4b54660e2b535a35e57ef779c477e19a3471ce0458633','2a09:bac5:398b:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 02:58:25','2026-05-07 02:58:25','2026-05-07 02:58:25','2026-05-07 02:58:25'),
(103,125,'c565ae56cf21442e6c59dfd758200e01ac470e4743d977e2c9eed88b6b388433','2a09:bac1:31e0:8::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 03:03:26','2026-05-07 03:03:26','2026-05-07 03:03:26','2026-05-07 03:03:26'),
(104,125,'ded228b9c20b8a79221d8e9a596851145829fb74fecfc36e6898af3fed2c165e','2a09:bac5:3988:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 03:06:36','2026-05-07 03:06:36','2026-05-07 03:06:36','2026-05-07 03:06:36'),
(105,125,'c6383a34de2af373a256beede226f69dff7210296260301ce0167f703c42fbaa','2a09:bac5:3989:16dc::247:ce','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-07 03:08:24','2026-05-07 03:08:24','2026-05-07 03:08:24','2026-05-07 03:08:24'),
(106,125,'b54bb2e17d33e1a5ed5481baa5ce42ccdc3f9b1358f4a3e295f8e1d112131080','2001:fd8:cb6a:fb00:ddd0:7e29:e222:9556','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0',NULL,'2026-05-07 21:56:59','2026-05-07 21:56:59','2026-05-07 21:56:59','2026-05-07 21:56:59'),
(109,125,'6bb0cfdca40ae0af1ad9d2b1f7c8ed10b7a112d6d5efdb84b6ef3b9a1ed8f28f','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-08 10:21:46','2026-05-08 10:21:46','2026-05-08 10:21:46','2026-05-08 10:21:46'),
(110,125,'67a4230fee2615f350612ae16d14dd3849f783268fbd8e0899fc8dd88542f5f5','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-08 12:02:38','2026-05-08 12:02:38','2026-05-08 12:02:38','2026-05-08 12:02:38'),
(111,125,'d4481eb3f29adf8b846eaa6b5b942a022e196319c68ad6b3100c78136142b87e','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-08 14:23:16','2026-05-08 14:23:16','2026-05-08 14:23:16','2026-05-08 14:23:16'),
(112,125,'800951e5af694e843f961cce79ea30e9f9d671fc6f7402d6d0d23124896b75e9','136.158.67.252','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',NULL,'2026-05-08 17:27:56','2026-05-08 17:27:56','2026-05-08 17:27:56','2026-05-08 17:27:56'),
(113,125,'e21a291472451bd904875d444c74adb957e2608b0d80a1f46337a2b57354eaa2','136.158.67.252','Mozilla/5.0 (Linux; Android 15; CPH2531 Build/AP3A.240617.008; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/147.0.7727.137 Mobile Safari/537.36',NULL,'2026-05-08 18:49:28','2026-05-08 18:49:28','2026-05-08 18:49:28','2026-05-08 18:49:28'),
(127,160,'beb05bfa66ca773f88d1a106453e23328d9de8c3e6df6b6880766d4d90341dac','112.201.201.169','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 01:08:50','2026-05-20 01:08:50','2026-05-20 01:08:50','2026-05-20 01:08:50'),
(128,160,'817d91310533a9f98a6475d001c798cf818b0ecbd6169f06a72ca814b91fff7b','49.144.210.255','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-05-20 18:24:21','2026-05-20 18:24:21','2026-05-20 18:24:21','2026-05-20 18:24:21'),
(130,125,'def6c866154c2beb362c5335e8e71d790e8f3adbc9e164364703c241ff054b4b','112.208.168.90','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-09 19:04:21','2026-06-09 19:04:21','2026-06-09 19:04:21','2026-06-09 19:04:21'),
(132,160,'26a43ea018e1f3272bd7aae925a41c558c2ca5f3f1170dc34154d86f4d7f8741','222.127.153.193','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 06:36:38','2026-06-10 06:36:38','2026-06-10 06:36:38','2026-06-10 06:36:38'),
(133,125,'386bef12be58c73d4d62283a2db8bc2d42a1b3fd88114e1b651366f24483d908','2405:8d40:4881:e55b:18a:b35e:bdba:bc80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36',NULL,'2026-06-10 10:01:51','2026-06-10 10:01:51','2026-06-10 10:01:51','2026-06-10 10:01:51'),
(136,163,'3a4ea15c767edc8caa935787d59f7784f158f228f555b2aa7ff97be7570b2717','2001:fd8:e240:2100:b485:848c:f33:cff6','Dalvik/2.1.0 (Linux; U; Android 12; itel S665L Build/SP1A.210812.016)',NULL,'2026-06-20 17:00:14','2026-06-20 17:00:14','2026-06-20 17:00:14','2026-06-20 17:00:14'),
(137,125,'c00eb45f9d4619c084437cf0835c9ffc972512545aa89d37924489030b0e9e8b','111.90.198.145','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36',NULL,'2026-06-21 10:01:52','2026-06-21 10:01:52','2026-06-21 10:01:52','2026-06-21 10:01:52'),
(138,130,'9ff2194a0034dfca917e303894e6a221b6486cce822c49cfbcc7c0c4abed0b28','111.90.198.145','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36',NULL,'2026-06-21 10:09:24','2026-06-21 10:09:24','2026-06-21 10:09:24','2026-06-21 10:09:24'),
(139,125,'9d359d647133fbd6e6a3c9297e4de10fbf8a8afa5e98fe83a3d721faf0bbe035','136.158.66.58','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-06-22 16:24:10','2026-06-22 16:24:10','2026-06-22 16:24:10','2026-06-22 16:24:10'),
(140,164,'8d17ca0709e64afd639744000b9498626907603fc19063c1de45cef9bbce5b22','180.195.70.222','Dalvik/2.1.0 (Linux; U; Android 16; HEY3-W00 Build/HONORHEY3-W09)',NULL,'2026-06-24 20:10:35','2026-06-24 20:10:35','2026-06-24 20:10:35','2026-06-24 20:10:35');
/*!40000 ALTER TABLE `user_verified_browsers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'Unknown User',
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pending_email` varchar(191) DEFAULT NULL,
  `email_change_token` varchar(191) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `middle_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `otp_code` varchar(191) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(50) DEFAULT 'staff',
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `allowed_pages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_pages`)),
  `is_active` tinyint(1) DEFAULT 1,
  `is_disabled` tinyint(1) NOT NULL DEFAULT 0,
  `disable_reason` text DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `temp_password` varchar(191) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `fcm_token` varchar(191) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `profile_image` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(18,'Unknown User','manager-sunibertson','sonysunico02@gmail.com',NULL,NULL,'$2y$10$CVq1neYKgTLqg1FVSdDMkeXzseBwPfATvRzONK3LNi3rjbYQGCqau','sunibertson R. sunico','sunibertson','roncesvalles','sunico',NULL,'09153520035','858881','2026-05-03 18:10:31','2026-04-08 02:48:20',1,'Developer','approved',NULL,NULL,'[\"dashboard\",\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"boundaries.*\",\"office-expenses.*\",\"salary.*\",\"salaries.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"live-tracking.*\",\"analytics.*\",\"unit-profitability.*\",\"decision-management.*\",\"staff.*\",\"archive.*\",\"boundary-rules.*\",\"spare-parts.*\",\"suppliers.*\"]',1,0,NULL,0,NULL,NULL,NULL,'2026-04-07 23:51:23','2026-05-05 05:46:48','$2y$10$JNcE.e3Q3m/yOi7.T01wS.kX3qzNqo2pbFo/zmGF5eDdDFvVmDgg.','dHh7shV7NYLk6jWk8Ii2E1HZ3dWDETMq7AEofPq7PJYsiETEHmPDlNNCB4yi',NULL,'2026-04-30 07:40:58',NULL,'2026-05-05 05:46:48'),
(125,'Unknown User','super_admin-robert','robertgarcia.owner@gmail.com',NULL,NULL,'$2b$10$g.1WwatD50Ijf2q8ER3NVeXAvYu8UFmVoUtyoBQZjzOBdEcRpQFYW','Robert Garcia','Robert',NULL,'Garcia',NULL,NULL,NULL,NULL,NULL,1,'super_admin','approved',NULL,NULL,NULL,1,0,NULL,0,NULL,NULL,NULL,'2026-04-27 01:26:36','2026-06-22 16:24:10','$2b$10$g.1WwatD50Ijf2q8ER3NVeXAvYu8UFmVoUtyoBQZjzOBdEcRpQFYW','1APTG3SWZVBbnJlu3NAoWdKHCyu6UNnew6R8xjdw86H3kdm2VcU7wK6SMhP2','MOCK_CPH2531_1779211028','2026-06-22 16:24:10',NULL,NULL),
(129,'shiella marie orilla','shiellamarieorilla428','shiellamarie.sec@gmail.com',NULL,NULL,'$2y$10$UJvc3MmyfpinVy9klR63gOcFvqarAPpe0AVPHWjMusdKbLSZGFnlO','shiella marie orilla','shiella marie',NULL,'orilla',NULL,NULL,'320366','2026-05-12 21:09:27',NULL,1,'secretary','approved',NULL,NULL,'[\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"boundary-rules.*\",\"staff.*\",\"archive.*\"]',1,0,NULL,0,NULL,NULL,NULL,'2026-04-30 10:20:22','2026-05-12 20:59:27','$2y$10$HY51COxlnoHwRYkvJ.wsL.Y3ho9Qz/mzSMAfES3vW6G6.MomHYWRK','ZBwUTnETdyF2wMhHAp9UdMSG8APibSiAGESy3YRh3aBmsdGTNuuEwZShe7l7',NULL,'2026-05-04 20:29:02',NULL,NULL),
(130,'rea remitra','rearemitra179','remitra.manager1@gmail.com',NULL,NULL,'$2y$10$gsYbUEmFaEdBTvdK2nzkPurYB2vkXrReewEX.Q7x.W7sjV1hdH7PK','rea remitra','rea',NULL,'remitra',NULL,NULL,NULL,NULL,NULL,1,'manager','approved',NULL,NULL,'[\"dashboard\",\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"live-tracking.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"salary.*\",\"boundary-rules.*\",\"decision-management.*\",\"staff.*\",\"archive.*\",\"analytics.*\",\"unit-profitability.*\"]',1,0,NULL,0,NULL,NULL,NULL,'2026-04-30 10:21:08','2026-06-21 10:09:24','$2y$10$HtU4GRVCub3.ea7xQ6oktONt6m6rGjzW0DBd4id7AbAzDr2VHo0hK','HGw533BEH9T0X0RprTIDkiWFsQaLSEL7RFyYxIyzudpnMN7ountuc1CixHAl',NULL,'2026-06-21 10:09:24',NULL,NULL),
(131,'Romy Thomas','romythomas658','Romy.dispatcher1@gmail.com',NULL,NULL,'$2y$10$AFJm8NznXINElSV2g.fzcOIOJAuNLIk4ZedqlC4KggpIWlz55ocsy','Romy Thomas','Romy',NULL,'Thomas',NULL,NULL,NULL,NULL,NULL,1,'dispatcher','approved',NULL,NULL,'[\"units.*\",\"driver-management.*\",\"live-tracking.*\",\"coding.*\"]',1,0,NULL,0,NULL,NULL,NULL,'2026-04-30 10:21:40','2026-05-04 13:31:36','$2y$10$.8cdVqGYB1/Auw5nIpgxH.dw3y1AR4jlLazzuGzGcz4/JH9kzXiXu','0x4gkwwKTlHVzKm5OFsxLvq4z8iWYIImQ9ub2X0g7gBvWDTKoIX7GGPNzqEA',NULL,'2026-05-04 13:31:36',NULL,NULL),
(132,'yana santiago','yanasantiago471','dianesantiago879@gmail.com',NULL,NULL,'$2y$10$.3v4lzShjW8XP2A7z82xzuvZIFLeuL.Wmrza5ECPpWcRyd/00ko9i','yana santiago','yana',NULL,'santiago',NULL,'09158112931',NULL,NULL,NULL,1,'manager','approved',NULL,NULL,'[\"live-tracking.*\"]',1,0,NULL,0,NULL,NULL,'labuin','2026-04-30 22:28:25','2026-05-05 05:47:00','$2y$10$79ALaabNwHxJkCbM6iTB/OR6FoXY3Lf49Y9IPw.bJtGYGY/3/Iebu','I07BfXgCU7rv0FTPLLmGGSt2PFk0reJ4KqlwZxqzVbjVmpxCJNf0Z9Ceuraw',NULL,'2026-04-30 22:45:35',NULL,'2026-05-05 05:47:00'),
(133,'Ria Jane Perocho','riajaneperocho280','perochoriajane4@gmail.com',NULL,NULL,'$2y$10$SNe7DpyeayNbgQmulJfhEO1HkaDZ5OWPapDyeytOOIIzGHcQt8.xa','Ria Jane Perocho','Ria Jane',NULL,'Perocho',NULL,'+639814444055',NULL,NULL,NULL,1,'dispatcher','approved',NULL,NULL,NULL,1,0,NULL,0,NULL,NULL,'0049 Liwag st','2026-05-01 13:05:18','2026-05-05 05:46:37','$2y$10$pbY6Q7bTF8yUickJczl0oeZQn25YhPCstvUUf5PPNOgvSNrFypwn.',NULL,NULL,'2026-05-01 13:08:23',NULL,'2026-05-05 05:46:37'),
(134,'angela San Victores','angelasanvictores836','angelasanvictores2005@gmail.com',NULL,NULL,'$2y$10$qXEB6caJcLoGzlo1ev2DsekhlBPc8BetWbgMRYSWHzjzobd5UjE0y','angela San Victores','angela',NULL,'San Victores',NULL,NULL,'995273','2026-05-02 16:51:18',NULL,1,'secretary','approved',NULL,NULL,NULL,1,0,NULL,1,'UVY487#',NULL,NULL,'2026-05-02 16:09:28','2026-05-02 19:44:39','$2y$10$2YHAr/kX4XmxnGv2Y5vAjuqtFUcUw1JpXqm7cI3gH2dO8hR1MPLPO',NULL,NULL,NULL,NULL,'2026-05-02 19:44:39'),
(135,'Ria Jane Perocho','riajaneperocho271','perochoriajane065@gmail.com',NULL,NULL,'$2y$10$vvWDNPOvXhUMqIHuXF73RuPaW3ZKKSz3BvQxHzFz/RK72zL8l/256','Ria Jane Perocho','Ria Jane',NULL,'Perocho',NULL,'+639814444055',NULL,NULL,NULL,1,'secretary','approved',NULL,NULL,NULL,1,0,NULL,1,'NVY803@',NULL,'0049 Liwag st','2026-05-02 19:42:55','2026-05-02 21:10:21','$2y$10$tcJCvz97dNUOqSXr72vpGefiDmaVYY6SXgNyYlGR74a.bUxraxzIa',NULL,NULL,NULL,NULL,'2026-05-02 21:10:21'),
(136,'Ria Jane JANEEEEEEEEEEEEEEEEEE','riajanejaneeeeeeeeeeeeeeeeee726','criztelperocho@gmail.com',NULL,NULL,'$2y$10$yWY0/YHCu45O/dW2IbuWRuZKOTuwhZOG45r6.WcYG/4SKkm.c62lW','Ria Jane JANEEEEEEEEEEEEEEEEEE','Ria Jane',NULL,'JANEEEEEEEEEEEEEEEEEE',NULL,'09814444055',NULL,NULL,NULL,1,'dispatcher','approved',NULL,NULL,NULL,1,0,NULL,1,'YUJ390!',NULL,'0049 Liwag stNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN_NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN','2026-05-02 21:02:06','2026-05-04 08:16:59','$2y$10$ylqvchcNWbkeSTzQKNXU.erq3148ui/aSu7RCcHxT5EE/7t1OmoFG',NULL,NULL,NULL,NULL,'2026-05-04 08:16:59'),
(137,'Clark Tiquison','clarktiquison603','clarkjasontiquison@gmail.com',NULL,NULL,'$2y$10$HrRNE7bYC7NMnKO3/OpYveeex7VZ9Mvk6dMXWIfXXqyBbYCxEvmXm','Clark Tiquison','Clark',NULL,'Tiquison',NULL,'9937831749',NULL,NULL,NULL,1,'secretary','approved',NULL,NULL,'[\"live-tracking.*\"]',1,0,NULL,0,NULL,NULL,'Barangay Tadlac Los Banos','2026-05-03 11:48:20','2026-05-05 05:46:29','$2y$10$jcy.nh7.ayp7qcYS.tpabebkhNO7yumVKKxk8Ncd.98JT9yPv6mNu','ixVYpEvAoilhSZ39AWGMCjuIH9ZOHvmaLXDdkI9oPa77pJYFea3rITSh2vhR',NULL,'2026-05-03 12:20:38',NULL,'2026-05-05 05:46:29'),
(138,'Gelatokisdinagbabayad Tulog','gelatokisdinagbabayadtulog944','sunicoq@gmail.com',NULL,NULL,'$2y$10$F8.AXkedOZ.U8EfLr0lwTuheU4NShr3nRnKKH5TwkZmkEkEPDuOcG','Gelatokisdinagbabayad Tulog','Gelatokisdinagbabayad',NULL,'Tulog',NULL,NULL,NULL,NULL,NULL,1,'dispatcher','approved',NULL,NULL,NULL,1,0,NULL,1,'ZTC283#',NULL,NULL,'2026-05-03 13:31:11','2026-05-03 13:31:32','$2y$10$ZesGqj2vrilqO1elWF2yVedlDCPm/Rw4ki5yXokLUUab0DLuBD2HS',NULL,NULL,NULL,NULL,'2026-05-03 13:31:32'),
(139,'Ria jane Perocho','riajaneperocho563','haha@gmail.comaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',NULL,NULL,'$2y$10$ah/Vc4s.G1Ud.WHbkIWSDuV98kndQ6dkKr8DNNTDT1nlJT/bjUOMe','Ria jane Perocho','Ria jane',NULL,'Perocho',NULL,'09814444055',NULL,NULL,NULL,1,'manager','approved',NULL,NULL,NULL,1,0,NULL,1,'HQX394$',NULL,'0049 Liwag st. Brgy Cabanbanan Pagsanjan Laguna','2026-05-04 08:10:06','2026-05-05 05:46:16','$2y$10$qE38GXqEksV76ceJowZdYuc6mmJPfN7LvlnqDDXM35/1dbf0MRTs2',NULL,NULL,NULL,NULL,'2026-05-05 05:46:16'),
(140,'PEPITO PEPITO','pepitopepito164','HA@GMAIL.COM',NULL,NULL,'$2y$10$Sp5SaYT06UMFWx.6nvTwrO5TNWph38.qDzwgGpGrV1wNBn/gfWv06','PEPITO PEPITO','PEPITO',NULL,'PEPITO',NULL,'09814444055',NULL,NULL,NULL,1,'dispatcher','approved',NULL,NULL,'[]',1,0,NULL,1,'MDK703!',NULL,'0049 Liwag st','2026-05-04 08:11:47','2026-05-05 02:23:56','$2y$10$kljH7GEDrbeV.GyzlF0GHOGxDtI80vi.EcXZlq6Va4LG.i1X71dye',NULL,NULL,NULL,NULL,'2026-05-05 02:23:56'),
(141,'ri po','ripo727','pepito@gmail.com',NULL,NULL,'$2y$10$GSBn4XPR6PzxNEK4SJaBVOEZpT6ePGZkjCPmFy1wUOn8VYucfZ.C2','ri po','ri',NULL,'po',NULL,'09814444055',NULL,NULL,NULL,1,'secretary','approved',NULL,NULL,'[]',1,0,NULL,1,'EGP114#',NULL,'0049 Liwag st','2026-05-04 08:14:11','2026-05-06 11:38:38','$2y$10$mDQtx1KuNlI0qdKjdDc//eQe0tFB10IMBP9RuyohRH6IFnh5NrnYq',NULL,NULL,NULL,NULL,'2026-05-06 11:38:38'),
(142,'Test Driver','testdriver','driver@test.com',NULL,NULL,'$2y$10$w2eHca8s3n6a.9jYWgQ9p.oMLZxybnf2Q8pAYgDhIaYtmdBBzawHK','Test Driver',NULL,NULL,NULL,NULL,NULL,'740359','2026-05-08 19:17:50',NULL,0,'driver','approved',NULL,NULL,NULL,1,0,NULL,0,NULL,'09090909090',NULL,'2026-05-07 08:50:58','2026-05-08 19:07:57','$2y$10$oBpKcHDFS3ZOA./Lt4kW3u59ixTUJDFMAAkNONC1pF3wWFj2rpd/a',NULL,NULL,NULL,NULL,'2026-05-08 19:07:57'),
(160,'Clark Tiquison','clarktiquison506','tiquisonclark@gmail.com',NULL,NULL,'$2y$10$qXwwbrLgnQftBK.sqpkM8eUGUBNUtFNAoGsmjUJqLUCwwJm89qTZy','Clark Tiquison','Clark',NULL,'Tiquison',NULL,'09937831749',NULL,NULL,NULL,1,'manager','approved',NULL,NULL,'[\"dashboard\",\"units.*\",\"driver-management.*\",\"activity-logs.*\",\"live-tracking.*\",\"maintenance.*\",\"coding.*\",\"driver-behavior.*\",\"spare-parts.*\",\"suppliers.*\",\"boundaries.*\",\"office-expenses.*\",\"salary.*\",\"boundary-rules.*\",\"decision-management.*\",\"staff.*\",\"archive.*\",\"support.*\",\"analytics.*\",\"unit-profitability.*\"]',1,0,NULL,0,NULL,NULL,'Bahay kubo','2026-05-19 21:42:29','2026-06-10 11:24:18','$2y$10$RnJFR4EvUj12VlIkTGov0eXBRMmxkXL5sLWPxv0/0c52yWGIwokWu','MwsAvtbELnzgLzBh9B3z1taVkA0VHcSrUeMLkdQFw6RXos6yONxkYzm1Dr5g',NULL,'2026-06-10 11:24:18',NULL,NULL),
(163,'joel sumando','joel.sumando33','rennelpasculado@gmail.com',NULL,NULL,'$2y$10$iybTCetuVNVXJebFvKjQ6OtfxSYavmqSdc7UM8VB6kOz5RImCL9Yi','Joel Sumando','joel',NULL,'sumando',NULL,NULL,NULL,NULL,NULL,1,'driver','approved',NULL,NULL,NULL,1,0,NULL,0,NULL,'09911275418',NULL,'2026-06-20 17:00:14','2026-06-25 17:55:50','$2y$10$1oU4aDLvaQ5lELWvoH4zXeX2UGMFD8E0hlA8BjlVeU9CS8sbA3qwW',NULL,'fUpmbjmbSzWCX8oUKeXcOV:APA91bE8xRuRD1QB4kg1GWDkizG8N7D5soaqwmOLdrrh2Se-c86K3P61o9TpVe8hQFDuctWieD0gJTa43NP3iUYPlWBgGsl-joc5GfMpND8-1jGB4WPVhRI',NULL,NULL,NULL),
(164,'Elmar Pabalate','elmar.pabalate62','lchar7581@gmail.com',NULL,NULL,'$2y$10$Zu4m8gmHdhjpfjE/E6uVoOxYf0F2dkiASj7RKoTOwiurh32giN0ni','Elmar Pabalate','Elmar',NULL,'Pabalate',NULL,NULL,NULL,NULL,NULL,1,'driver','approved',NULL,NULL,NULL,1,0,NULL,0,NULL,'09066740167',NULL,'2026-06-24 20:10:35','2026-06-24 12:10:35','$2y$10$ETNEvRgHiJfaoQhjOGEKF.1iHZ10Q0zWkOP15PesqOcAuv9SykKRG',NULL,'dtk3zwIySL-UuDpw-EZDFa:APA91bFNfFkOSk5gCNE22ZvfN4xh--Rf02qnFGLP2PyDufe7mnyHjwNqVS9b7OEiYdo-_fEzqdFCFdXW_pJic9HxvaeNWqm77sPdg7SLQsxj0L4NxzCGr-Y',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-25 11:37:07
