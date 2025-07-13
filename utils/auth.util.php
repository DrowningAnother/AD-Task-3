<?php
// Authentication utility functions

require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . '/envSetter.util.php';

/**
 * Get database connection
 */
function getDatabaseConnection()
{
    global $typeConfig;

    $host = $typeConfig['pgHost'];
    $port = $typeConfig['pgPort'];
    $username = $typeConfig['pgUser'];
    $password = $typeConfig['pgPass'];
    $dbname = trim($typeConfig['pgDb'], '"');

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}

/**
 * Get user by username from database
 */
function getUserByUsername($username)
{
    try {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting user: " . $e->getMessage());
        return false;
    }
}

/**
 * Authenticate user with username and password
 */
function authenticateUser($username, $password)
{
    $user = getUserByUsername($username);

    if (!$user) {
        return false;
    }

    if (password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

/**
 * Hash a password securely
 */
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 */
function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Start a user session
 */
function startUserSession($user)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['logged_in'] = true;
}

/**
 * Check if user is logged in
 */
function isUserLoggedIn()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get current logged in user data
 */
function getCurrentUser()
{
    if (!isUserLoggedIn()) {
        return false;
    }

    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'role' => $_SESSION['role'] ?? null,
        'first_name' => $_SESSION['first_name'] ?? null,
        'last_name' => $_SESSION['last_name'] ?? null,
    ];
}

/**
 * Logout user and destroy session
 */
function logoutUser()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();
}

/**
 * Check if user has specific role
 */
function userHasRole($role)
{
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

/**
 * Require user to be logged in (redirect if not)
 */
function requireLogin($redirectTo = '/login.php')
{
    if (!isUserLoggedIn()) {
        header("Location: {$redirectTo}");
        exit();
    }
}

/**
 * Require user to have specific role
 */
function requireRole($role, $redirectTo = '/')
{
    requireLogin();

    if (!userHasRole($role)) {
        header("Location: {$redirectTo}");
        exit();
    }
}

/**
 * Create a new user
 */
function createUser($username, $firstName, $lastName, $password, $role = 'user')
{
    try {
        $pdo = getDatabaseConnection();

        // Check if username already exists
        if (getUserByUsername($username)) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        $hashedPassword = hashPassword($password);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, first_name, last_name, password, role)
            VALUES (:username, :first_name, :last_name, :password, :role)
        ");

        $result = $stmt->execute([
            ':username' => $username,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'User created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create user'];
        }

    } catch (Exception $e) {
        error_log("Error creating user: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
