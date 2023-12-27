-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 18 fév. 2023 à 20:40
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tasks`
--

-- --------------------------------------------------------

-- Create a table for users
CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `user_name` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create a table for projects
CREATE TABLE `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`),
  CONSTRAINT `fk_user_project` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create a table for tasks
CREATE TABLE `task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_descr` text NOT NULL,
  `task_end` date NOT NULL,
  `statut` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`task_id`),
  CONSTRAINT `fk_user_task` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_project_task` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Déchargement des données de la table `tb_user`
--

-- Insert data into tb_user table
INSERT INTO `tb_user` (`user_id`, `email`, `password`, `user_name`) VALUES
(1, 'a@example.com', '$2y$10$3RgQuKEO5EEzhQK/bWoi/OLdOlbWf7GVtATDktrBzcWMhiF66rm2W', 'UserA'),
(2, 'h@example.com', 'hashed_password_for_user_h', 'UserH'),
(3, 'b@example.com', '$2y$10$7xYLI/KqIeKqdKjSIi8Jie51GYQl/mJJlA8dShPr768o.8B4/Vv4i', 'UserB');

-- Insert data into project table
INSERT INTO `project` (`project_id`, `project_name`, `user_id`) VALUES
(1, 'ProjectOne', 1),
(2, 'ProjectTwo', 2),
(3, 'ProjectThree', 3);

-- Insert data into task table
INSERT INTO `task` (`task_id`, `task_descr`, `task_end`, `statut`, `user_id`, `project_id`) VALUES
(1, 'Task for ProjectOne', '2023-12-31', 'In Progress', 1, 1),
(2, 'Task for ProjectTwo', '2023-12-31', 'Not Started', 2, 2),
(3, 'Task for ProjectThree', '2023-12-31', 'Completed', 3, 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`);

--
-- Index pour la table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT pour la table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
