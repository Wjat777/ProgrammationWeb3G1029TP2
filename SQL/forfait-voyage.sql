-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 07 fév. 2022 à 06:49
-- Version du serveur :  8.0.18
-- Version de PHP :  7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `forfait-voyage`
--

-- --------------------------------------------------------

--
-- Structure de la table `forfaits`
--

CREATE TABLE `forfaits` (
  `id` int(11) NOT NULL,
  `destination` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `villeDepart` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dateDepart` datetime NOT NULL,
  `dateRetour` datetime NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `prix` decimal(11,2) NOT NULL,
  `taxes` varchar(21) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rabais` decimal(11,2) NOT NULL,
  `vedette` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `forfaits`
--

INSERT INTO `forfaits` (`id`, `destination`, `villeDepart`, `dateDepart`, `dateRetour`, `hotel_id`, `prix`, `taxes`, `rabais`, `vedette`) VALUES
(1, 'BrésilRio de Janeiro', 'Québec', '2021-12-27 00:00:00', '2022-01-20 00:00:00', 1, '1500.00', 'Taxes et frais inclus', '200.00', 0),
(2, 'USANew York', 'Montreal', '2021-12-27 00:00:00', '2022-01-20 00:00:00', 2, '282.00', 'Taxes et frais inclus', '0.00', 0),
(3, 'FranceParis', 'Montréal', '2021-12-27 00:00:00', '2022-01-20 00:00:00', 3, '200.00', 'Taxes et frais inclus', '50.00', 0),
(4, 'EmirateDubai', 'Toronto', '2022-04-01 00:00:00', '2022-05-01 00:00:00', 4, '2500.00', 'Taxes et frais inclus', '200.00', 0),
(5, 'EmirateDubai', 'Toronto', '2021-12-27 00:00:00', '2022-01-20 00:00:00', 4, '3500.00', 'Taxes et frais inclus', '100.00', 0),
(6, 'ChineHong Kong', 'Toronto', '2022-03-01 00:00:00', '2022-04-01 00:00:00', 5, '1396.00', 'Taxes et frais inclus', '0.00', 0),
(7, 'ChineHong Kong', 'Toronto', '2022-12-27 00:00:00', '2022-01-20 00:00:00', 5, '396.00', 'Taxes et frais inclus', '0.00', 0),
(8, 'ChineHong Kong', 'Toronto', '2023-03-01 00:00:00', '2023-04-01 00:00:00', 5, '2396.00', 'Taxes et frais inclus', '0.00', 1),
(10, 'ChineHong Kong', 'Montréal', '2023-05-02 00:00:00', '2023-06-05 00:00:00', 5, '1396.00', 'Taxes et frais inclus', '0.00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coordonnees` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `imagePath` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombreEtoiles` int(11) NOT NULL,
  `nombreChambres` int(11) NOT NULL,
  `caracteristiques` varchar(4000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `hotels`
--

INSERT INTO `hotels` (`id`, `nom`, `coordonnees`, `url`, `imagePath`, `nombreEtoiles`, `nombreChambres`, `caracteristiques`) VALUES
(1, 'Copacabane Hotel', 'Av. Atlântica, 1702 - Copacabana, Rio de Janeiro - RJ, 22021-001, Brésil', 'https://www.belmond.com/hotels/south-america/brazil/rio-de-janeiro/belmond-copacabana-palace/about', '../../../assets/images/hotel1.jpg', 5, 226, 'SPA,Piscine Interieur,Accès Plage,45 min Aeroport,Restaurent'),
(2, 'Hilton Garden Inn Times Square', '790 Eighth Avenue, New York, NY 10019-7568', 'https://www.hilton.com/en/hotels/nycmwgi-hilton-garden-inn-times-square/', '../../../assets/images/hotel2.jpg', 5, 100, 'Free WiFi,Fiteness Center,Pas des animaux de companies,Business Center'),
(3, 'ibis Paris Tour Eiffel', '2 Rue Cambronne, 15e arr., 75015 Paris, France', 'https://all.accor.com/hotel/1400/index.fr.shtml', '../../../assets/images/hotel3.jpg', 3, 50, 'Wi-Fi gratuit,Restaurant,Animaux de compagnie acceptés,Buanderie'),
(4, 'W Dubai - The Palm', 'Crescent Rd, The Palm Jumeirah - Dubai - Émirats arabes unis', 'https://www.marriott.com/hotels/travel/dxbtp-w-dubai-the-palm/', '../../../assets/images/wdubai.jpg', 5, 350, 'Animaux de compagnie acceptés,Piscine,Face à la plage,Animaux de compagnie acceptés,Spa'),
(5, 'The Ritz-Carlton, Hong Kong', 'International Commerce Centre (ICC), 1 Austin Rd W, Tsim Sha Tsui, Hong Kong', 'https://www.ritzcarlton.com/en/hotels/china/hong-kong/spa', '../../../assets/images/hotel4.jpg', 5, 600, 'SPA,Business Center,Restaurant Michelin Star,Shoping Center');

-- --------------------------------------------------------

--
-- Structure de la table `hotel_commentaires`
--

CREATE TABLE `hotel_commentaires` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `usager` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `commentaire` varchar(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `hotel_commentaires`
--

INSERT INTO `hotel_commentaires` (`id`, `hotel_id`, `usager`, `commentaire`, `note`) VALUES
(1, 1, 'William', 'Bon hôtel. Staff sympathique.', 5),
(2, 3, 'Jean', 'Un peu cher, ma la vue est exceptionnel', 3),
(3, 4, 'William', 'Un hôtel vraiment fantastique... ça vaut le prix ', 5),
(4, 4, 'Jean', 'Trop cher, trop chique', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `forfaits`
--
ALTER TABLE `forfaits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_forfait_hotel` (`hotel_id`);

--
-- Index pour la table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `hotel_commentaires`
--
ALTER TABLE `hotel_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comentaires_hotels` (`hotel_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `forfaits`
--
ALTER TABLE `forfaits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `hotel_commentaires`
--
ALTER TABLE `hotel_commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `forfaits`
--
ALTER TABLE `forfaits`
  ADD CONSTRAINT `fk_forfait_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Contraintes pour la table `hotel_commentaires`
--
ALTER TABLE `hotel_commentaires`
  ADD CONSTRAINT `fk_comentaires_hotels` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
