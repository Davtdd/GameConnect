-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 03 avr. 2026 à 16:36
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gameconnect`
--
CREATE DATABASE IF NOT EXISTS `gameconnect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gameconnect`;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `idc` int(11) NOT NULL,
  `idp` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `texte` varchar(255) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`idc`, `idp`, `idu`, `texte`, `date_creation`) VALUES
(1, 4, 2, 'Intéressant', '2026-04-03 10:20:19'),
(2, 5, 2, 'Tu l&#039;as posté deux fois mdr, je comprends t&#039;inquiète .', '2026-04-03 10:22:01'),
(3, 10, 2, 'Je suis aussi un grand fan de FIFA devenu efc.', '2026-04-03 10:26:17'),
(4, 8, 2, 'L\'attente est bouillante !!', '2026-04-03 10:26:57'),
(5, 6, 1, 'Le gamin est va devenir un loisir de riche bientôt !!', '2026-04-03 12:10:27'),
(6, 10, 1, 'Bonne game a toi !', '2026-04-03 12:13:11'),
(7, 5, 4, 'Deux fois le poste , n\'oublie pas de supprimer l\'autre.', '2026-04-03 16:13:08');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `idl` int(11) NOT NULL,
  `idp` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`idl`, `idp`, `idu`, `date_creation`) VALUES
(12, 10, 1, '2026-04-03 15:47:39'),
(13, 8, 1, '2026-04-03 15:47:51'),
(14, 6, 1, '2026-04-03 15:47:56'),
(18, 8, 4, '2026-04-03 16:12:12'),
(19, 6, 4, '2026-04-03 16:12:15'),
(20, 4, 4, '2026-04-03 16:12:16'),
(21, 5, 4, '2026-04-03 16:12:19'),
(22, 1, 4, '2026-04-03 16:12:28');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `idp` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `image` text DEFAULT NULL,
  `lien` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`idp`, `idu`, `contenu`, `image`, `lien`, `date_creation`) VALUES
(1, 1, 'Bonjour les gars, aujourd\'hui, je suis tombé sur une vidéo parlant de la hausse des prix, aller regarder la vidéo.', 'uploads/posts/69c688fb9213d_Capture d’écran du 2026-03-27 14-35-22.png', 'https://youtu.be/VMN_E0McCTc', '2026-03-27 14:41:15'),
(3, 1, 'Les 10 JEUX VIDÉO les plus ATTENDUS sur PS5, Switch 2, Xbox et PC 🌟 AVRIL 2026\\r\\n', 'uploads/posts/69c809a60fcb6_Capture d’écran du 2026-03-28 18-01-40.png', 'https://youtu.be/v4iplmiqbO0', '2026-03-28 18:02:30'),
(4, 1, 'Les 10 JEUX VIDÉO les plus ATTENDUS sur PS5, Switch 2, Xbox et PC 🌟 AVRIL 2026\\r\\n', 'uploads/posts/69c80b26e244a_Capture d’écran du 2026-03-28 18-01-40.png', 'https://youtu.be/v4iplmiqbO0', '2026-03-28 18:08:54'),
(5, 1, 'Les 10 JEUX VIDÉO les plus ATTENDUS sur PS5, Switch 2, Xbox et PC 🌟 AVRIL 2026\\r\\n', 'uploads/posts/69c80b26e244a_Capture d’écran du 2026-03-28 18-01-40.png', 'https://youtu.be/v4iplmiqbO0', '2026-03-28 18:08:54'),
(6, 2, '\\r\\nATTENTION DANGER ⚠️ Le GAMING prend un GROS RISQUE !!!', 'uploads/posts/69c80c555b114_Capture d’écran du 2026-03-28 18-13-27.png', 'https://youtu.be/AjLdhnDeq4U', '2026-03-28 18:13:57'),
(8, 2, 'Les 12 JEUX les PLUS ATTENDUS du PRINTEMPS 2026 ! 💥 Ça va envoyer du LOURD !', 'uploads/posts/69c814a9ece9c_Capture d’écran du 2026-03-28 18-48-58.png', 'https://youtu.be/e_Qn22hxfVI', '2026-03-28 18:49:29'),
(10, 4, 'EA SPORTS FC 25 : CARRIÈRE PRO FR #1 - Vers une légende !\\r\\n', 'uploads/posts/69c81fe2d6c1c_Capture d’écran du 2026-03-28 19-33-19.png', 'https://youtu.be/jLiDuZTnz6M', '2026-03-28 19:37:22');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `idu` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `bio` varchar(100) DEFAULT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `avatar` text DEFAULT NULL,
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idu`, `nom`, `prenom`, `bio`, `pseudo`, `email`, `mot_de_passe`, `avatar`, `date_inscription`) VALUES
(1, 'Dwrite', 'Eliote', 'Bienvenue dans mon monde !!', 'dwriteger', 'eliote@gmail.com', '$2y$10$IErFuirScqE03r4jPf/9deX.iZCh1JG613yxpwYq7MZiUiYVJlzTS', 'uploads/avatars/69c6628f1d337_pexels-chaikong2511-185725.jpg', '2026-03-27 11:57:19'),
(2, 'Martin', 'Jean', 'jean@gmail.com', 'Shadow', 'jean@gmail.com', '$2y$10$Epknl7ntQIWjBgfMV6wxQuts6dOhSsEnIEuVbaBknj2f.XwNcwDBi', 'uploads/avatars/69c6982a87445_hacker-hacking-computer-security.jpg', '2026-03-27 15:46:02'),
(4, 'MIssit', 'Edouard', 'Grand Joueur de FIFA : Instagram @Eclaire', 'Eclaire', 'eclaire@gmail.com', '$2y$10$YhKIZpOzpuQYd6JKBvgBY.FyPDo7hx7q06arL.01B2eLJpMkvwFnW', 'uploads/avatars/69c81fb704050_pexels-pixabay-2150.jpg', '2026-03-28 19:36:39');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`idc`),
  ADD KEY `post_id` (`idp`),
  ADD KEY `user_id` (`idu`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`idl`),
  ADD UNIQUE KEY `post_id` (`idp`,`idu`),
  ADD KEY `user_id` (`idu`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `user_id` (`idu`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idu`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `idc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `idl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `posts` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `posts` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`idu`) REFERENCES `users` (`idu`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
