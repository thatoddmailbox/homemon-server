-- Description: Initial migration
-- Up migration

CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `powered` tinyint(1) NOT NULL,
  `batteryLevel` int(11) NOT NULL,
  `batteryVoltage` int(11) NOT NULL,
  `ip` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;