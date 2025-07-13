<?php
// Enhanced PostgreSQL Connection Checker with Auth Integration

require_once dirname(__DIR__) . '/utils/envSetter.util.php';
require_once dirname(__DIR__) . '/utils/auth.util.php';

/**
 * Check PostgreSQL connection and return status
 */
function checkPostgreSQLConnection($returnData = false)
{
    global $typeConfig;

    $host = $typeConfig['pgHost'];
    $port = $typeConfig['pgPort'];
    $username = $typeConfig['pgUser'];
    $password = $typeConfig['pgPass'];
    $dbname = trim($typeConfig['pgDb'], '"');

    $status = [
        'connected' => false,
        'message' => '',
        'details' => [],
        'users_count' => 0,
        'tables_exist' => false
    ];

    try {
        // Test basic connection
        $conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";
        $dbconn = pg_connect($conn_string);

        if (!$dbconn) {
            $status['message'] = "❌ Connection Failed: " . pg_last_error();
            if ($returnData)
                return $status;
            echo $status['message'] . "<br>";
            return false;
        }

        $status['connected'] = true;
        $status['message'] = "✅ PostgreSQL Connection Successful";
        $status['details'][] = "Host: $host:$port";
        $status['details'][] = "Database: $dbname";

        // Check if users table exists
        $query = "SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'users')";
        $result = pg_query($dbconn, $query);

        if ($result && pg_fetch_result($result, 0, 0) == 't') {
            $status['tables_exist'] = true;
            $status['details'][] = "✅ Users table exists";

            // Count users
            $count_query = "SELECT COUNT(*) FROM users";
            $count_result = pg_query($dbconn, $count_query);
            if ($count_result) {
                $count = pg_fetch_result($count_result, 0, 0);
                $status['users_count'] = (int) $count;
                $status['details'][] = "👥 Users in database: $count";
            }
        } else {
            $status['details'][] = "⚠️ Users table not found";
        }

        pg_close($dbconn);

    } catch (Exception $e) {
        $status['message'] = "❌ PostgreSQL Error: " . $e->getMessage();
        if ($returnData)
            return $status;
        echo $status['message'] . "<br>";
        return false;
    }

    if ($returnData) {
        return $status;
    }

    // Display results
    echo $status['message'] . "<br>";
    foreach ($status['details'] as $detail) {
        echo "&nbsp;&nbsp;" . $detail . "<br>";
    }

    return $status['connected'];
}

/**
 * Display PostgreSQL status for web interface
 */
function displayPostgreSQLStatus()
{
    $status = checkPostgreSQLConnection(true);

    echo "<div class='database-status postgresql-status'>";
    echo "<h3>🐘 PostgreSQL Status</h3>";

    if ($status['connected']) {
        echo "<p class='success'>" . $status['message'] . "</p>";
        echo "<ul>";
        foreach ($status['details'] as $detail) {
            echo "<li>" . $detail . "</li>";
        }
        echo "</ul>";

        if ($status['users_count'] > 0) {
            echo "<p class='info'>✅ Authentication system ready</p>";
        } else {
            echo "<p class='warning'>⚠️ No users found - run seeder to populate test data</p>";
        }
    } else {
        echo "<p class='error'>" . $status['message'] . "</p>";
        echo "<p class='warning'>❌ Authentication system unavailable</p>";
    }

    echo "</div>";
}

// If called directly, run the check
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    checkPostgreSQLConnection();
}