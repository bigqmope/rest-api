DROP DATABASE IF EXISTS `kampus_db`;
CREATE DATABASE IF NOT EXISTS `kampus_db`;
USE `kampus_db`;

DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);