-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2022 at 07:40 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sgta`
--

-- --------------------------------------------------------

--
-- Table structure for table `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `data_nasc` date NOT NULL,
  `id_turma` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `data_reg` datetime NOT NULL DEFAULT current_timestamp(),
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aluno`
--

INSERT INTO `aluno` (`id`, `nome`, `email`, `data_nasc`, `id_turma`, `id_utilizador`, `data_reg`, `id_estado`) VALUES
(1, 'Odinilson gomes', 'odfifffifi1@gmail.com', '0000-00-00', 2, 1, '2022-07-09 21:53:34', 1),
(26, 'Vania2', 'vania2@gmail.com', '2022-09-07', 1, 1, '2022-07-09 22:44:02', 1),
(27, 'Meyme', 'meyme@gmail.com', '2022-07-10', 23, 1, '2022-07-10 23:23:02', 1),
(33, 'Artur4', 'artur2@gmail.com', '2022-10-10', 23, 1, '2022-07-10 23:28:59', 1),
(34, 'QQQR', 'qqq@gmail.com', '2022-06-27', 2, 1, '2022-07-10 23:32:36', 1),
(35, 'Ciclano', 'cicla@gmail.com', '2022-07-18', 24, 1, '2022-07-10 23:35:34', 1),
(36, 'Fulano4', 'fulano2@gmail.com', '2022-07-20', 24, 1, '2022-07-10 23:37:02', 1),
(37, 'sdd', 'ewds@hggga', '2022-07-11', 27, 1, '2022-07-11 22:36:30', 1),
(38, 'tttttt', 'tttttt', '2022-07-04', 13, 1, '2022-07-11 22:36:49', 1),
(40, 'Vania Gomes', 'vania@gmail.com', '1990-10-08', 1, 1, '2022-09-18 11:31:17', 1),
(41, 'Aluno 57', 'aluno57@gmail.com', '0000-00-00', 2, 1, '2022-10-13 13:40:33', 1),
(42, 'Odinilson gomes', 'odfifffifi@gmail.com', '0000-00-00', 2, 1, '2022-10-18 15:46:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `descricao` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `estado`
--

INSERT INTO `estado` (`id`, `descricao`) VALUES
(1, 'activo'),
(2, 'desactivo');

-- --------------------------------------------------------

--
-- Table structure for table `transferencia`
--

CREATE TABLE `transferencia` (
  `id` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_turma_anterior` int(11) NOT NULL,
  `id_turma_destino` int(11) NOT NULL,
  `data_reg` datetime NOT NULL DEFAULT current_timestamp(),
  `id_utilizador` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transferencia`
--

INSERT INTO `transferencia` (`id`, `id_aluno`, `id_turma_anterior`, `id_turma_destino`, `data_reg`, `id_utilizador`, `id_estado`) VALUES
(64, 1, 1, 81, '2022-09-18 21:24:21', 1, 1),
(65, 1, 1, 81, '2022-09-18 21:30:15', 1, 1),
(67, 1, 1, 2, '2022-10-18 16:43:21', 1, 1),
(68, 1, 1, 2, '2022-10-18 16:50:47', 1, 1),
(69, 1, 2, 2, '2022-10-18 16:58:34', 1, 1),
(70, 1, 2, 2, '2022-10-18 17:18:54', 1, 1),
(71, 1, 2, 2, '2022-10-18 17:22:18', 1, 1),
(72, 1, 2, 2, '2022-10-18 17:22:29', 1, 1);

--
-- Triggers `transferencia`
--
DELIMITER $$
CREATE TRIGGER `Eliminar_transferencia_aluno` AFTER DELETE ON `transferencia` FOR EACH ROW update aluno set id_turma=OLD.id_turma_anterior where id=OLD.id_aluno
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `turma`
--

CREATE TABLE `turma` (
  `id` int(11) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `serie` varchar(15) NOT NULL,
  `data_reg` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_utilizador` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `turma`
--

