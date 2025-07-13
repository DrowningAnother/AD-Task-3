<?php
// Authentication handler - handles login/logout requests

require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/auth.util.php';

/**
 * Handle login form submission
 */
function handleLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false, 'message' => 'Invalid request method'];
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username)) {
        return ['success' => false, 'message' => 'Username is required'];
    }

    if (empty($password)) {
        return ['success' => false, 'message' => 'Password is required'];
    }

    // Attempt authentication
    $user = authenticateUser($username, $password);

    if ($user) {
        startUserSession($user);
        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user
        ];
    } else {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
}

/**
 * Handle logout request
 */
function handleLogout()
{
    if (isUserLoggedIn()) {
        logoutUser();
        return ['success' => true, 'message' => 'Logged out successfully'];
    } else {
        return ['success' => false, 'message' => 'No user logged in'];
    }
}

/**
 * Handle user registration
 */
function handleRegistration()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false, 'message' => 'Invalid request method'];
    }

    $username = trim($_POST['username'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Validate input
    if (empty($username)) {
        return ['success' => false, 'message' => 'Username is required'];
    }

    if (empty($firstName)) {
        return ['success' => false, 'message' => 'First name is required'];
    }

    if (empty($lastName)) {
        return ['success' => false, 'message' => 'Last name is required'];
    }

    if (empty($password)) {
        return ['success' => false, 'message' => 'Password is required'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters'];
    }

    if ($password !== $confirmPassword) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }

    // Validate username format (alphanumeric and dots only)
    if (!preg_match('/^[a-zA-Z0-9.]+$/', $username)) {
        return ['success' => false, 'message' => 'Username can only contain letters, numbers, and dots'];
    }

    // Create user
    return createUser($username, $firstName, $lastName, $password, $role);
}

/**
 * Process authentication request based on action
 */
function processAuthRequest()
{
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    switch ($action) {
        case 'login':
            return handleLogin();

        case 'logout':
            return handleLogout();

        case 'register':
            return handleRegistration();

        default:
            return ['success' => false, 'message' => 'Invalid action'];
    }
}

/**
 * Handle authentication with redirect
 */
function processAuthWithRedirect($successRedirect = '/', $errorRedirect = null)
{
    $result = processAuthRequest();

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($result['success']) {
        $_SESSION['auth_message'] = $result['message'];
        $_SESSION['auth_type'] = 'success';

        // For login, redirect to success page
        if (($_POST['action'] ?? '') === 'login') {
            header("Location: {$successRedirect}");
            exit();
        }

        // For logout, redirect to login page
        if (($_POST['action'] ?? '') === 'logout') {
            header("Location: /login.php");
            exit();
        }

        // For registration, redirect to login page
        if (($_POST['action'] ?? '') === 'register') {
            header("Location: /login.php");
            exit();
        }

    } else {
        $_SESSION['auth_message'] = $result['message'];
        $_SESSION['auth_type'] = 'error';

        // Redirect back to the form with error
        if ($errorRedirect) {
            header("Location: {$errorRedirect}");
            exit();
        } else {
            // Default redirect based on action
            $action = $_POST['action'] ?? '';
            if ($action === 'register') {
                header("Location: /register.php");
            } else {
                header("Location: /login.php");
            }
            exit();
        }
    }
}

/**
 * Get and clear auth message from session
 */
function getAuthMessage()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $message = $_SESSION['auth_message'] ?? null;
    $type = $_SESSION['auth_type'] ?? 'info';

    // Clear the message after retrieving it
    unset($_SESSION['auth_message']);
    unset($_SESSION['auth_type']);

    return $message ? ['message' => $message, 'type' => $type] : null;
}

/**
 * Display auth message if exists
 */
function displayAuthMessage()
{
    $authMessage = getAuthMessage();

    if ($authMessage) {
        $alertClass = $authMessage['type'] === 'success' ? 'alert-success' : 'alert-danger';
        echo "<div class='alert {$alertClass}' role='alert'>";
        echo htmlspecialchars($authMessage['message']);
        echo "</div>";
    }
}

/**
 * Generate CSRF token for forms
 */
function generateCSRFToken()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
