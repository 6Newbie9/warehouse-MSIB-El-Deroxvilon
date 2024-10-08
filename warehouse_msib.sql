-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Okt 2024 pada 14.13
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warehouse_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `gudang_id` varchar(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `barang`
--
DELIMITER $$
CREATE TRIGGER `after_delete_barang` AFTER DELETE ON `barang` FOR EACH ROW BEGIN
    
    UPDATE gudang
    SET capacity = capacity + OLD.quantity
    WHERE id = OLD.gudang_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_barang` AFTER INSERT ON `barang` FOR EACH ROW BEGIN
    DECLARE current_capacity INT;
    
    
    SELECT capacity INTO current_capacity
    FROM gudang
    WHERE id = NEW.gudang_id;
    
    
    IF current_capacity >= NEW.quantity THEN
        UPDATE gudang
        SET capacity = capacity - NEW.quantity
        WHERE id = NEW.gudang_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kapasitas gudang tidak mencukupi.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_barang` BEFORE INSERT ON `barang` FOR EACH ROW BEGIN
    DECLARE new_id VARCHAR(5);
    
    
    SELECT CONCAT('B', LPAD(IFNULL(MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)), 0) + 1, 2, '0'))
    INTO new_id
    FROM barang;
    
    
    SET NEW.id = new_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gudang`
--

CREATE TABLE `gudang` (
  `id` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('aktif','tidak_aktif') DEFAULT 'aktif',
  `opening_hour` time DEFAULT NULL,
  `closing_hour` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `gudang`
--
DELIMITER $$
CREATE TRIGGER `before_insert_gudang` BEFORE INSERT ON `gudang` FOR EACH ROW BEGIN
    DECLARE new_id VARCHAR(5);
    
    
    SELECT CONCAT('G', LPAD(IFNULL(MAX(CAST(SUBSTRING(id, 2) AS UNSIGNED)), 0) + 1, 2, '0'))
    INTO new_id
    FROM gudang;
    
    
    SET NEW.id = new_id;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indeks untuk tabel `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
