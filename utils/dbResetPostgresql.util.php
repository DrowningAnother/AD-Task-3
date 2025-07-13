<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once BASE_PATH . '/utils/envSetter.util.php';

// ——— Connect to PostgreSQL ———
$host = $typeConfig['pgHost'];
$port = $typeConfig['pgPort'];
$username = $typeConfig['pgUser'];
$password = $typeConfig['pgPass'];
$dbname = trim($typeConfig['pgDb'], '"');

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

echo "Connected to PostgreSQL successfully!\n";

// ——— Apply Users Schema ———
echo "Applying schema from database/users.model.sql…\n";
$sql = file_get_contents('database/users.model.sql');
if ($sql === false) {
    throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql\n";
}
$pdo->exec($sql);

// ——— Apply Projects Schema ———
echo "Applying schema from database/projects.model.sql…\n";
$sql = file_get_contents('database/projects.model.sql');
if ($sql === false) {
    throw new RuntimeException("Could not read database/projects.model.sql");
} else {
    echo "Creation Success from the database/projects.model.sql\n";
}
$pdo->exec($sql);

// ——— Apply Tasks Schema ———
echo "Applying schema from database/tasks.model.sql…\n";
$sql = file_get_contents('database/tasks.model.sql');
if ($sql === false) {
    throw new RuntimeException("Could not read database/tasks.model.sql");
} else {
    echo "Creation Success from the database/tasks.model.sql\n";
}
$pdo->exec($sql);

// ——— Apply Project Users Schema ———
echo "Applying schema from database/project_users.model.sql…\n";
$sql = file_get_contents('database/project_users.model.sql');
if ($sql === false) {
    throw new RuntimeException("Could not read database/project_users.model.sql");
} else {
    echo "Creation Success from the database/project_users.model.sql\n";
}
$pdo->exec($sql);

// ——— Clean Tables ———
echo "Truncating tables…\n";
foreach (['project_users', 'tasks', 'projects', 'users'] as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
}

echo "✅ PostgreSQL reset complete!\n";
