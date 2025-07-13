<?php
require_once __DIR__ . '/bootstrap.php';

require_once HANDLERS_PATH . '/mongodbChecker.handler.php';
require_once HANDLERS_PATH . '/postgreChecker.handler.php';

// Include template components
require_once BASE_PATH . '/components/templates/header.component.php';
require_once BASE_PATH . '/components/templates/footer.component.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// For now, no user is logged in (we'll implement login later)
$currentUser = null;

// Render page header
renderHeader('AD-Task-3 - Database Status', $currentUser);

// Show database status with alerts
echo "<h2>Database Connection Status</h2>";

echo "<h3>PostgreSQL Status:</h3>";
checkPostgreSQLConnection();

echo "<h3>MongoDB Status:</h3>";
checkMongoDBConnection();

// Example of using alert components
echo "<br>";
renderSuccessAlert("Components are now integrated into the index page!");
renderWarningAlert("User authentication will be implemented in the next steps.");

// Render page footer
renderFooter();
?>