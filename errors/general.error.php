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

// Get error details from query parameters or default values
$errorCode = $_GET['code'] ?? '500';
$errorMessage = $_GET['message'] ?? 'An unexpected error occurred';
$errorDetails = $_GET['details'] ?? 'Please try again later or contact support if the problem persists.';

// Set HTTP status code
http_response_code((int) $errorCode);

// Capture the page content
$content = captureContent(function () use ($currentUser, $errorCode, $errorMessage, $errorDetails) {
    renderErrorAlert($errorMessage);

    echo "<h2>Error {$errorCode}</h2>";
    echo "<p>{$errorDetails}</p>";

    // Show specific guidance based on error type
    if ($errorCode == '403') {
        echo "<p><strong>Access Denied:</strong> You don't have permission to access this resource.</p>";
        if (!$currentUser) {
            echo "<p>You may need to <a href='/login'>login</a> to access this page.</p>";
        }
    } elseif ($errorCode == '401') {
        echo "<p><strong>Authentication Required:</strong> Please login to access this resource.</p>";
    } elseif ($errorCode == '500') {
        echo "<p><strong>Server Error:</strong> Something went wrong on our end. Please try again later.</p>";
    }

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
    echo "<p><small>Error Code: {$errorCode} | " . date('Y-m-d H:i:s') . "</small></p>";
});

// Render the page using layout
renderLayout("Error {$errorCode}", $content, $currentUser);
?>