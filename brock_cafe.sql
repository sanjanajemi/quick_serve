-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 07:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brock_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(6) UNSIGNED NOT NULL,
  `email` varchar(25) NOT NULL DEFAULT 'Not Null',
  `password` varchar(100) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `name`) VALUES
(123123, 'admin12@gmail.com', '$2y$10$ze1NVZVwbUAe8Bb0.tUrVOh8R5yA4qdwJfiX/Ko0VikYTi2ZepWvC', 'admin'),
(123127, 'mahi@gmail.com', '$2y$10$3srHEJGk0asw6lfVp2pFHuQtoJT1/uhbUBHZea3LTLQ6dJOiUseD6', 'mahi'),
(123128, 'hisan@gmail.com', '$2y$10$mTMYDM2PlOXQwY50gDussu2Ceam7Jc6IqGb4zNB7Llt05ES54nn.2', 'Hisan'),
(123129, 'hisan@gmail.com', '$2y$10$SoHCWwWFaGr91zLYwgj.4.4KgMu2H.i8F59N5CXhO0N81SdRXdLc.', 'Hisan'),
(123130, 'sanjanaakter283@gmail.com', '$2y$10$88I0pRyRnS6fM/0WzNE1/.47r4N49EvPNXUQpRacm5imx/0Qkh/C.', 'Sanjana Akter Jemi'),
(123131, 'momo@gmail.com', '$2y$10$6D1TGwUeUHud5Pjrb4UXXu3AwdhwT.NOR./UY2khk0APUkktRBqPu', 'momo');

-- --------------------------------------------------------

--
-- Table structure for table `cleared_orders`
--

CREATE TABLE `cleared_orders` (
  `order_id` int(11) NOT NULL,
  `cleared_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleared_orders`
--

INSERT INTO `cleared_orders` (`order_id`, `cleared_at`) VALUES
(227, '2025-11-20 18:16:25'),
(228, '2025-11-20 18:16:27'),
(229, '2025-11-20 18:16:17'),
(230, '2025-11-20 18:17:25'),
(231, '2025-11-20 18:17:33'),
(232, '2025-11-20 18:17:40'),
(238, '2025-11-26 10:17:24'),
(249, '2025-11-26 09:44:25'),
(251, '2025-11-26 09:44:20'),
(252, '2025-11-26 09:44:23'),
(253, '2025-11-26 09:44:17'),
(255, '2025-11-26 09:44:16'),
(256, '2025-11-26 09:44:21'),
(258, '2025-11-26 09:44:12'),
(259, '2025-11-26 09:44:13'),
(260, '2025-11-26 09:29:46'),
(268, '2025-12-02 13:23:41'),
(269, '2025-12-03 21:56:02'),
(270, '2025-12-03 21:56:02'),
(272, '2025-12-03 21:56:03'),
(273, '2025-12-03 21:56:03'),
(276, '2025-12-03 21:56:05'),
(277, '2025-12-03 21:56:05'),
(279, '2025-12-03 21:56:06'),
(280, '2025-12-03 21:56:06'),
(281, '2025-12-03 21:56:07'),
(285, '2025-12-03 21:56:09'),
(286, '2025-12-03 21:56:10'),
(291, '2025-12-03 22:34:17'),
(297, '2025-12-03 22:46:26'),
(300, '2025-12-03 22:49:31'),
(407, '2025-12-05 06:54:34');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL DEFAULT 'Not Null',
  `email` varchar(55) NOT NULL,
  `password_hash` text NOT NULL DEFAULT 'Not Null',
  `account_status` varchar(15) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `password_hash`, `account_status`, `avatar`) VALUES
