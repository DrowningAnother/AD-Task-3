<?php
session_start();

require_once __DIR__ . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once HANDLERS_PATH . '/mongodbChecker.handler.php';
require_once HANDLERS_PATH . '/postgreChecker.handler.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Check if user is logged in
$currentUser = null;
if (isUserLoggedIn()) {
    $currentUser = getCurrentUser();
}

// Capture the page content
$content = captureContent(function () use ($currentUser) {
    if ($currentUser) {
        renderSuccessAlert("Welcome back, {$currentUser['first_name']}! You are logged in.");
    } else {
        renderWarningAlertWithHtml("You are not logged in. <a href='/login.php'>Login here</a> to access the dashboard.");
    }

    echo "<h2>Database Connection Status</h2>";

    echo "<h3>PostgreSQL Status:</h3>";
    checkPostgreSQLConnection();

    echo "<h3>MongoDB Status:</h3>";
    checkMongoDBConnection();

    echo "<br>";
    renderSuccessAlert("Authentication and layout system is working!");

    // Quick links
    echo "<h3>Quick Links:</h3>";
    echo "<ul>";
    if ($currentUser) {
        echo "<li><a href='/dashboard.php'>Go to Dashboard</a></li>";
        echo "<li><a href='/logout.php'>Logout</a></li>";
    } else {
        echo "<li><a href='/login.php'>Login</a></li>";
    }
    echo "</ul>";
});

// Render the page using layout
renderLayout('AD-Task-3 - Home', $content, $currentUser);
?>