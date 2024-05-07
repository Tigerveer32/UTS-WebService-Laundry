-- Database: `db_laundry`

-- --------------------------------------------------------

--
-- Table structure for table `laundry_items`
--

CREATE TABLE `laundry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL,
  `price` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laundry_items`
--

INSERT INTO `laundry` (`id`, `item`, `price`) VALUES
(1, 'T-Shirt', 10000),
(2, 'Jeans', 15000),
(3, 'Socks', 5000),
(4, 'Towel', 12000),
(5, 'Bed Sheet', 20000);