(1, 'Tanu Sharma', 'tanu@example.com', '$2y$10$m2HyY5RUJ.lt2MS8FmKTOuL0tDQWcvGzxJNxisSqEHc9ghCsdct0.', 'active', NULL),
(16, 'Tanu Thapa Tirsana', '52366@niels.brock.dk', '$2y$10$PTICuJzRsxR88bNl8r9/SOwWBNvgwymDcbefx0huun9sbOyv4GtLq', 'active', NULL),
(19, 'tirsana', 'tirsana@gmail.com', '$2y$10$1zYd4Fem9O9Q27KWfH/DleX2U.CVeK7a3hDLbLaq1XUhlbBwHB96S', 'active', NULL),
(20, 'Sanjana Akter Jemi', 'sanjanaakter283@gmail.com', '$2y$10$XX95Yp9MShgqNPs4CYMRpuhaWfqdYfETMSBJQZbMhR7mEFNxpgSwm', 'active', 'avatar_20_1764842429.png'),
(21, 'somu', 'somu@gmail.com', 'Not Null', 'active', NULL),
(22, 'somu', 'somi@gmail.com', 'Not Null', 'active', NULL),
(23, 'isra', 'isra@gmail.com', 'Not Null', 'active', NULL),
(24, 'Isra Akter', 'sukranrashid3@gmail.com', 'Not Null', 'active', NULL),
(26, 'somiya', 'somiya@gmail.com', 'Not Null', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `menu_item_id` int(5) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `variant_type` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `price` decimal(6,2) NOT NULL,
  `category` varchar(25) DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `status` enum('published','unpublished') DEFAULT 'published',
  `is_deal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`menu_item_id`, `name`, `variant_type`, `description`, `image_url`, `price`, `category`, `ingredients`, `status`, `is_deal`) VALUES
(1, 'Focaccia sandwich', 'Non-Veg', 'Chicken sandwich in focaccia bread', 'http://localhost/quick_serve/assets/images/menu_item/food/chicken_focaccia_sandwich.jpg', 50.00, 'Food', 'Chicken, focaccia, lettuce, mayo', 'published', 0),
(2, 'Focaccia Sandwich', 'Veg', 'grilled vegetables sandwich in focaccia bread', 'http://localhost/quick_serve/assets/images/menu_item/food/veg_focaccia_sandwich.jpg', 50.00, 'Food', 'focaccia, lettuce, mayo, pesto, mozarella cheese', 'published', 0),
(3, 'Fried Chicken', 'Non-Veg', 'Crispy deep-fried chicken', 'http://localhost/quick_serve/assets/images/menu_item/food/fried_chicken.jpg', 55.00, 'Food', 'Chicken, flour, spices', 'published', 0),
(4, 'Shawarma Wrap', 'Non-Veg', 'Wrap with grilled meat', 'http://localhost/quick_serve/assets/images/menu_item/food/chicken_shawarma_wrap.jpg', 60.00, 'Food', 'Chicken or veggies, pita, garlic sauce', 'published', 0),
(5, 'Shawarma Wrap', 'Veg', 'Wrap with falafel', 'http://localhost/quick_serve/assets/images/menu_item/food/veg_shawarma_wrap.jpg\r\n', 60.00, 'Food', 'falafal, pita, garlic sauce', 'published', 0),
(6, 'Grilled Halloumi Burger', 'Veg', 'Burger with grilled halloumi cheese', 'http://localhost/quick_serve/assets/images/menu_item/food/grilled_halloumi.jpg', 48.00, 'Food', 'Halloumi, bun, lettuce, tomato', 'published', 0),
(7, 'Fries and Mozzarella Stic', 'Veg', 'Combo of fries and cheesy sticks', 'http://localhost/quick_serve/assets/images/menu_item/food/mozarella_fries_stick.jpg', 40.00, 'Food', 'Potatoes, mozzarella, breadcrumbs', 'published', 0),
(8, 'Creamy Mushroom Soup', 'Veg', 'Rich mushroom soup with cream', 'http://localhost/quick_serve/assets/images/menu_item/food/creamy_mushroom.jpg\r\n', 42.00, 'Food', 'Mushrooms, cream, herbs', 'published', 0),
(9, 'Fried Rice	', 'Non-Veg', 'Fried Rice with meat and eggs', 'http://localhost/quick_serve/assets/images/menu_item/food/veg_fried_rice.jpg', 45.00, 'Food', 'Rice, vegetables, chicken,egg', 'published', 0),
(10, 'Egg Sandwich', 'Non-Veg', 'Sandwich with boiled egg and mayo', 'http://localhost/quick_serve/assets/images/menu_item/food/egg_sandwich.jpg', 40.00, 'Food', 'Egg, mayo, lettuce, bread', 'published', 0),
(11, 'Mac and Cheese', 'Veg', '\r\nPasta in creamy cheese sauce			', 'http://localhost/quick_serve/assets/images/menu_item/food/mac_and_cheese.jpg', 38.00, 'Food', 'Macaroni, cheese, milk', 'published', 0),
(12, 'Burrito', 'Non-Veg', 'Stuffed tortilla with rice, meat and fillings', 'http://localhost/quick_serve/assets/images/menu_item/food/non_veg_burito.jpg', 50.00, 'Food', 'Tortilla, rice, beans,  meat', 'published', 0),
(13, 'Burrito', 'Veg', 'Stuffed tortilla with rice and fillings', 'http://localhost/quick_serve/assets/images/menu_item/food/veg_burito.jpg', 50.00, 'Food', 'Tortilla, rice, beans, avo', 'published', 0),
(14, 'Fish Burger', 'Non-Veg', 'Crispy fish patty in a bun', 'http://localhost/quick_serve/assets/images/menu_item/food/fish_burger.jpg', 52.00, 'Food', 'Fish fillet, bun, tartar sauce', 'published', 0),
(15, 'Chocolate Chip Muffins', NULL, 'Muffins with gooey chocolate chips', 'http://localhost/quick_serve/assets/images/menu_item/bakery/chocolate_chip_muffins.jpg', 35.00, 'Bakery', 'Flour, sugar, chocolate chips, oil', 'published', 0),
(16, 'Oreo Frosting Cookies', NULL, 'Cookies topped with Oreo frosting', 'http://localhost/quick_serve/assets/images/menu_item/bakery/oreofrosting_cookies.jpg', 30.00, 'Bakery', 'Flour, sugar, Oreo, butter', 'published', 0),
(17, 'Croissant', NULL, 'Flaky buttery pastry', 'http://localhost/quick_serve/assets/images/menu_item/bakery/croissant.jpg', 28.00, 'Bakery', 'Flour, butter, yeast', 'published', 0),
(18, 'Bread Butter and Cheese', NULL, 'Danish classic breakfast', 'http://localhost/quick_serve/assets/images/menu_item/bakery/danish_breadbuttercheese.jpg.png', 32.00, 'Bakery', 'Bread, butter, cheese', 'published', 0),
(19, 'Cinnamon Roll', NULL, 'Sweet roll with cinnamon glaze', 'http://localhost/quick_serve/assets/images/menu_item/bakery/cinamonroll.jpg', 30.00, 'Bakery', 'Flour, cinnamon, sugar, yeast, egg', 'published', 0),
(20, 'Dreamy Vanilla Cake', NULL, 'Soft vanilla sponge cake', 'http://localhost/quick_serve/assets/images/menu_item/bakery/dreamy_vanilla_cake.jpg', 40.00, 'Bakery', 'Flour, vanilla, cream, egg, oil, sugar', 'published', 0),
(21, 'Brownies', NULL, 'Rich chocolate brownies', 'http://localhost/quick_serve/assets/images/menu_item/bakery/brownies.jpg', 35.00, 'Bakery', 'Chocolate, flour, sugar, butteer, sugaer, egg', 'published', 0),
(22, 'Green Tea', NULL, 'Refreshing green tea', 'http://localhost/quick_serve/assets/images/menu_item/beverages/green_tea.jpg', 20.00, 'Beverage', 'Green tea leaves, water', 'published', 0),
(23, 'Ice Latte', NULL, 'Chilled espresso with milk', 'http://localhost/quick_serve/assets/images/menu_item/beverages/ice_latte.jpg', 30.00, 'Beverage', 'Espresso, milk, ice', 'published', 0),
(24, 'Americano', NULL, 'Espresso with hot water', 'http://localhost/quick_serve/assets/images/menu_item/beverages/americano.jpg', 28.00, 'Beverage', 'Espresso, water', 'published', 0),
(25, 'Cafe Latte', NULL, 'Espresso with steamed milk', 'http://localhost/quick_serve/assets/images/menu_item/beverages/latte.jpg', 30.00, 'Beverage', 'Espresso, milk', 'published', 0),
(26, 'Cappuccino', NULL, 'Espresso with milk foam', 'http://localhost/quick_serve/assets/images/menu_item/beverages/cappucino.jpg', 32.00, 'Beverage', 'Espresso, milk, foam', 'published', 0),
(27, 'Oreo  Milkshake', NULL, 'Flavored milkshake options', 'http://localhost/quick_serve/assets/images/menu_item/beverages/oreo_milkshake.jpg', 35.00, 'Beverage', 'Milk, ice cream, flavoring, oreo', 'published', 0),
(28, 'Milkshake vanilla', NULL, 'Flavored milkshake options', 'http://localhost/quick_serve/assets/images/menu_item/beverages/vanilla_milkshake.jpg', 35.00, 'Beverage', 'Milk, ice cream, flavoring', 'published', 0),
(29, 'Milkshake chocolate', NULL, 'Flavored milkshake options', 'http://localhost/quick_serve/assets/images/menu_item/beverages/chcolate_milkshake.jpg', 35.00, 'Beverage', 'Milk, ice cream, flavoring', 'published', 0),
(30, 'Frappuccino oreo', NULL, 'Blended iced coffee with flavors', 'http://localhost/quick_serve/assets/images/menu_item/beverages/oreo_frappe.jpg', 38.00, 'Beverage', 'Coffee, ice, flavoring, ice cream, oreo', 'published', 0),
(31, 'Frappuccino caramel', NULL, 'Blended iced coffee with flavors', 'http://localhost/quick_serve/assets/images/menu_item/beverages/caramel_frappe.jpg\r\n', 38.00, 'Beverage', 'coffee, ice, flavoring, caramel, icecream', 'published', 0),
(32, 'Seasonal Iced Tea', NULL, 'Flavored iced tea of the season', 'http://localhost/quick_serve/assets/images/menu_item/beverages/seasonal_iced_tea.jpg', 28.00, 'Beverage', 'Tea, fruit essence, ice', 'published', 0),
(33, 'Soft Drinks pepsi', NULL, 'Soft drinks', 'http://localhost/quick_serve/assets/images/menu_item/beverages/pepsi.jpg', 25.00, 'Beverage', 'Carbonated water, sugar', 'published', 0),
(35, 'Water', NULL, ' Filtered water', 'http://localhost/quick_serve/assets/images/menu_item/beverages/water.jpg', 15.00, 'Beverage', NULL, 'published', 0),
(36, 'Faxe kondi', NULL, ' Soft drinks', 'http://localhost/quick_serve/assets/images/menu_item/beverages/faxekondi.jpg', 20.00, 'Beverage', NULL, 'published', 0),
(38, 'Shawarma Combo Deal', NULL, '2 wraps + fries', '/quick_serve/assets/images/logo/shawarma_combo.png', 85.00, 'Deal', NULL, 'published', 1),
(39, 'Morning Brew Deal', NULL, '10% off morning brew', '/quick_serve/assets/images/logo/Morning brews.png', 25.00, 'Deal', NULL, 'published', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(6) UNSIGNED NOT NULL,
  `customer_id` int(6) UNSIGNED NOT NULL,
  `order_id` int(15) UNSIGNED NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `sent_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `customer_id`, `order_id`, `type`, `sent_time`) VALUES
