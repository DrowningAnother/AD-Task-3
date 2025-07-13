<?php
// User Profile Component
// Usage: include this file and call renderUserProfile($user)

function renderUserProfile($user)
{
    if (!$user) {
        echo "<p>No user logged in</p>";
        return;
    }

    ?>
    <div class="user-profile">
        <h3>User Profile</h3>
        <div class="profile-info">
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        </div>

        <div class="profile-actions">
            <a href="/logout.php">Logout</a>
        </div>
    </div>
    <?php
}
?>