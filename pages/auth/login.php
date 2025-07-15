<?php
session_start();

require_once __DIR__ . '/../../bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/componentGroup/loginForm.component.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Redirect if already logged in
if (isUserLoggedIn()) {
    header('Location: /pages/dashboard/');
    exit;
}

$error = null;
$success = null;

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = authenticateUser($username, $password);

        if ($user) {
            // Login successful - use proper session function
            startUserSession($user);

            $success = 'Login successful! Redirecting...';
            header('refresh:2;url=/pages/dashboard/');
        } else {
            $error = 'Invalid username or password. Please check your credentials and try again.';
            // Debug info - remove this in production
            error_log("Login attempt failed for username: " . $username);
        }
    }
}

// Capture page content
$content = captureContent(function () use ($error, $success) {
    if ($error) {
        renderErrorAlert($error);
    }
    if ($success) {
        renderSuccessAlert($success);
    }

    echo '<div class="login-container">';
    echo '<div class="login-card">';
    renderLoginForm('/pages/auth/login.php');
    echo '</div>';
    echo '</div>';

    echo '<div class="login-help">';
    echo '<h3>Test Accounts:</h3>';
    echo '<p><strong>Username:</strong> john.smith<br><strong>Password:</strong> p@ssW0rd1234<br><strong>Role:</strong> designer</p>';
    echo '<p><strong>Username:</strong> admin<br><strong>Password:</strong> AdminPass999<br><strong>Role:</strong> admin</p>';
    echo '<p><strong>Username:</strong> mike.chen<br><strong>Password:</strong> StrongPwd789<br><strong>Role:</strong> manager</p>';
    echo '</div>';
});

// Render the page
renderLayout('Login - AD-Task-3', $content);
?>