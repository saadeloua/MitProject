-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 17 Novembre 2018 à 10:21
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `symfony`
--

drop database if exists `symfony`;
create database `symfony` CHARACTER SET utf8 COLLATE utf8_general_ci;
use `symfony`;
-- --------------------------------------------------------

--
-- Structure de la table `role`
--



CREATE TABLE `roles` (
  `id` int(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `role` varchar(100) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateurs` (
  `id` int(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL UNIQUE,
  `password` varchar(100) NOT NULL,
  `mail` varchar(25) NOT NULL UNIQUE,
  `role_id` int(50),
  FOREIGN KEY (`role_id`) REFERENCES role(`id`)
);
