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
http_response_code(401);

// Capture the page content
$content = captureContent(function () use ($currentUser) {
    renderErrorAlert("Authentication required to access this page.");

    echo "<h2>401 - Authentication Required</h2>";
    if ($currentUser) {
        echo "<p>You are logged in as <strong>{$currentUser['first_name']} {$currentUser['last_name']}</strong>, but you don't have permission to access this resource.</p>";
        echo "<p>If you believe this is an error, please contact the administrator.</p>";
    } else {
        echo "<p>You need to be logged in to access this page.</p>";
        echo "<p>Please login with your credentials to continue.</p>";
    }

    echo "<h3>What you can do:</h3>";
    echo "<ul>";
    echo "<li><a href='/'>Go to Home Page</a></li>";
    if ($currentUser) {
        echo "<li><a href='/dashboard'>Go to Dashboard</a></li>";
        echo "<li><a href='/logout'>Logout and try different account</a></li>";
    } else {
        echo "<li><strong><a href='/login'>Login Now</a></strong></li>";
    }
    echo "</ul>";

    echo "<hr>";
    echo "<p><small>Error Code: 401 | Authentication Required</small></p>";
});

// Render the page using layout
renderLayout('401 - Authentication Required', $content, $currentUser);
?>