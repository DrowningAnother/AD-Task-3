<?php
// Navigation Component
// Usage: include this file and call renderNavigation($currentUser)

function renderNavigation($currentUser = null)
{
    ?>
    <nav class="main-navigation">
        <div class="nav-brand">
            <a href="/">AD-Task-3</a>
        </div>

        <div class="nav-links">
            <a href="/">Home</a>

            <?php if ($currentUser): ?>
                <a href="/dashboard.php">Dashboard</a>
                <span class="nav-user">
                    Welcome, <?= htmlspecialchars($currentUser['first_name']) ?>
                </span>
                <a href="/logout.php">Logout</a>
            <?php else: ?>
                <a href="/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}
?>