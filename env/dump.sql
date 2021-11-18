DROP DATABASE IF EXISTS `music_rating_app`;

CREATE DATABASE IF NOT EXISTS `music_rating_app`;

USE `music_rating_app`;

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `mail` varchar(70) NOT NULL,
  `passwd` char(255) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `mail`, `passwd`, `user_type`) VALUES 
  (1, 'manager', 'manager@rating-songs.com', '123', 'adm'),
  (2, 'regular_user', 'regular_user@rating-songs.com', '123', 'regular');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


/* SONGS ------------------------------------------------> */
DROP TABLE IF EXISTS `songs`;

CREATE TABLE IF NOT EXISTS `songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `song_order` tinyint(2) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `songs` (`id`, `title`, `song_order`) VALUES 
  (1, 'The King of Carrot Flowers, Pt. One', 1),
  (2, 'The King of Carrot Flowers, Pts. Two & Three', 2),
  (3, 'In the Aeroplane Over the Sea', 3),
  (4, 'Two-Headed Boy', 4),
  (5, 'The Fool', 5),
  (6, 'Holland, 1945', 6),
  (7, 'Communist Daughter', 7),
  (8, 'Oh Comely', 8),
  (9, 'Ghost', 9),
  (10, 'Untitled', 10),
  (11, 'Two-Headed Boy, Pt. Two', 11),
  (12, 'Dead & Bloated', 1),
  (13, 'Sex Type Thing', 2),
  (14, 'Wicked Garden', 3),
  (15, 'No Memory', 4),
  (16, 'Sin', 5),
  (17, 'Naked Sunday', 6),
  (18, 'Creep', 7),
  (19, 'Piece of Pie', 8),
  (20, 'Plush', 9),
  (21, 'Wet My Bed', 10),
  (22, 'Crackerman', 11),
  (23, 'Where the River Goes', 12);



/* ALBUMS ------------------------------------------------> */
DROP TABLE IF EXISTS `albums`;

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `img` varchar(60) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `albums` (`id`, `title`, `img`) VALUES 
  (1, 'In the Aeroplane Over the Sea', 'in-the-aeroplane-over-the-sea.jpg'),
  (2, 'Core', 'core.jpg');


/* ARTISTS ------------------------------------------------> */
DROP TABLE IF EXISTS `artists`;

CREATE TABLE IF NOT EXISTS `artists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `img` varchar(60) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO `artists` (`id`, `name`, `img`) VALUES 
  (1, 'Neutral Milk Hotel', 'neutral-milk-hotel.jpg'),
  (2, 'Stone Temple Pilots', 'stone-temple-pilots.jpg');


/* ARTISTS <=> SONGS  ------------------------------------------------> */
DROP TABLE IF EXISTS `artists_songs`;

CREATE TABLE IF NOT EXISTS `artists_songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artists` int(11) NULL,
  `songs` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`artists`) REFERENCES `artists` (`id`),
  FOREIGN KEY (`songs`) REFERENCES `songs` (`id`)
);

INSERT INTO `artists_songs` (`id`, `artists`, `songs`) VALUES 
  (1, 1, 1),
  (2, 1, 2),
  (3, 1, 3),
  (4, 1, 4),
  (5, 1, 5),
  (6, 1, 6),
  (7, 1, 7),
  (8, 1, 8),
  (9, 1, 9),
  (10, 1, 10),
  (11, 1, 11),
  (12, 2, 12),
  (13, 2, 13),
  (14, 2, 14),
  (15, 2, 15),
  (16, 2, 16),
  (17, 2, 17),
  (18, 2, 18),
  (19, 2, 19),
  (20, 2, 20),
  (21, 2, 21),
  (22, 2, 22),
  (23, 2, 23);


/* ARTISTS <=> ALBUMS  ------------------------------------------------> */
DROP TABLE IF EXISTS `artists_albums`;

CREATE TABLE IF NOT EXISTS `artists_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artists` int(11) NULL,
  `albums` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`artists`) REFERENCES `artists` (`id`),
  FOREIGN KEY (`albums`) REFERENCES `albums` (`id`)
);

INSERT INTO `artists_albums` (`id`, `artists`, `albums`) VALUES
  (1, 1, 1),
  (2, 2, 2);


/* ALBUMS <=> SONGS  ------------------------------------------------> */
DROP TABLE IF EXISTS `albums_songs`;

CREATE TABLE IF NOT EXISTS `albums_songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `albums` int(11) NULL,
  `songs` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`albums`) REFERENCES `albums` (`id`),
  FOREIGN KEY (`songs`) REFERENCES `songs` (`id`)
);

INSERT INTO `albums_songs` (`id`, `albums`, `songs`) VALUES 
  (1, 1, 1),
  (2, 1, 2),
  (3, 1, 3),
  (4, 1, 4),
  (5, 1, 5),
  (6, 1, 6),
  (7, 1, 7),
  (8, 1, 8),
  (9, 1, 9),
  (10, 1, 10),
  (11, 1, 11),
  (12, 2, 12),
  (13, 2, 13),
  (14, 2, 14),
  (15, 2, 15),
  (16, 2, 16),
  (17, 2, 17),
  (18, 2, 18),
  (19, 2, 19),
  (20, 2, 20),
  (21, 2, 21),
  (22, 2, 22),
  (23, 2, 23);

/* STARRED_SONGS  ------------------------> */
DROP TABLE IF EXISTS `starred_songs`;

CREATE TABLE IF NOT EXISTS `starred_songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stars` tinyint(1) NOT NULL,
  `songs` int(11) NULL,
  `users` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`songs`) REFERENCES `songs` (`id`),
  FOREIGN KEY (`users`) REFERENCES `users` (`id`)
);

INSERT INTO `starred_songs` (`id`, `stars`, `songs`, `users`) VALUES
  (1, 4, 1, 1),
  (2, 5, 2, 1),
  (3, 3, 5, 1);


/* STARRED_ALBUMS  ------------------------> */
DROP TABLE IF EXISTS `starred_albums`;

CREATE TABLE IF NOT EXISTS `starred_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stars` tinyint(1) NOT NULL,
  `albums` int(11) NULL,
  `users` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`albums`) REFERENCES `albums` (`id`),
  FOREIGN KEY (`users`) REFERENCES `users` (`id`)
);

INSERT INTO `starred_albums` (`id`, `stars`, `albums`, `users`) VALUES
  (1, 5, 1, 1),
  (2, 4, 2, 1);

/* STARRED_ARTISTS  ------------------------> */
DROP TABLE IF EXISTS `starred_artists`;

CREATE TABLE IF NOT EXISTS `starred_artists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stars` tinyint(1) NOT NULL,
  `artists` int(11) NULL,
  `users` int(11) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`artists`) REFERENCES `artists` (`id`),
  FOREIGN KEY (`users`) REFERENCES `users` (`id`)
);

INSERT INTO `starred_artists` (`id`, `stars`, `artists`, `users`) VALUES
  (1, 5, 1, 1),
  (2, 5, 2, 1);