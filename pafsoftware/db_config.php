<?php
require_once dirname(__DIR__) . '/database-connection/shared_db.php';

$DB_CONFIG = highbrowsGetDbConfig('pafsoftware');

function getPDOConnection(): PDO
{
    return highbrowsGetPdoConnection('pafsoftware');
}

function getMysqliConnection(): mysqli
{
    return highbrowsGetMysqliConnection('pafsoftware');
}
?>
