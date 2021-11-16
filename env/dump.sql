DROP DATABASE IF EXISTS `contato_seguro_app`;
DROP DATABASE IF EXISTS `music_rating_app`;

CREATE DATABASE IF NOT EXISTS `music_rating_app`;

USE `music_rating_app`;

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `mail` varchar(70) NOT NULL,
  `phone` varchar(22) DEFAULT NULL,
  `birth` varchar(10) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,'Henrique Fogaça','henrique.fogaca@gmail.com','321321321','2021-08-11','São Paulo','2021-08-25 23:09:14','2021-08-28 01:58:14'),(8,'Paola Carosella','paolla.carosella.j@gmail.com','(51)99999-9999','2021-07-26','Buenos Aires','2021-08-25 23:10:27','2021-08-28 02:14:40'),(9,'Érick Jacquin','erick.jacquin@gmail.com','(51)88888-8888','2021-08-04','Saint-Amand-Montrond','2021-08-25 23:11:22','2021-08-28 02:14:35'),(36,'Susan','sussu.l.s@hotmail.coma','(51) 99999-9999','2021-08-04','Porto Alegre','2021-08-27 07:15:19','2021-08-28 04:10:15'),(60,'Anderson','aloeffler.j@hotmail.com','999999','2012-07-27','Porto Alegre','2021-08-28 04:39:50','2021-08-28 18:49:34');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;