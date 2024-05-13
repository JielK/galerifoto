-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2024 at 04:45 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `galerifoto`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `AlbumID` int(11) NOT NULL,
  `NamaAlbum` varchar(255) NOT NULL,
  `Deskripsi` text NOT NULL,
  `TanggalDibuat` date NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`AlbumID`, `NamaAlbum`, `Deskripsi`, `TanggalDibuat`, `UserID`) VALUES
(1, 'Desktop Wallpapers', 'Album for Desktop Wallpapers', '2024-04-17', 1),
(2, 'Desktop Wallpapers', 'Beautiful desktop wallpapers in 4K Ultra HD', '2024-04-29', 2),
(3, 'Mobile Wallpapers', 'Mobile Wallpapers in 4K HD', '2024-05-03', 6);

-- --------------------------------------------------------

--
-- Table structure for table `foto`
--

CREATE TABLE `foto` (
  `FotoID` int(11) NOT NULL,
  `JudulFoto` varchar(255) NOT NULL,
  `DeskripsiFoto` text NOT NULL,
  `TanggalUnggah` date NOT NULL,
  `LokasiFile` varchar(255) NOT NULL,
  `AlbumID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `foto`
--

INSERT INTO `foto` (`FotoID`, `JudulFoto`, `DeskripsiFoto`, `TanggalUnggah`, `LokasiFile`, `AlbumID`, `UserID`) VALUES
(1, 'Seele Vollerei', 'Cool Seele Vollerei Desktop Wallpaper ', '2024-04-17', 'seelevollerei.jpg', 1, 1),
(2, 'Car in the sunset', 'sunset car', '2024-04-29', 'photo_662ecd42b40d91.82393718.jpg', 2, 2),
(3, 'Hu Taooo', 'Cute Hu tao wallpaper for mobile I found, wanted to share 😆', '2024-05-03', 'image_6634a3708e5090.80658620.png', 3, 6),
(5, 'Landscape', 'Cool Landscape', '2024-05-13', 'image_664226944eadb2.65090806.jpg', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `komentarfoto`
--

CREATE TABLE `komentarfoto` (
  `KomentarID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IsiKomentar` text NOT NULL,
  `TanggalKomentar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `komentarfoto`
--

INSERT INTO `komentarfoto` (`KomentarID`, `FotoID`, `UserID`, `IsiKomentar`, `TanggalKomentar`) VALUES
(1, 2, 6, 'joshjosh', '2024-05-03'),
(2, 2, 6, 'nice pic btw', '2024-05-03'),
(3, 1, 6, 'this one is also nice, big fan of seele', '2024-05-03'),
(4, 3, 6, 'of course I am gonna like my own post hehe', '2024-05-03'),
(5, 3, 6, '.😍', '2024-05-03'),
(6, 3, 2, 'I like this one, using it 😎', '2024-05-03');

-- --------------------------------------------------------

--
-- Table structure for table `likefoto`
--

CREATE TABLE `likefoto` (
  `LikeID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TanggalLike` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likefoto`
--

INSERT INTO `likefoto` (`LikeID`, `FotoID`, `UserID`, `TanggalLike`) VALUES
(1, 2, 6, '2024-05-03'),
(3, 3, 6, '2024-05-03'),
(4, 1, 2, '2024-05-03'),
(5, 2, 2, '2024-05-03'),
(6, 3, 2, '2024-05-03');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NamaLengkap` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `IsAdmin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `NamaLengkap`, `Alamat`, `IsAdmin`) VALUES
(1, 'user', '$2y$10$bybtWSVmIxsgAxdFbPCrDO1DuTIxD82EL1OIzXs4.ai9G2hMeqtJO', 'jieljoestar@gmail.com', 'Default User', 'Greenland Blok A1 No. 777', NULL),
(2, 'john', '$2y$10$y2PN4yqXVzlzoFZ7eVawP.AU2K8ejMYnw2TAjMOUlcnWtGg7VI7oO', 'fersnemlesnem@gmail.com', 'JohnQT', 'Greenland Blok A1 No. 777', NULL),
(3, 'lebron', '$2y$10$hK4gIKcOAoIQfXeZobtE9.rfwpcNQP.Wg6dWH0NuqJYm5jx2gyQHy', 'lebron@gmail.com', 'Lebron James', 'Greenland Blok A1 No. 777', NULL),
(4, 'James', '$2y$10$G4b9mI5a5A0.1Kc6aN3cuu34Gj1j1dwDEiKBk.SyBbJkrpTalCeyy', 'james@gmail.com', 'James Johnson', 'Ghost Town 2nd street', NULL),
(5, 'admin', '$2y$10$LQ6PAsOm5LvRmvdpcnPBgeA/jRcvqb39S/iXF61pI/5aBI2YH/rHe', 'admin@gmail.com', 'Ad Min', 'Ghost Town 2nd street', 1),
(6, 'Josh', '$2y$10$zvvWHikEE72S7sBgZ.F5r.qD2tvwMOoS60vYM2kjYPzfPK/qvYri.', 'joshiejoestar@gmail.com', 'Joshie Joestar', 'Ghost Town 2nd street', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`AlbumID`);

--
-- Indexes for table `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`FotoID`);

--
-- Indexes for table `komentarfoto`
--
ALTER TABLE `komentarfoto`
  ADD PRIMARY KEY (`KomentarID`);

--
-- Indexes for table `likefoto`
--
ALTER TABLE `likefoto`
  ADD PRIMARY KEY (`LikeID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `AlbumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `foto`
--
ALTER TABLE `foto`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `komentarfoto`
--
ALTER TABLE `komentarfoto`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `likefoto`
--
ALTER TABLE `likefoto`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
