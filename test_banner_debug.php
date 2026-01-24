<?php
// Simple test file to debug banner creation
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔧 Banner Debug Test</h2>";

try {
    // Check if table exists
    require_once "Application/Core/Conn.php";
    require_once "Admin/Model/MobileBannerModel.php";
    
    echo "<h3>✅ Testing Database Connection</h3>";
    echo "Database connection: " . ($conn ? "✅ Connected" : "❌ Failed") . "<br>";
    
    $model = new MobileBannerModel($conn);
    echo "Model created: ✅<br>";
    
    echo "<h3>📋 Testing Table Creation</h3>";
    $model->createMobileBannerTable();
    echo "Table creation attempted: ✅<br>";
    
    // Check table structure
    $result = $conn->query("DESCRIBE Mobile_banners");
    if ($result) {
        echo "<h3>📊 Current Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>🧪 Testing Banner Insert</h3>";
    $testData = [
        "title" => "Test Banner Debug",
        "description" => "Testing banner insertion",
        "image_url" => "test_banner.jpg",
        "action_url" => "https://example.com",
        "screen" => "home",
        "priority" => 1,
        "start_date" => date('Y-m-d H:i:s'),
        "end_date" => null
    ];
    
    echo "Test data: <pre>" . print_r($testData, true) . "</pre>";
    
    $result = $model->addMobileBanner($testData);
    echo "Insert result: " . ($result > 0 ? "✅ Success (ID: $result)" : "❌ Failed") . "<br>";
    
    echo "<h3>📊 Current Banner Count</h3>";
    $banners = $model->getAllMobileBanners();
    echo "Total banners in database: " . count($banners) . "<br>";
    
    if (!empty($banners)) {
        echo "<h4>📋 Latest Banners:</h4>";
        foreach (array_slice($banners, 0, 5) as $banner) {
            echo "- ID: {$banner['id']}, Title: {$banner['title']}, Image: {$banner['image_url']}<br>";
        }
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<h3>📝 Recent Error Logs</h3>";
$logFile = "/var/log/apache2/error.log"; // Common location
if (file_exists($logFile)) {
    $logs = tail($logFile, 20);
    echo "<pre>" . htmlspecialchars($logs) . "</pre>";
} else {
    echo "Error log file not found at $logFile<br>";
    echo "Try checking PHP error log location with: " . ini_get('error_log') . "<br>";
}

function tail($filename, $lines = 10) {
    $handle = fopen($filename, "r");
    if (!$handle) return "";
    
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if (fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true;
                break;
            }
            $t = fgetc($handle);
            $pos--;
        }
        $linecounter--;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose($handle);
    return array_reverse($text);
}
?>