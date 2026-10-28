-- ================================================================
-- Student PG Management Module Schema (Yadav PG for Stay / Yadav.in)
-- Engineered by Yuvraj
-- ================================================================

CREATE TABLE IF NOT EXISTS `pg_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_number` varchar(50) NOT NULL,
  `floor` varchar(50) NOT NULL,
  `occupancy_type` enum('single','double','triple') NOT NULL DEFAULT 'double',
  `total_beds` int(11) NOT NULL DEFAULT 2,
  `monthly_rent` decimal(10,2) NOT NULL DEFAULT 10000.00,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_room` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_beds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `bed_label` varchar(10) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `status` enum('vacant','occupied','maintenance') NOT NULL DEFAULT 'vacant',
  PRIMARY KEY (`id`),
  KEY `fk_room` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `college` varchar(150) DEFAULT 'AS College Deoghar',
  `room_id` int(11) DEFAULT NULL,
  `bed_id` int(11) DEFAULT NULL,
  `rent_amount` decimal(10,2) NOT NULL DEFAULT 13000.00,
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT 13000.00,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_complaints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `status` enum('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_comp` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_vacate_intimations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `intimated_on` date NOT NULL,
  `vacating_on` date NOT NULL,
  `rent_pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comment` text NOT NULL,
  `status` enum('Pending Review','Approved','Settled') NOT NULL DEFAULT 'Pending Review',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_student_vac` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_kyc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `id_type` varchar(50) NOT NULL DEFAULT 'Aadhaar Card',
  `id_number` varchar(50) NOT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Verified',
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_kyc` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `importance` enum('normal','urgent') NOT NULL DEFAULT 'normal',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pg_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `month_for` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL DEFAULT 'UPI / GPay',
  `transaction_ref` varchar(100) NOT NULL,
  `status` enum('Paid','Pending') NOT NULL DEFAULT 'Paid',
  PRIMARY KEY (`id`),
  KEY `fk_student_pay` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================================================================
-- Seed Initial PG Data (Single, Double & Triple Sharing Rooms)
-- ================================================================

-- Rooms
INSERT IGNORE INTO `pg_rooms` (`id`, `room_number`, `floor`, `occupancy_type`, `total_beds`, `monthly_rent`) VALUES
(1, 'R-101', 'First Floor [Yadav PG for Stay]', 'single', 1, 16000.00),
(2, 'R-102', 'First Floor [Yadav PG for Stay]', 'double', 2, 12500.00),
(3, 'R-201', 'Second Floor [Yadav PG for Stay]', 'double', 2, 12500.00),
(4, 'R-202', 'Second Floor [Yadav PG for Stay]', 'triple', 3, 9500.00),
(5, 'R-301', 'Third Floor [Yadav PG for Stay]', 'single', 1, 16000.00),
(6, 'R-302', 'Third Floor [Yadav PG for Stay]', 'double', 2, 13000.00),
(7, 'R-406', 'Four Floor [Yadav PG for Stay]', 'double', 2, 13000.00),
(8, 'R-407', 'Four Floor [Yadav PG for Stay]', 'triple', 3, 9500.00);

