<?php
require_once __DIR__ . '/bootstrap.php';

require_once HANDLERS_PATH . '/mongodbChecker.handler.php';
require_once HANDLERS_PATH . '/postgreChecker.handler.php';

echo "<h1>AD-Task-3 - Database Status</h1>";

echo "<h2>PostgreSQL Status:</h2>";
checkPostgreSQLConnection();

echo "<h2>MongoDB Status:</h2>";
checkMongoDBConnection();
?>