<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__, 2) . '/database-connection/shared_db.php';

$conn = highbrowsGetMysqliConnection('academic');
?>