(11, 20, 261, 'Order Ready', '2025-11-10 09:15:00'),
(12, 16, 262, 'Order Cancellation', '2025-11-11 13:42:00'),
(13, 26, 263, 'Order Ready', '2025-11-12 18:27:00'),
(14, 20, 264, 'Order Ready', '2025-11-13 07:55:00'),
(15, 16, 265, 'Order Cancellation', '2025-11-14 21:33:00'),
(16, 26, 266, 'Order Ready', '2025-11-16 10:05:00'),
(17, 20, 267, 'Order Cancellation', '2025-11-18 15:48:00'),
(18, 16, 268, 'Order Ready', '2025-11-20 12:20:00'),
(19, 26, 269, 'Order Cancellation', '2025-11-22 19:02:00'),
(20, 20, 270, 'Order Ready', '2025-11-24 08:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(15) UNSIGNED NOT NULL,
  `customer_id` int(6) UNSIGNED NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `placed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `comments` text DEFAULT NULL,
  `final_amount` decimal(8,2) NOT NULL,
  `waiting_time` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `status`, `placed_at`, `comments`, `final_amount`, `waiting_time`, `feedback`) VALUES
(533, 20, NULL, '2025-12-05 07:13:26', '', 102.00, NULL, NULL),
(534, 20, NULL, '2025-12-05 07:13:55', 'extra cream', 202.00, NULL, NULL),
(535, 24, NULL, '2025-12-05 07:14:13', '', 184.00, NULL, NULL),
(536, 20, NULL, '2025-12-05 07:14:34', '', 108.00, NULL, NULL),
(537, 20, NULL, '2025-12-05 07:17:45', '', 67.00, NULL, NULL),
(538, 24, NULL, '2025-12-05 07:17:54', '', 60.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_analytics`
--

CREATE TABLE `order_analytics` (
  `analytics_id` int(25) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `total_orders` int(10) UNSIGNED NOT NULL,
  `average_waiting_time` int(25) UNSIGNED NOT NULL,
  `revenue` decimal(10,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_analytics`
--

INSERT INTO `order_analytics` (`analytics_id`, `date`, `total_orders`, `average_waiting_time`, `revenue`) VALUES
(1, '2025-11-20 23:59:00', 6, 19, 245.75),
(2, '2025-11-21 23:59:00', 8, 18, 198.40),
(3, '2025-11-22 23:59:00', 2, 14, 28.20),
(4, '2025-11-23 23:59:00', 1, 20, 22.00),
(5, '2025-11-24 23:59:00', 11, 12, 1105.60),
(6, '2025-11-25 23:59:00', 7, 22, 760.90),
(7, '2025-11-26 23:59:00', 1, 10, 29.45);

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(15) UNSIGNED NOT NULL,
  `order_id` int(15) UNSIGNED NOT NULL,
  `menu_item_id` int(15) UNSIGNED NOT NULL,
  `unit_price` decimal(6,2) NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `quantity` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `menu_item_id`, `unit_price`, `total_price`, `quantity`) VALUES
(982, 533, 15, 0.00, 0.00, 1),
(983, 533, 18, 0.00, 0.00, 1),
(984, 533, 21, 0.00, 0.00, 1),
(985, 534, 21, 0.00, 0.00, 3),
(986, 534, 25, 0.00, 0.00, 1),
(987, 534, 26, 0.00, 0.00, 1),
(988, 534, 27, 0.00, 0.00, 1),
(989, 535, 10, 0.00, 0.00, 2),
(990, 535, 14, 0.00, 0.00, 2),
(991, 536, 17, 0.00, 0.00, 1),
(992, 536, 20, 0.00, 0.00, 2),
(993, 537, 18, 0.00, 0.00, 1),
(994, 537, 21, 0.00, 0.00, 1),
(995, 538, 16, 0.00, 0.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(6) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(55) NOT NULL DEFAULT 'Not Null',
  `password` text NOT NULL,
  `role` varchar(30) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `name`, `email`, `password`, `role`, `phone`, `profile_picture`) VALUES
(123123, 'Sanjana Akter Jemi', 'sanjanaakter283@gmail.com', '$2y$10$Yb.CjZFwTwXv9pQG6PYmv.8Wpt2y3MijKwuDAgqzDskoZ..HRHkPe', 'Waiter', '1234567891', 'jem.png'),
(123457, 'MD SUKRAN RASHID', 'sukranrashid3@gmail.com', '$2y$10$uu9yPOJyB1A02mfQZXo3YuTEyDMGineB6jS2szXKAb/TAF31QsW.q', 'staff', '71518413', ''),
(123459, 'somu', 'somu@gmail.com', '$2y$10$oO2rpJ5xSjA2vVgdmRTreu6eAeNNQNhtFEC4bBn.odrd9j1B5wO8C', 'staff', '1234567890', ''),
(123461, 'rawa', 'rawaha@gmail.com', '$2y$10$K7TxI7cdQfciA7EcWmhguuo.NX6LcHkkK/7b7y7mXp2uLsF/UnDim', 'manager', '1234567890', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cleared_orders`
--
ALTER TABLE `cleared_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`menu_item_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `Foreign Key` (`customer_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_analytics`
--
ALTER TABLE `order_analytics`
  ADD PRIMARY KEY (`analytics_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `Foreign Key` (`order_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123132;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `menu_item_id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=539;

--
-- AUTO_INCREMENT for table `order_analytics`
--
ALTER TABLE `order_analytics`
  MODIFY `analytics_id` int(25) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=996;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123464;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
