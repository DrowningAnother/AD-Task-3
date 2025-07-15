<?php
session_start();

require_once __DIR__ . '/../bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Check if user is logged in
$currentUser = null;
if (isUserLoggedIn()) {
    $currentUser = getCurrentUser();
}

// Set HTTP status code
http_response_code(404);

// Capture the page content
$content = captureContent(function () use ($currentUser) {
    renderErrorAlert("The page you are looking for could not be found.");

    echo "<h2>404 - Page Not Found</h2>";
    echo "<p>Sorry, the page you are looking for doesn't exist or has been moved.</p>";

    echo "<h3>What you can do:</h3>";
    echo "<ul>";
    echo "<li><a href='/'>Go to Home Page</a></li>";
    if ($currentUser) {
        echo "<li><a href='/dashboard'>Go to Dashboard</a></li>";
        echo "<li><a href='/logout'>Logout</a></li>";
    } else {
        echo "<li><a href='/login'>Login</a></li>";
    }
    echo "</ul>";

    echo "<hr>";
    echo "<p><small>Error Code: 404 | Page Not Found</small></p>";
});

// Render the page using layout
renderLayout('404 - Page Not Found', $content, $currentUser);
?>