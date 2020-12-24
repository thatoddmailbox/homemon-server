-- Description: Add fields for UDP transport
-- Up migration

ALTER TABLE `reports`
ADD `transport` tinyint NOT NULL AFTER `ip`,
ADD `clientTimestamp` int NOT NULL AFTER `transport`;