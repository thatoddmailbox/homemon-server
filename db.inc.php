<?php
require_once("config.inc.php");
$db = new PDO("mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_NAME . ";charset=utf8mb4", DATABASE_USERNAME, DATABASE_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);