-- Beds for Rooms
INSERT IGNORE INTO `pg_beds` (`id`, `room_id`, `bed_label`, `student_id`, `status`) VALUES
-- R-101 (Single)
(1, 1, 'Bed A', NULL, 'vacant'),
-- R-102 (Double)
(2, 2, 'Bed A', NULL, 'vacant'),
(3, 2, 'Bed B', NULL, 'vacant'),
-- R-201 (Double)
(4, 3, 'Bed A', NULL, 'vacant'),
(5, 3, 'Bed B', NULL, 'vacant'),
-- R-202 (Triple)
(6, 4, 'Bed A', NULL, 'vacant'),
(7, 4, 'Bed B', NULL, 'vacant'),
(8, 4, 'Bed C', NULL, 'vacant'),
-- R-301 (Single)
(9, 5, 'Bed A', NULL, 'vacant'),
-- R-302 (Double)
(10, 6, 'Bed A', NULL, 'vacant'),
(11, 6, 'Bed B', NULL, 'vacant'),
-- R-406 (Double - Yuvraj's Room)
(12, 7, 'Bed A', NULL, 'vacant'),
(13, 7, 'Bed B', 1, 'occupied'),
-- R-407 (Triple)
(14, 8, 'Bed A', NULL, 'vacant'),
(15, 8, 'Bed B', NULL, 'vacant'),
(16, 8, 'Bed C', NULL, 'vacant');

-- Seed Students: Including Yuvraj Yadav as in Screenshot 3
INSERT IGNORE INTO `pg_students` (`id`, `name`, `username`, `email`, `password`, `phone`, `college`, `room_id`, `bed_id`, `rent_amount`, `deposit_amount`, `joined_date`, `status`, `created_by`, `location`, `photo`) VALUES
(1, 'Yuvraj Yadav', 'yuvrajyadav734', 'yuvrajyadav734@gmail.com', '$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO', '8340195572', 'AI & Software Engineering Institute', 7, 13, 13000.00, 13000.00, '2026-07-18', 'active', 'Sumit Yadav', 'Hirna Near Tv relay Centre B. Deoghar Jharkhand 814112', 'yuvraj.png'),
(2, 'Aman Sharma', 'amansharma', 'aman.sharma@gmail.com', '$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO', '9835123450', 'AS College Deoghar', 2, 2, 12500.00, 12500.00, '2026-08-01', 'active', 'Sumit Yadav', 'Hirna Road Deoghar', 'default_student.png'),
(3, 'Rohan Verma', 'rohanverma', 'rohan.verma@gmail.com', '$2y$12$aGtvSK2CTkSHcA6kxJckFOhRVwUIWV4w9f8iScr3abAqXpLDykMhO', '9431188776', 'BIT Mesra Extension', 4, 6, 9500.00, 9500.00, '2026-08-15', 'active', 'Sumit Yadav', 'Hirna Road Deoghar', 'default_student.png');

-- Update Beds occupied by students
UPDATE `pg_beds` SET `student_id` = 1, `status` = 'occupied' WHERE `id` = 13;
UPDATE `pg_beds` SET `student_id` = 2, `status` = 'occupied' WHERE `id` = 2;
UPDATE `pg_beds` SET `student_id` = 3, `status` = 'occupied' WHERE `id` = 6;

-- KYC Record for Yuvraj Yadav
INSERT IGNORE INTO `pg_kyc` (`id`, `student_id`, `id_type`, `id_number`, `document_file`, `status`, `submitted_at`, `verified_at`) VALUES
(1, 1, 'Aadhaar Card', 'XXXX-XXXX-8492', 'aadhaar_sample.pdf', 'Verified', '2026-07-18 10:30:00', '2026-07-18 14:00:00');

-- Announcements for PG
INSERT IGNORE INTO `pg_announcements` (`id`, `title`, `content`, `importance`, `created_at`) VALUES
(1, 'High-Speed Wi-Fi Router Upgraded', 'New 300 Mbps fiber dual-band routers installed on all 4 floors. Wi-Fi SSID: YadavPG_5G. Password available at Warden reception desk.', 'normal', '2026-10-20 09:00:00'),
(2, 'Night Main Gate Closing Time (10:00 PM)', 'All residents must check in before 10:00 PM. In case of late return from coaching/college, please log late entry with Security Guard Ramesh.', 'urgent', '2026-10-22 18:30:00'),
(3, 'Drinking Water Purifier RO Filter Replaced', 'All 4 floors drinking water RO purifiers serviced and tested today with TDS 75.', 'normal', '2026-10-26 11:00:00');


-- Seed Payments
INSERT IGNORE INTO `pg_payments` (`id`, `student_id`, `month_for`, `amount`, `payment_date`, `payment_method`, `transaction_ref`, `status`) VALUES
(1, 1, 'August 2026', 13000.00, '2026-08-03 11:20:00', 'UPI / GPay', 'UPI/2608031120/YADAV', 'Paid'),
(2, 1, 'September 2026', 13000.00, '2026-09-02 16:45:00', 'UPI / PhonePe', 'UPI/2609021645/YADAV', 'Paid'),
(3, 1, 'October 2026', 13000.00, '2026-10-04 10:15:00', 'UPI / GPay', 'UPI/2610041015/YADAV', 'Paid');
