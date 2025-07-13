<?php
// Enhanced MongoDB Connection Checker with Auth Integration

require_once dirname(__DIR__) . '/utils/envSetter.util.php';

/**
 * Check MongoDB connection and return status
 */
function checkMongoDBConnection($returnData = false)
{
    global $typeConfig;

    $status = [
        'connected' => false,
        'message' => '',
        'details' => [],
        'databases' => [],
        'collections' => []
    ];

    try {
        $mongo = new MongoDB\Driver\Manager($typeConfig['mongoUri']);

        // Test connection with ping
        $command = new MongoDB\Driver\Command(["ping" => 1]);
        $cursor = $mongo->executeCommand("admin", $command);
        $response = $cursor->toArray()[0];

        if ($response->ok == 1) {
            $status['connected'] = true;
            $status['message'] = "✅ MongoDB Connection Successful";
            $status['details'][] = "URI: " . $typeConfig['mongoUri'];

            // List databases
            $listDatabases = new MongoDB\Driver\Command(["listDatabases" => 1]);
            $cursor = $mongo->executeCommand("admin", $listDatabases);
            $databases = $cursor->toArray()[0];

            if (isset($databases->databases)) {
                foreach ($databases->databases as $db) {
                    $status['databases'][] = $db->name;
                }
                $status['details'][] = "📊 Databases: " . implode(", ", $status['databases']);
            }

            // Check collections in main database
            $mainDb = trim($typeConfig['mongoDb'], '"') ?: 'drowning-database';
            try {
                $listCollections = new MongoDB\Driver\Command(["listCollections" => 1]);
                $cursor = $mongo->executeCommand($mainDb, $listCollections);
                $collections = $cursor->toArray();

                foreach ($collections as $collection) {
                    $status['collections'][] = $collection->name;
                }

                if (!empty($status['collections'])) {
                    $status['details'][] = "📑 Collections: " . implode(", ", $status['collections']);
                } else {
                    $status['details'][] = "📑 No collections found";
                }
            } catch (Exception $e) {
                $status['details'][] = "⚠️ Could not list collections: " . $e->getMessage();
            }

        } else {
            $status['message'] = "❌ MongoDB ping failed";
        }

    } catch (MongoDB\Driver\Exception\Exception $e) {
        $status['message'] = "❌ MongoDB connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        $status['message'] = "❌ MongoDB error: " . $e->getMessage();
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
 * Display MongoDB status for web interface
 */
function displayMongoDBStatus()
{
    $status = checkMongoDBConnection(true);

    echo "<div class='database-status mongodb-status'>";
    echo "<h3>🍃 MongoDB Status</h3>";

    if ($status['connected']) {
        echo "<p class='success'>" . $status['message'] . "</p>";
        echo "<ul>";
        foreach ($status['details'] as $detail) {
            echo "<li>" . $detail . "</li>";
        }
        echo "</ul>";

        if (!empty($status['collections'])) {
            echo "<p class='info'>✅ Document storage ready</p>";
        } else {
            echo "<p class='warning'>⚠️ No collections found - database is empty</p>";
        }
    } else {
        echo "<p class='error'>" . $status['message'] . "</p>";
        echo "<p class='warning'>❌ Document storage unavailable</p>";
    }

    echo "</div>";
}

// If called directly, run the check
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    checkMongoDBConnection();
}
