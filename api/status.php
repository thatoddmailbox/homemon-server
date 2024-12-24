<?php
header("Content-Type: application/json");

require_once("../data.inc.php");
require_once("../status.inc.php");

echo json_encode(get_modern_home_state());