<?php
session_start();

require_once __DIR__ . '/../../bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Handle logout
if (isUserLoggedIn()) {
    $currentUser = getCurrentUser();
    $username = $currentUser['first_name'] ?? 'User';

    // Logout using auth utility
    logoutUser();

    $success_message = "Goodbye, {$username}! You have been logged out successfully.";
} else {
    $success_message = "You are already logged out.";
}

// Capture page content
$content = captureContent(function () use ($success_message) {
    renderSuccessAlert($success_message);

    echo '<div class="logout-container">';
    echo '<div class="logout-card">';
    echo '<h2>Logged Out</h2>';
    echo '<p>You have been successfully logged out of your account.</p>';
    echo '<div class="logout-actions">';
    echo '<a href="/pages/auth/login.php" class="btn btn-primary">Login Again</a>';
    echo '<a href="/" class="btn btn-secondary">Go to Homepage</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
});

// Render the page
renderLayout('Logout - AD-Task-3', $content);

// Redirect to login after 3 seconds
header('refresh:3;url=/pages/auth/login.php');
?>