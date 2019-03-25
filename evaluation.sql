-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 05 Feb 2019 pada 17.35
-- Versi Server: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `approval`
--

CREATE TABLE `approval` (
  `ApprovalId` int(11) NOT NULL,
  `EvaluationId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `approval`
--

INSERT INTO `approval` (`ApprovalId`, `EvaluationId`) VALUES
(24, 125),
(25, 126),
(26, 127),
(27, 129),
(28, 130);

-- --------------------------------------------------------

--
-- Struktur dari tabel `approvalsetting`
--

CREATE TABLE `approvalsetting` (
  `ApprovalSettingId` int(11) NOT NULL,
  `Version` int(11) NOT NULL,
  `ApproveNumber` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `approvalsetting`
--

INSERT INTO `approvalsetting` (`ApprovalSettingId`, `Version`, `ApproveNumber`, `UserId`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `approvaluser`
--

CREATE TABLE `approvaluser` (
  `ApprovalUserId` int(11) NOT NULL,
  `ApprovalId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `IsApprove` enum('0','1') NOT NULL,
  `Status` enum('0','1') NOT NULL,
  `ApproveNumber` int(11) NOT NULL,
  `ApprovalSettingId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `approvaluser`
--

INSERT INTO `approvaluser` (`ApprovalUserId`, `ApprovalId`, `UserId`, `IsApprove`, `Status`, `ApproveNumber`, `ApprovalSettingId`) VALUES
(23, 24, 1, '1', '1', 1, 1),
(24, 24, 2, '1', '1', 2, 2),
(25, 25, 1, '1', '1', 1, 1),
(26, 25, 2, '1', '1', 2, 2),
(27, 26, 1, '1', '1', 1, 1),
(28, 26, 2, '1', '1', 2, 2),
(29, 27, 1, '1', '1', 1, 1),
(30, 27, 2, '1', '0', 2, 2),
(31, 28, 1, '1', '1', 1, 1),
(32, 28, 2, '1', '1', 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluation`
--

CREATE TABLE `evaluation` (
  `EvaluationId` int(11) NOT NULL,
  `PRId` int(11) NOT NULL,
  `EvaluationCode` varchar(255) DEFAULT NULL,
  `Note` text,
  `UserId` int(11) NOT NULL,
  `IsApprove` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `evaluation`
--

INSERT INTO `evaluation` (`EvaluationId`, `PRId`, `EvaluationCode`, `Note`, `UserId`, `IsApprove`) VALUES
(125, 24, 'EVL-5C4D659C17CC7', 'Tst', 4, '1'),
(126, 23, 'EVL-5C4D6E1591411', NULL, 4, '1'),
(127, 21, 'EVL-5C4D6760C128D', NULL, 4, '1'),
(129, 25, 'EVL-5C4D6A1034FFB', 'asaasass', 4, '1'),
(130, 26, 'EVL-5C59BAE347134', NULL, 4, '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluationdetail`
--

CREATE TABLE `evaluationdetail` (
  `EvaluationDetailId` int(11) NOT NULL,
  `SubTotal` int(11) NOT NULL,
  `Total` int(11) NOT NULL,
  `Discount` int(11) DEFAULT NULL,
  `PPN` int(11) NOT NULL,
  `GrandTotal` int(11) NOT NULL,
  `DeliveryPoint` varchar(255) NOT NULL,
  `PaymentTerms` int(11) NOT NULL,
  `EvaluationId` int(11) NOT NULL,
  `IDRId` int(11) NOT NULL,
  `PRVendorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `evaluationdetail`
--

INSERT INTO `evaluationdetail` (`EvaluationDetailId`, `SubTotal`, `Total`, `Discount`, `PPN`, `GrandTotal`, `DeliveryPoint`, `PaymentTerms`, `EvaluationId`, `IDRId`, `PRVendorId`) VALUES
(5, 200000, 8000, 4, 800, 8800, 'Batanm', 2, 125, 1, 108),
(6, 25000, 1000, 4, 100, 1100, 'baata', 4, 126, 3, 107),
(7, 3000000, 150000, 5, 15000, 165000, 'ytrrrr', 6, 127, 1, 105),
(8, 43600, 2180, 5, 218, 2398, 'errer', 6, 129, 1, 109),
(9, 180, 0, 0, 1, 1, 'batam', 7, 130, 4, 111);

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluationdetailvendor`
--

CREATE TABLE `evaluationdetailvendor` (
  `EvaluationDetailVendorId` int(11) NOT NULL,
  `Delivery` date NOT NULL,
  `UnitPrice` int(11) NOT NULL,
  `TotalPrice` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `PRVendorId` int(11) NOT NULL,
  `PRItemId` int(11) NOT NULL,
  `EvaluationId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `evaluationdetailvendor`
--

INSERT INTO `evaluationdetailvendor` (`EvaluationDetailVendorId`, `Delivery`, `UnitPrice`, `TotalPrice`, `Description`, `PRVendorId`, `PRItemId`, `EvaluationId`) VALUES
(7, '2019-01-17', 3343434, 13373736, NULL, 108, 6, 120),
(8, '2019-01-18', 5000, 25000, NULL, 105, 4, 121),
(9, '2019-01-18', 500000, 2000000, NULL, 108, 6, 122),
(10, '2019-01-25', 40000, 200000, 'Desckripsi', 107, 5, 123),
(11, '2019-01-12', 50000, 250000, NULL, 105, 4, 124),
(12, '2019-01-17', 50000, 200000, 'sdsd', 108, 6, 125),
(13, '2019-01-18', 5000, 25000, NULL, 107, 5, 126),
(14, '2019-01-18', 600000, 3000000, NULL, 105, 4, 127),
(17, '2019-01-18', 4900, 19600, NULL, 109, 8, 129),
(18, '2019-01-25', 6000, 24000, NULL, 109, 7, 129),
(19, '2019-02-15', 90, 180, NULL, 111, 9, 130);

-- --------------------------------------------------------

--
-- Struktur dari tabel `exclude`
--

CREATE TABLE `exclude` (
  `ExcludeId` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `EvaluationId` int(11) NOT NULL,
  `PRVendorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `exclude`
--

INSERT INTO `exclude` (`ExcludeId`, `ItemName`, `EvaluationId`, `PRVendorId`) VALUES
(18, 'sdsd', 120, 108),
(19, 'sdsd', 121, 105),
(20, 'rrr', 122, 108),
(21, 'approve', 123, 107),
(22, 'fff', 124, 105),
(23, 'sdsdsd', 125, 108),
(24, 'sdsdsd', 126, 107),
(25, 'gg', 127, 105),
(26, 'dsdd', 129, 109),
(27, 'test', 130, 111);

-- --------------------------------------------------------

--
-- Struktur dari tabel `group`
--

CREATE TABLE `group` (
  `GroupId` int(11) NOT NULL,
  `GroupName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `group`
--

INSERT INTO `group` (`GroupId`, `GroupName`) VALUES
(1, 'Administrator'),
(2, 'Buyer'),
(3, 'Evaluator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `idr`
--

CREATE TABLE `idr` (
  `IDRId` int(11) NOT NULL,
  `IDRCode` varchar(10) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Status` enum('Active','Non-Active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `idr`
--

INSERT INTO `idr` (`IDRId`, `IDRCode`, `Description`, `Status`) VALUES
(1, 'IDR QE', 'Test1', 'Active'),
(2, 'IDRE', NULL, 'Non-Active'),
(3, 'sas', 'ass', 'Active'),
(4, 'sdsd', 'sdsd', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `include`
--

CREATE TABLE `include` (
  `IncludeId` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `EvaluationId` int(11) NOT NULL,
  `PRVendorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `include`
--

INSERT INTO `include` (`IncludeId`, `ItemName`, `EvaluationId`, `PRVendorId`) VALUES
(10, 'sdsd', 120, 108),
(11, 'sdsd', 121, 105),
(12, 'rr', 122, 108),
(13, 'test', 123, 107),
(14, 'ff', 124, 105),
(15, 'sdsdsd', 125, 108),
(16, 'ssd', 126, 107),
(17, 'ggg', 127, 105),
(18, 'fsddsd', 129, 109),
(19, 'test', 130, 111);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `ItemId` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Status` enum('Active','Non-Active') NOT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`ItemId`, `ItemName`, `Status`, `Description`) VALUES
(3, 'Item 1', 'Non-Active', NULL),
(4, 'Item 2', 'Non-Active', NULL),
(5, 'test', 'Non-Active', NULL),
(6, 'asas', 'Non-Active', 'ssasas'),
(7, 'asdas', 'Non-Active', NULL),
(8, 'asas', 'Active', 'asas trr6yr'),
(9, 'test', 'Active', 'reeeedsdd'),
(10, 'Test', 'Active', 'ssdd'),
(11, 'asas', 'Active', 'sdasd'),
(12, 'dsds', 'Active', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `navigation`
--

CREATE TABLE `navigation` (
  `NavigationId` int(11) NOT NULL,
  `NavigationName` varchar(255) NOT NULL,
  `Url` varchar(255) NOT NULL,
  `GroupId` int(11) NOT NULL,
  `Icon` varchar(255) NOT NULL,
  `Order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `navigation`
--

INSERT INTO `navigation` (`NavigationId`, `NavigationName`, `Url`, `GroupId`, `Icon`, `Order`) VALUES
(1, 'Dashboard', 'index.php', 1, 'menu-icon mdi mdi-television', 1),
(2, 'UoM', 'pages/UoM/index.php', 1, 'menu-icon mdi mdi-backup-restore', 3),
(3, 'Item', 'pages/Item/index.php', 1, 'menu-icon mdi mdi-television', 4),
(4, 'PR', 'pages/PR/index.php', 2, 'menu-icon mdi mdi-television', 2),
(6, 'Dashboard', 'index.php\r\n', 2, 'menu-icon mdi mdi-television', 1),
(7, 'Dashboard', 'index.php', 3, 'menu-icon mdi mdi-television', 1),
(8, 'Evaluator Approval', 'pages/Approval/index.php\r\n', 3, 'menu-icon mdi mdi-television', 3),
(9, 'IDR', 'pages/IDR/index.php', 1, 'menu-icon mdi mdi-backup-restore', 2),
(10, 'Vendor', 'pages/Vendor/index.php', 1, 'menu-icon mdi mdi-backup-restore', 4),
(11, 'User', 'pages/User/index.php\r\n', 1, 'menu-icon mdi mdi-backup-restore\r\n', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pr`
--

CREATE TABLE `pr` (
  `PRId` int(11) NOT NULL,
  `PRName` varchar(255) NOT NULL,
  `Project` varchar(255) NOT NULL,
  `PRCode` varchar(255) NOT NULL,
  `CostCenter` int(255) NOT NULL,
  `Colective` varchar(255) NOT NULL,
  `PurchaseCode` varchar(255) NOT NULL,
  `IsEvaluation` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pr`
--

INSERT INTO `pr` (`PRId`, `PRName`, `Project`, `PRCode`, `CostCenter`, `Colective`, `PurchaseCode`, `IsEvaluation`) VALUES
(18, 'test', 'sdsd', 'PR-5C4D658849A9B', 0, '', '', '0'),
(19, 'sdsdSSDSDD', 'sdsdsda1RRRR', 'PR-5C4D65A1F1556', 0, '', '', '0'),
(20, 'sdsd', 'sdsd', 'PR-5C4D66049705F', 0, '', '', '0'),
(21, 'sdsd', 'sdsd', 'PR-5C4D66AC1D02D', 0, '', '', '1'),
(23, 'aas', 'asas', 'PR-5C4D67F23F8CF', 0, '', '', '1'),
(24, 'sds', 'sd', 'PR-5C4D6BFCB184D', 0, '', '', '1'),
(25, 'PR Test Multi vendor', 'Project', 'PR-5C4D665CED168', 0, '', '', '1'),
(26, 'Test PR', 'TEst', 'PR-5C4D6EA90FF14', 0, '', '', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pritem`
--

CREATE TABLE `pritem` (
  `PRItemId` int(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `PRId` int(11) NOT NULL,
  `UoMId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pritem`
--

INSERT INTO `pritem` (`PRItemId`, `Qty`, `ItemId`, `PRId`, `UoMId`) VALUES
(1, 5, 11, 18, 7),
(2, 5, 11, 19, 7),
(3, 2, 11, 20, 5),
(4, 5, 11, 21, 7),
(5, 5, 10, 23, 5),
(6, 4, 10, 24, 5),
(7, 4, 10, 25, 5),
(8, 4, 8, 25, 5),
(9, 2, 10, 26, 7),
(10, 1, 11, 26, 5),
(11, 1, 12, 26, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `prvendor`
--

CREATE TABLE `prvendor` (
  `PRVendorId` int(11) NOT NULL,
  `VendorId` int(11) NOT NULL,
  `PRId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `prvendor`
--

INSERT INTO `prvendor` (`PRVendorId`, `VendorId`, `PRId`) VALUES
(2, 2, 13),
(3, 2, 13),
(4, 2, 13),
(9, 1, 14),
(14, 2, 14),
(15, 2, 15),
(16, 1, 15),
(102, 4, 18),
(103, 4, 19),
(104, 5, 20),
(105, 4, 21),
(107, 4, 23),
(108, 4, 24),
(109, 5, 25),
(110, 2, 25),
(111, 4, 26),
(112, 5, 26),
(113, 2, 26);

-- --------------------------------------------------------

--
-- Struktur dari tabel `uom`
--

CREATE TABLE `uom` (
  `UoMId` int(11) NOT NULL,
  `UoMKode` varchar(20) NOT NULL,
  `UoMName` varchar(255) NOT NULL,
  `Status` enum('Active','Non-Active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `uom`
--

INSERT INTO `uom` (`UoMId`, `UoMKode`, `UoMName`, `Status`) VALUES
(1, 'Kg', 'Kilogram', 'Non-Active'),
(2, 'Unit', 'Unit Item', 'Non-Active'),
(3, 'Pcs', 'Price', 'Active'),
(4, 'uom32', 'UoM 3', 'Active'),
(5, 'UoM 4', 'UoM 4', 'Active'),
(6, 'uomCode1', 'test', 'Non-Active'),
(7, 'uomCode1', 'Bro', 'Active'),
(8, 'uomCode1', 'Bro hh test', 'Non-Active'),
(9, 'uomCode1', 'Bro hh10', 'Non-Active'),
(10, 'vff', 'fff', 'Non-Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `UserId` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupId` int(11) NOT NULL,
  `Password` text NOT NULL,
  `Department` varchar(255) NOT NULL,
  `Status` enum('Active','Non-Active') NOT NULL,
  `HasConfirmation` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`UserId`, `UserName`, `FullName`, `GroupId`, `Password`, `Department`, `Status`, `HasConfirmation`) VALUES
(1, 'User1', 'User 1', 3, '$2y$10$QHSMwew/yGqNcErWP4m9weVUhAinSXswNbu1.ZEzw68wUaoCzPU0O', 'MANAGER OF SCM POMS EIR', 'Active', '1'),
(2, 'User2', 'User 2', 3, '$2y$10$QHSMwew/yGqNcErWP4m9weVUhAinSXswNbu1.ZEzw68wUaoCzPU0O', 'GENERAL MANAGER OF POMS EIR', 'Active', '1'),
(3, 'Admin', 'Administrator', 1, '$2y$10$QHSMwew/yGqNcErWP4m9weVUhAinSXswNbu1.ZEzw68wUaoCzPU0O', '', 'Active', '1'),
(4, 'userbuyer', 'User Buyer', 2, '$2y$10$QHSMwew/yGqNcErWP4m9weVUhAinSXswNbu1.ZEzw68wUaoCzPU0O', '', 'Active', '1'),
(5, 'Test@df', 'Test', 1, '$2y$10$b2aHjXNUu22oJtLfjg0HfOniw0YR8SZOY8hXa.xGDjbfYGIfBCDdO', 'Department', 'Active', '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vendor`
--

CREATE TABLE `vendor` (
  `VendorId` int(11) NOT NULL,
  `VendorName` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `Status` enum('Active','Non-Active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `vendor`
--

INSERT INTO `vendor` (`VendorId`, `VendorName`, `Address`, `Status`) VALUES
(1, 'sdsd', 'sdsd', 'Non-Active'),
(2, 'Vendor 2', 'Jalan Berbatu batu', 'Active'),
(3, 'Vendor 3', 'Jl Batu Batu1', 'Non-Active'),
(4, 'ftr', 'rff', 'Active'),
(5, 'sdsd', 'sd', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval`
--
ALTER TABLE `approval`
  ADD PRIMARY KEY (`ApprovalId`),
  ADD KEY `EvaluationId` (`EvaluationId`);

--
-- Indexes for table `approvalsetting`
--
ALTER TABLE `approvalsetting`
  ADD PRIMARY KEY (`ApprovalSettingId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `approvaluser`
--
ALTER TABLE `approvaluser`
  ADD PRIMARY KEY (`ApprovalUserId`),
  ADD KEY `ApprovalSettingId` (`ApprovalSettingId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `ApprovalId` (`ApprovalId`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`EvaluationId`),
  ADD KEY `PRId` (`PRId`);

--
-- Indexes for table `evaluationdetail`
--
ALTER TABLE `evaluationdetail`
  ADD PRIMARY KEY (`EvaluationDetailId`),
  ADD KEY `IDRId` (`IDRId`),
  ADD KEY `EvaluationId` (`EvaluationId`),
  ADD KEY `PRVendorId` (`PRVendorId`);

--
-- Indexes for table `evaluationdetailvendor`
--
ALTER TABLE `evaluationdetailvendor`
  ADD PRIMARY KEY (`EvaluationDetailVendorId`),
  ADD KEY `PRDetailId` (`PRItemId`),
  ADD KEY `VendorInvitationId` (`PRVendorId`),
  ADD KEY `EvaluationId` (`EvaluationId`);

--
-- Indexes for table `exclude`
--
ALTER TABLE `exclude`
  ADD PRIMARY KEY (`ExcludeId`),
  ADD KEY `EvaluationId` (`EvaluationId`),
  ADD KEY `VendorInvitationId` (`PRVendorId`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`GroupId`);

--
-- Indexes for table `idr`
--
ALTER TABLE `idr`
  ADD PRIMARY KEY (`IDRId`);

--
-- Indexes for table `include`
--
ALTER TABLE `include`
  ADD PRIMARY KEY (`IncludeId`),
  ADD KEY `EvaluationId` (`EvaluationId`),
  ADD KEY `VendorInvitationId` (`PRVendorId`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`ItemId`);

--
-- Indexes for table `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`NavigationId`),
  ADD KEY `GroupId` (`GroupId`);

--
-- Indexes for table `pr`
--
ALTER TABLE `pr`
  ADD PRIMARY KEY (`PRId`);

--
-- Indexes for table `pritem`
--
ALTER TABLE `pritem`
  ADD PRIMARY KEY (`PRItemId`),
  ADD KEY `PRId` (`PRId`),
  ADD KEY `ItemId` (`ItemId`),
  ADD KEY `UoMId` (`UoMId`);

--
-- Indexes for table `prvendor`
--
ALTER TABLE `prvendor`
  ADD PRIMARY KEY (`PRVendorId`),
  ADD KEY `PRId` (`PRId`),
  ADD KEY `VendorId` (`VendorId`);

--
-- Indexes for table `uom`
--
ALTER TABLE `uom`
  ADD PRIMARY KEY (`UoMId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`),
  ADD KEY `GroupId` (`GroupId`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`VendorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval`
--
ALTER TABLE `approval`
  MODIFY `ApprovalId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `approvalsetting`
--
ALTER TABLE `approvalsetting`
  MODIFY `ApprovalSettingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `approvaluser`
--
ALTER TABLE `approvaluser`
  MODIFY `ApprovalUserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `EvaluationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT for table `evaluationdetail`
--
ALTER TABLE `evaluationdetail`
  MODIFY `EvaluationDetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `evaluationdetailvendor`
--
ALTER TABLE `evaluationdetailvendor`
  MODIFY `EvaluationDetailVendorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `exclude`
--
ALTER TABLE `exclude`
  MODIFY `ExcludeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `GroupId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `idr`
--
ALTER TABLE `idr`
  MODIFY `IDRId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `include`
--
ALTER TABLE `include`
  MODIFY `IncludeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `ItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `NavigationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `pr`
--
ALTER TABLE `pr`
  MODIFY `PRId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `pritem`
--
ALTER TABLE `pritem`
  MODIFY `PRItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `prvendor`
--
ALTER TABLE `prvendor`
  MODIFY `PRVendorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `uom`
--
ALTER TABLE `uom`
  MODIFY `UoMId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `VendorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `approval`
--
ALTER TABLE `approval`
  ADD CONSTRAINT `approval_ibfk_1` FOREIGN KEY (`EvaluationId`) REFERENCES `evaluation` (`EvaluationId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `approvalsetting`
--
ALTER TABLE `approvalsetting`
  ADD CONSTRAINT `approvalsetting_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `approvaluser`
--
ALTER TABLE `approvaluser`
  ADD CONSTRAINT `approvaluser_ibfk_1` FOREIGN KEY (`ApprovalId`) REFERENCES `approval` (`ApprovalId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvaluser_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvaluser_ibfk_3` FOREIGN KEY (`ApprovalSettingId`) REFERENCES `approvalsetting` (`ApprovalSettingId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`PRId`) REFERENCES `pr` (`PRId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `evaluationdetail`
--
ALTER TABLE `evaluationdetail`
  ADD CONSTRAINT `evaluationdetail_ibfk_1` FOREIGN KEY (`EvaluationId`) REFERENCES `evaluation` (`EvaluationId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `evaluationdetail_ibfk_2` FOREIGN KEY (`PRVendorId`) REFERENCES `prvendor` (`PRVendorId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `evaluationdetail_ibfk_3` FOREIGN KEY (`IDRId`) REFERENCES `idr` (`IDRId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
