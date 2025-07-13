<?php
// Alert Component
// Usage: include this file and call renderAlert($message, $type)

function renderAlert($message, $type = 'info', $allowHtml = false)
{
    $validTypes = ['success', 'error', 'warning', 'info'];
    $type = in_array($type, $validTypes) ? $type : 'info';

    ?>
    <div class="alert alert-<?= $type ?>">
        <?php if ($type === 'success'): ?>
            <span class="alert-icon">✅</span>
        <?php elseif ($type === 'error'): ?>
            <span class="alert-icon">❌</span>
        <?php elseif ($type === 'warning'): ?>
            <span class="alert-icon">⚠️</span>
        <?php else: ?>
            <span class="alert-icon">ℹ️</span>
        <?php endif; ?>

        <span class="alert-message"><?= $allowHtml ? $message : htmlspecialchars($message) ?></span>
    </div>
    <?php
}

function renderSuccessAlert($message)
{
    renderAlert($message, 'success');
}

function renderErrorAlert($message)
{
    renderAlert($message, 'error');
}

function renderWarningAlert($message)
{
    renderAlert($message, 'warning');
}

function renderWarningAlertWithHtml($message)
{
    renderAlert($message, 'warning', true);
}
?>