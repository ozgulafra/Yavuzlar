-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 25 Eyl 2024, 22:03:17
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `restaurant_app`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `basket`
--

CREATE TABLE `basket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `quantity` varchar(5) NOT NULL,
  `created_at` varchar(255) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `surname` varchar(110) NOT NULL,
  `title` varchar(110) NOT NULL,
  `description` varchar(1100) NOT NULL,
  `score` int(10) NOT NULL,
  `created_at` varchar(1000) NOT NULL DEFAULT current_timestamp(),
  `updated_at` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `restaurant_id`, `surname`, `title`, `description`, `score`, `created_at`, `updated_at`) VALUES
(1, 16, 0, 'su', 'müq', 'harika', 6, '', ''),
(2, 16, 0, 'su', 'müq', 'harika', 9, '', ''),
(5, 17, 0, 'su', 'DONAS KALİTESİ', 'HAYATIMDA YEDİĞİM EN İYİ DÜRÜMDÜ. TEŞEKKÜRLER DONAS TEŞEKKÜRLER TÜRKİYE TEŞEKKÜRLER ATATÜRK <33333', 10, '2024-09-21 21:25:03', ''),
(6, 17, 0, 'karapınar', 'DAHA BOL SOS', 'YEMEK MÜQ AMA DAHA FAZLA SOS LÜTFEN SİZE YAKIŞTIRAMADIM ', 10, '2024-09-21 21:27:40', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(110) NOT NULL,
  `description` varchar(110) NOT NULL,
  `logo_path` varchar(110) NOT NULL,
  `deleted_at` varchar(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `company`
--

INSERT INTO `company` (`id`, `name`, `description`, `logo_path`, `deleted_at`) VALUES
(7, 'afra', 'logo', '_', 'deleted'),
(8, 'asdasd', 'asdsadsda', '_', '_'),
(9, 'Donas', 'Dürümcünüz', '_', '_');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cupon`
--

CREATE TABLE `cupon` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `name` varchar(1000) NOT NULL,
  `discount` varchar(1000) NOT NULL,
  `created_at` varchar(1000) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `cupon`
--

INSERT INTO `cupon` (`id`, `restaurant_id`, `name`, `discount`, `created_at`) VALUES
(4, 0, 'DURUM15', '15', '2024-09-21 17:07:50'),
(5, 0, 'DURUM95', '95', '2024-09-25 21:53:24');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `food`
--

CREATE TABLE `food` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image_path` varchar(1000) NOT NULL,
  `price` varchar(1100) NOT NULL,
  `discount` varchar(1000) NOT NULL,
  `created_at` varchar(1000) NOT NULL DEFAULT current_timestamp(),
  `deleted_at` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `food`
--

INSERT INTO `food` (`id`, `restaurant_id`, `name`, `description`, `image_path`, `price`, `discount`, `created_at`, `deleted_at`) VALUES
(2, 0, 'Bol soslu zurna dürüm', 'hatay usulü 60 cm zurna dürüm', '_', '120', '', '', ''),
(3, 0, 'Et döner dürüm maxi boy', '130 gr döner ', '_', '170', '', '2024-09-19 23:10:48', ''),
(4, 0, 'Et döner dürüm maxi boy', '150 gr et dönerden dürüm', 'gorseller/et_durum.jpg', '200', '', '2024-09-21 17:07:28', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_status` varchar(1000) NOT NULL,
  `total_price` varchar(1000) NOT NULL,
  `created_at` varchar(1000) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `order`
--

INSERT INTO `order` (`id`, `user_id`, `order_status`, `total_price`, `created_at`) VALUES
(7, 18, 'bekliyor', '1800', '2024-09-25 09:45:58'),
(8, 18, 'tamamlandi', '60', '2024-09-25 21:58:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` varchar(110) NOT NULL DEFAULT '0',
  `price` varchar(110) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `order_items`
--

INSERT INTO `order_items` (`id`, `food_id`, `order_id`, `quantity`, `price`, `note`) VALUES
(6, 4, 7, '5', '200', 'yok'),
(7, 4, 7, '4', '200', 'yok2'),
(8, 4, 8, '6', '200', 'YOOK');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image_path` varchar(1000) NOT NULL,
  `created_at` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `restaurants`
--

INSERT INTO `restaurants` (`id`, `company_id`, `name`, `description`, `image_path`, `created_at`) VALUES
(0, 11, 'Donas Dürümcü', 'tavuk döner dürüm bol soslu ve turşulu', '_', ''),
(4, 9, 'Armada ', 'Kokoreç', '_', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `role` int(5) NOT NULL,
  `name` varchar(110) NOT NULL,
  `surname` varchar(110) NOT NULL,
  `username` varchar(110) NOT NULL,
  `password` varchar(110) NOT NULL,
  `balance` varchar(5000) NOT NULL DEFAULT '',
  `created_at` varchar(255) NOT NULL DEFAULT current_timestamp(),
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `company_id`, `role`, `name`, `surname`, `username`, `password`, `balance`, `created_at`, `deleted_at`) VALUES
(16, 9, 1, 'Donas', '', 'donas', '$argon2id$v=19$m=12,t=3,p=1$dXExZnZ3dG9jeWUwMDAwMA$VGCjBX1+iWFmSxxkBjJWBQ', '', '2024-09-18 23:08:51', '_'),
(17, NULL, 0, 'özgül afra', 'su', 'ozgulafra', '$argon2id$v=19$m=12,t=3,p=1$Z3M5MzN2dTQ2OGIwMDAwMA$H3vLpt9LfAr/KH3k0M9THw', '500', '2024-09-21 21:03:24', '_'),
(18, NULL, 0, 'Beyza', 'Kara', 'beyzakara@mail.com', '$argon2id$v=19$m=12,t=3,p=1$Z3M5MzN2dTQ2OGIwMDAwMA$H3vLpt9LfAr/KH3k0M9THw', '150', '2024-09-24 09:53:01', '_'),
(19, NULL, 2, 'admin', 'admin', 'admin', '$argon2id$v=19$m=12,t=3,p=1$ZThkZDBjaXlqdzAwMDAwMA$9LHbZqCdj0xJCGWLm+QQdQ', '999999', 'current_timestamp()', ''),
(21, NULL, 0, 'nnnn', 'nnnn', 'nnnn', '$argon2id$v=19$m=65536,t=4,p=1$anVLa1VML05ickpycWNKUA$eaJbcjyMdTfOfsdB81SHyINiz74I6pZkK1vDLqS/LpA', '', '2024-09-25 22:45:46', 'deleted');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cupon`
--
ALTER TABLE `cupon`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `cupon`
--
ALTER TABLE `cupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `food`
--
ALTER TABLE `food`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
