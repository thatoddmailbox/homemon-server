-- Description: Push notification support
-- Up migration

CREATE TABLE `push_devices` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` text NOT NULL,
  `platform` tinyint NOT NULL,
  `environment` tinyint NOT NULL,
  `deviceToken` text NOT NULL,
  `lastVerdict` int NOT NULL
);