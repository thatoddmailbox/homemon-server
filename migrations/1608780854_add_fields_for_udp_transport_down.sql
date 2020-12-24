-- Description: Add fields for UDP transport
-- Down migration

ALTER TABLE `reports`
DROP `transport`,
DROP `clientTimestamp`;