INSERT INTO `turma` (`id`, `nome`, `serie`, `data_reg`, `id_utilizador`, `id_estado`) VALUES
(1, 'Teste rythg', 'Teste 222', '2022-07-06 23:21:37', 1, 2),
(2, 'www4', 'Teste 7 serie3', '2022-07-08 15:02:58', 1, 2),
(13, 'KRWT', 'a1001', '2022-07-09 19:26:22', 1, 1),
(23, 'YY-AA1', 'a1001', '2022-07-09 19:41:15', 1, 1),
(24, 'teste7', '777', '2022-07-10 15:15:36', 1, 1),
(25, 'Teste8', '782', '2022-07-10 15:18:48', 1, 1),
(26, 'TTTT', '000', '2022-07-11 10:33:10', 1, 1),
(27, 'CCCC', '00C', '2022-07-11 10:53:44', 1, 1),
(30, 'sdxzc', 'sdf', '2022-07-11 20:06:40', 1, 1),
(31, 'TEste 7 nome4', 'Teste 7 serie4', '2022-07-11 20:56:21', 1, 1),
(70, 'Teste 111', 'Teste 222', '2022-09-15 11:53:51', 1, 1),
(80, 'teste 7777', 'teste 7777', '2022-09-18 10:01:47', 1, 1),
(81, 'WWW cc2c', 'teste 7737', '2022-09-18 10:09:25', 1, 1),
(82, 'FromWeb', 'FromWe', '2022-10-13 10:29:15', 1, 1),
(83, 'turma 57', 'turma 57', '2022-10-13 13:59:39', 1, 1),
(84, 'Teste 453', 'teste 453', '2022-10-13 14:15:46', 1, 1),
(85, 'Turma apresenta', 'TA3', '2022-10-13 16:02:09', 1, 1),
(86, 'Turma apresenta', 'TA3', '2022-10-13 16:06:28', 1, 1),
(87, 'tesryass', 'asfdsdf', '2022-10-13 20:45:56', 1, 1),
(88, 'YYYY', 'poopk', '2022-10-13 20:46:21', 1, 1),
(89, 'WWWW', '1231wds', '2022-10-18 15:26:21', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `utilizador`
--

CREATE TABLE `utilizador` (
  `id` int(11) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `senha` varchar(150) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilizador`
--

INSERT INTO `utilizador` (`id`, `usuario`, `senha`, `id_estado`) VALUES
(1, 'odinilson', 'adcd7048512e64b48da55b027577886ee5a36350', 0),
(6, 'teste', '123', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_aluno`
-- (See below for the actual view)
--
CREATE TABLE `vista_aluno` (
`nome` varchar(150)
,`id` int(11)
,`id_turma` int(11)
,`email` varchar(150)
,`data_nasc` date
,`nome_turma` varchar(15)
,`id_utilizador` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `vista_aluno`
--
DROP TABLE IF EXISTS `vista_aluno`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_aluno`  AS  (select `a`.`nome` AS `nome`,`a`.`id` AS `id`,`a`.`id_turma` AS `id_turma`,`a`.`email` AS `email`,`a`.`data_nasc` AS `data_nasc`,`t`.`nome` AS `nome_turma`,`a`.`id_utilizador` AS `id_utilizador` from (`aluno` `a` join `turma` `t`) where `t`.`id` = `a`.`id_turma`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_aluno` (`id_turma`),
  ADD KEY `utilizador_aluno` (`id_utilizador`),
  ADD KEY `estado_aluno` (`id_estado`);

--
-- Indexes for table `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transferencia`
--
ALTER TABLE `transferencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_transferencia` (`id_aluno`),
  ADD KEY `turma_origem_tranferencia` (`id_turma_anterior`),
  ADD KEY `utilizador_tranferencia` (`id_utilizador`),
  ADD KEY `turma_destino_tranferencia` (`id_turma_destino`),
  ADD KEY `estado_transferencia` (`id_estado`);

--
-- Indexes for table `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_utilizador` (`id_utilizador`),
  ADD KEY `turma_estado` (`id_estado`);

--
-- Indexes for table `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilizador_usuario` (`usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transferencia`
--
ALTER TABLE `transferencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `turma`
--
ALTER TABLE `turma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `estado_aluno` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `turma_aluno` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id`),
  ADD CONSTRAINT `utilizador_aluno` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`);

--
-- Constraints for table `transferencia`
--
ALTER TABLE `transferencia`
  ADD CONSTRAINT `aluno_transferencia` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `estado_transferencia` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `turma_destino_tranferencia` FOREIGN KEY (`id_turma_destino`) REFERENCES `turma` (`id`),
  ADD CONSTRAINT `turma_origem_tranferencia` FOREIGN KEY (`id_turma_anterior`) REFERENCES `turma` (`id`),
  ADD CONSTRAINT `utilizador_tranferencia` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`);

--
-- Constraints for table `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `turma_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`),
  ADD CONSTRAINT `turma_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
