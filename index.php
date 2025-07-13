<?php
require_once __DIR__ . '/bootstrap.php';

require_once HANDLERS_PATH . '/mongodbChecker.handler.php';
require_once HANDLERS_PATH . '/postgreChecker.handler.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// For now, no user is logged in (we'll implement login later)
$currentUser = null;

// Capture the page content
$content = captureContent(function () {
    echo "<h2>Database Connection Status</h2>";

    echo "<h3>PostgreSQL Status:</h3>";
    checkPostgreSQLConnection();

    echo "<h3>MongoDB Status:</h3>";
    checkMongoDBConnection();

    // Example of using alert components
    echo "<br>";
    renderSuccessAlert("Layout system is working!");
    renderWarningAlert("User authentication will be implemented in the next steps.");
});

// Render the page using simple layout
renderLayout('AD-Task-3 - Database Status', $content, $currentUser);
?>