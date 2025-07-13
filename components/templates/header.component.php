<?php
// Header Template Component
// Usage: include this file and call renderHeader($title, $currentUser)

function renderHeader($title = 'AD-Task-3', $currentUser = null)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title) ?></title>
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>

    <body>
        <?php
        // Include navigation component
        require_once BASE_PATH . '/components/componentGroup/navigation.component.php';
        renderNavigation($currentUser);
        ?>

        <header class="page-header">
            <div class="container">
                <h1><?= htmlspecialchars($title) ?></h1>
            </div>
        </header>

        <main class="main-content">
            <div class="container">
                <?php
}
?>