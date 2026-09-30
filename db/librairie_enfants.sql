-- Librairie pour enfants : importer dans une base vide.
CREATE DATABASE IF NOT EXISTS librairie_enfants CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE librairie_enfants;

CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `category` (`id`, `name`, `description`) VALUES ('1', 'Aventure', 'Histoires pleines d’aventures et de découvertes.');
INSERT INTO `category` (`id`, `name`, `description`) VALUES ('2', 'Fantaisie', 'Histoires avec des mondes imaginaires et fantastiques.');
INSERT INTO `category` (`id`, `name`, `description`) VALUES ('3', 'Éducatif', 'Livres pour apprendre tout en s’amusant.');
INSERT INTO `category` (`id`, `name`, `description`) VALUES ('4', 'Animaux', 'Histoires sur les animaux et la nature.');

CREATE TABLE `author` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `birth_year` int DEFAULT NULL,
  `biography` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('1', 'Mark Twain', 'États-Unis', '1835', 'Écrivain américain connu pour ses romans d’aventure.');
INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('2', 'Michael Ende', 'Allemagne', '1929', 'Écrivain allemand connu pour ses œuvres de littérature jeunesse.');
INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('3', 'Antoine de Saint-Exupéry', 'France', '1900', 'Écrivain et aviateur français, auteur du Petit Prince.');
INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('4', 'Gilbert Delahaye', 'Belgique', '1923', 'Écrivain belge connu pour les histoires de Martine.');
INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('5', 'Charles Dudley Warner', 'États-Unis', '1829', 'Écrivain et essayiste américain');
INSERT INTO `author` (`id`, `name`, `country`, `birth_year`, `biography`) VALUES ('6', 'Charles Perrault', 'France', '1628', 'Charles Perrault est un écrivain français, connu pour ses contes pour enfants comme Le Petit Chaperon rouge, Cendrillon et La Belle au bois dormant.');

CREATE TABLE `book` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text,
  `page_count` int DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `edition` varchar(100) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `book_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `book` (`id`, `title`, `description`, `page_count`, `price`, `edition`, `age_group`, `category_id`) VALUES ('1', 'Les Aventures de Tom Sawyer', 'Les aventures d’un jeune garçon curieux et courageux.', '288', '12.99', 'Gallimard Jeunesse', '9-12 ans', '1');
INSERT INTO `book` (`id`, `title`, `description`, `page_count`, `price`, `edition`, `age_group`, `category_id`) VALUES ('2', 'L\'Histoire sans fin', 'Un jeune garçon découvre un livre mystérieux qui le transporte dans un monde fantastique.', '528', '16.99', 'Le Livre de Poche', '10-14 ans', '2');
INSERT INTO `book` (`id`, `title`, `description`, `page_count`, `price`, `edition`, `age_group`, `category_id`) VALUES ('3', 'Le Petit Prince', 'Un jeune prince voyage de planète en planète et découvre le monde et l’amitié.', '96', '10.99', 'Gallimard', '8-12 ans', '2');
INSERT INTO `book` (`id`, `title`, `description`, `page_count`, `price`, `edition`, `age_group`, `category_id`) VALUES ('4', 'Martine à la ferme', 'Martine découvre la vie à la ferme et les animaux qui y vivent.', '24', '7.99', 'Casterman', '5-8 ans', '1');
INSERT INTO `book` (`id`, `title`, `description`, `page_count`, `price`, `edition`, `age_group`, `category_id`) VALUES ('9', 'Le Petit Chaperon rouge', 'Une petite fille traverse la forêt pour rendre visite à sa grand-mère. En chemin, elle rencontre un loup qui cherche à la tromper.', '32', '10.00', 'Gallimard Jeunesse', '4-7 ans', '1');

CREATE TABLE `book_author` (
  `book_id` int NOT NULL,
  `author_id` int NOT NULL,
  PRIMARY KEY (`book_id`,`author_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `book_author` (`book_id`, `author_id`) VALUES ('1', '1');
INSERT INTO `book_author` (`book_id`, `author_id`) VALUES ('2', '2');
INSERT INTO `book_author` (`book_id`, `author_id`) VALUES ('3', '3');
INSERT INTO `book_author` (`book_id`, `author_id`) VALUES ('4', '4');
INSERT INTO `book_author` (`book_id`, `author_id`) VALUES ('9', '6');

