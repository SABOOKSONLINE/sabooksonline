<?php
/**
 * Quick Performance Optimization Script
 * Run this once to add database indexes and clear caches
 */

require_once __DIR__ . "/Core/Conn.php";

echo "🚀 Starting Performance Optimization...\n\n";

// 1. Add Database Indexes
echo "1. Adding database indexes...\n";

$indexes = [
    "CREATE INDEX IF NOT EXISTS idx_payment_date ON book_purchases(payment_date DESC)",
    "CREATE INDEX IF NOT EXISTS idx_book_id ON book_purchases(book_id)",
    "CREATE INDEX IF NOT EXISTS idx_user_email ON book_purchases(user_email)",
    "CREATE INDEX IF NOT EXISTS idx_payment_status ON book_purchases(payment_status)",
    "CREATE INDEX IF NOT EXISTS idx_format ON book_purchases(format)",
    "CREATE INDEX IF NOT EXISTS idx_pub_date ON books(pub_date DESC)",
    "CREATE INDEX IF NOT EXISTS idx_isbn ON books(isbn)",
    "CREATE INDEX IF NOT EXISTS idx_stock_status ON books(stock_status)",
    "CREATE INDEX IF NOT EXISTS idx_posts_contentid ON posts(CONTENTID)",
    "CREATE INDEX IF NOT EXISTS idx_posts_userid ON posts(USERID)",
];

$successCount = 0;
$errorCount = 0;

foreach ($indexes as $index) {
    try {
        $conn->query($index);
        echo "  ✅ Index created successfully\n";
        $successCount++;
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "  ⚠️  Index already exists (skipping)\n";
        } else {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
}

echo "\n2. Creating cache directories...\n";

// 2. Create cache directories
$cacheDirs = [
    __DIR__ . "/cache",
    __DIR__ . "/cache/book_images",
];

foreach ($cacheDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "  ✅ Created: $dir\n";
    } else {
        echo "  ⚠️  Already exists: $dir\n";
    }
}

echo "\n3. Checking PHP OPcache...\n";

// 3. Check OPcache status
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    if ($status && $status['opcache_enabled']) {
        echo "  ✅ OPcache is ENABLED\n";
        echo "  📊 Memory used: " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB\n";
        echo "  📊 Cached scripts: " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
    } else {
        echo "  ⚠️  OPcache is DISABLED (enable in php.ini for better performance)\n";
    }
} else {
    echo "  ⚠️  OPcache extension not available\n";
}

echo "\n✅ Optimization complete!\n";
echo "📊 Summary:\n";
echo "   - Indexes created: $successCount\n";
echo "   - Errors: $errorCount\n";
echo "\n💡 Next steps:\n";
echo "   1. Clear browser cache\n";
echo "   2. Test page load times\n";
echo "   3. Monitor performance improvements\n";
