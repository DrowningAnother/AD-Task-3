<?php
// Simple Layout - Combines header, content, and footer
// Usage: include this file and call renderLayout($title, $content, $currentUser)

require_once BASE_PATH . '/components/templates/header.component.php';
require_once BASE_PATH . '/components/templates/footer.component.php';

function renderLayout($title, $content, $currentUser = null)
{
    // Render header
    renderHeader($title, $currentUser);

    // Render main content
    echo $content;

    // Render footer
    renderFooter();
}

// Helper function to capture content output
function captureContent($callback)
{
    ob_start();
    $callback();
    return ob_get_clean();
}
?>