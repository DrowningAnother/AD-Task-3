<?php
declare(strict_types=1);

// 1) Composer autoload
require_once 'vendor/autoload.php';

// 2) Composer bootstrap
require_once 'bootstrap.php';

// 3) envSetter
require_once BASE_PATH . '/utils/envSetter.util.php';



$host = $typeConfig['pgHost'];
$port = $typeConfig['pgPort'];
$username = $typeConfig['pgUser'];
$password = $typeConfig['pgPass'];
$dbname = trim($typeConfig['pgDb'], '"');

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
echo "Dropping old tables…\n";
foreach ([
    'projects',
    'users',
] as $table) {
    // Use IF EXISTS so it won’t error if the table is already gone
    $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
}

// ——— Apply Users Schema ———
echo "Applying schema from database/users.model.sql…\n";

$sql = file_get_contents('database/users.model.sql');

if ($sql === false) {
    throw new RuntimeException("Could not read database/users.model.sql");
} else {
    echo "Creation Success from the database/users.model.sql";
}

$pdo->exec($sql);

