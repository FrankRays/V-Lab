-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 30, 2013 at 02:21 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vlab`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_details`
--

CREATE TABLE IF NOT EXISTS `acc_details` (
  `id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `priv` int(30) NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phno` varchar(13) NOT NULL,
  `labs` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_details`
--

INSERT INTO `acc_details` (`id`, `name`, `priv`, `address`, `email`, `phno`, `labs`) VALUES
('it001', 'Soundarrajan', 2, 'PSG Tech', 'soundarrajan@gmail.com', '8746478595', '*L1000*'),
('it002', 'Subashini', 2, '', '', '', '*'),
('it003', 'Sharathambikai', 2, '', '', '', '*'),
('tutor', 'Sivakumar', 5, 'Peelamedu', 'sivakumar@gmail.com', '828729829', '*'),
('it004', '', 2, '', '', '', '*');

-- --------------------------------------------------------

--
-- Table structure for table `doc_key_details`
--

CREATE TABLE IF NOT EXISTS `doc_key_details` (
  `doc_key` varchar(20) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL,
  `ex_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exe_upload`
--

CREATE TABLE IF NOT EXISTS `exe_upload` (
  `rollno` varchar(10) NOT NULL,
  `filename` varchar(30) NOT NULL,
  `lab_id` varchar(10) NOT NULL,
  `ex_no` int(2) NOT NULL,
  `date_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `experiment_table`
--

CREATE TABLE IF NOT EXISTS `experiment_table` (
  `lab_id` varchar(10) NOT NULL,
  `ex_no` int(2) NOT NULL,
  `doc_key` varchar(20) NOT NULL,
  `doc_name` varchar(100) NOT NULL,
  `batch` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fileupload`
--

CREATE TABLE IF NOT EXISTS `fileupload` (
  `rollno` varchar(10) NOT NULL,
  `filename` varchar(30) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `ind` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ind`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_table`
--

CREATE TABLE IF NOT EXISTS `lab_table` (
  `lab_id` varchar(10) NOT NULL,
  `lab_name` varchar(50) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `sem` int(2) NOT NULL,
  `batch` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logindetails`
--

CREATE TABLE IF NOT EXISTS `logindetails` (
  `rollno` varchar(7) NOT NULL,
  `password` varchar(35) NOT NULL,
  `priv` int(2) NOT NULL,
  `profileimage` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logindetails`
--

INSERT INTO `logindetails` (`rollno`, `password`, `priv`, `profileimage`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 10, NULL),
('10i349', 'd602c830efae8b141299efdb51b6b03c', 1, '10i349.jpg'),
('it001', 'f1aa14848fcc563745f3cc529ea04e0f', 2, 'imagedefault.jpg'),
('10i310', 'aa73e1aa46874b7f08e81d03f162e20b', 1, 'imagedefault.jpg'),
('it002', 'a5394e0d6f27692500fdafac17b4744f', 2, ''),
('it003', 'b3cf1113ec2011df498ff907d426adf9', 2, ''),
('10i338', '5cbb970729d5b6b6be7b79cebe67be53', 1, NULL),
('11i464', '9f67bb3cdef3747b9dbba51bb2f3e640', 1, NULL),
('tutor', '1f6f42334e1709a4e0f9922ad789912b', 5, ''),
('10i311', 'be22bb05e5187a030a1b939da3ef2f7f', 1, NULL),
('10i312', 'e1cc661b7aa1dfedbf241021516b983e', 1, NULL),
('10i344', '5c9380eaed9e8d0fa0a0eb7d995ac2a4', 1, NULL),
('10i319', '03b6abab025cce5028172d509e282d35', 1, NULL),
('10i315', '0845ecf1728c9e85916d2fdfc7924513', 1, 'imagedefault.jpg'),
('10i555', '313d6b0e21a4f74fb835a46d7efad556', 1, NULL),
('10i444', '1aa2b17eb2147cf0b7a9c5e884a90613', 1, NULL),
('it004', '1b93a167f3ea4d95b65a6e5272eb2d0f', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `stu_id` varchar(6) NOT NULL,
  `staff_id` varchar(8) NOT NULL,
  `ques` varchar(60) NOT NULL,
  `ans` varchar(70) NOT NULL,
  `ans_set` int(1) NOT NULL,
  `ind` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ind`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stud_details`
--

CREATE TABLE IF NOT EXISTS `stud_details` (
  `id` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phno` varchar(13) NOT NULL,
  `cgpa` float NOT NULL,
  `rank` int(2) NOT NULL,
  `achievements` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `dept` varchar(30) NOT NULL,
  `grp` varchar(3) NOT NULL,
  `labs` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stud_details`
--

INSERT INTO `stud_details` (`id`, `name`, `address`, `email`, `phno`, `cgpa`, `rank`, `achievements`, `remarks`, `dept`, `grp`, `labs`) VALUES
('10i310', 'Dijil.V.V', 'Avinashi road, Coimbatore.', 'dijil@gmail.com', '9999999999', 8.5, 0, '', '', 'Information Technology', 'G2', '*L1000-1*'),
('10i349', 'Sri Ram Koushik', '', 'dd@gmail.com', '', 0, 0, '', '', '', '', '*L1000-1*'),
('11i464', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i311', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i312', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i344', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i319', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i315', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i555', '', '', '', '', 0, 0, '', '', '', '', '*'),
('10i444', '', '', '', '', 0, 0, '', '', '', '', '*');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
