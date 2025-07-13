<?php
session_start();

require_once __DIR__ . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';
require_once BASE_PATH . '/layouts/main.layout.php';
require_once BASE_PATH . '/components/componentGroup/loginForm.component.php';
require_once BASE_PATH . '/components/templates/alert.component.php';

// Redirect if already logged in
if (isUserLoggedIn()) {
    header('Location: /dashboard.php');
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
            header('refresh:2;url=/dashboard.php');
        } else {
            $error = 'Invalid username or password.';
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
    renderLoginForm('/login.php');
    echo '</div>';
    echo '</div>';

    echo '<div class="login-help">';
    echo '<h3>Test Accounts:</h3>';
    echo '<p><strong>Username:</strong> john.smith<br><strong>Password:</strong> p@ssW0rd1234</p>';
    echo '<p><strong>Username:</strong> admin<br><strong>Password:</strong> AdminPass999</p>';
    echo '</div>';
});

// Render the page
renderLayout('Login - AD-Task-3', $content);
?>