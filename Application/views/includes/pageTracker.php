<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../../database/connection.php";
require_once __DIR__ . "/../../models/PageVisitsModel.php";
require_once __DIR__ . "/../../controllers/PageVisitsController.php";

$tracker = new PageVisitsController($conn);
$tracker->trackVisits();
