<?php
session_start();

require_once __DIR__ . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/componentGroup/userProfile.component.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Check if user is logged in
if (!isUserLoggedIn()) {
    header('Location: /errors/auth.error.php');
    exit;
}

// Get current user data
$currentUser = getCurrentUser();

if (!$currentUser) {
    // User not found, logout
    logoutUser();
    header('Location: /login.php');
    exit;
}

// Capture page content
$content = captureContent(function () use ($currentUser) {
    renderSuccessAlert("Welcome back, {$currentUser['first_name']}!");

    echo '<div class="dashboard-container">';

    // User profile section
    echo '<div class="dashboard-section">';
    renderUserProfile($currentUser);
    echo '</div>';

    // Dashboard content
    echo '<div class="dashboard-content">';
    echo '<h2>Dashboard</h2>';
    echo '<div class="dashboard-stats">';
    echo '<div class="stat-card">';
    echo '<h3>Welcome!</h3>';
    echo '<p>This is your personal dashboard. Authentication system is working correctly.</p>';
    echo '</div>';
    echo '</div>';

    echo '<div class="dashboard-actions">';
    echo '<h3>Quick Actions:</h3>';
    echo '<ul>';
    echo '<li><a href="/">Go to Homepage</a></li>';
    echo '<li><a href="/logout.php">Logout</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '</div>';

    echo '</div>';
});

// Render the page
renderLayout('Dashboard - AD-Task-3', $content, $currentUser);
?>