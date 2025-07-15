<?php
require __DIR__ . '/bootstrap.php';

if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = BASE_PATH . $urlPath;

    // If physical file exists, serve it directly
    if (is_file($file)) {
        return false;
    }

    // Route to appropriate pages
    switch ($urlPath) {
        case '/':
        case '/index.php':
            require BASE_PATH . '/index.php';
            break;
        case '/login':
        case '/login.php':
        case '/pages/auth/login.php':
            require BASE_PATH . '/pages/auth/login.php';
            break;
        case '/logout':
        case '/logout.php':
        case '/pages/auth/logout.php':
            require BASE_PATH . '/pages/auth/logout.php';
            break;
        case '/dashboard':
        case '/dashboard.php':
        case '/pages/dashboard/':
        case '/pages/dashboard/index.php':
            require BASE_PATH . '/pages/dashboard/index.php';
            break;
        default:
            // Handle 404 - page not found
            http_response_code(404);
            require BASE_PATH . '/errors/404.error.php';
            break;
    }
} else {
    // For non-CLI server (Apache/Nginx), just load index
    require BASE_PATH . '/index.php';
}
