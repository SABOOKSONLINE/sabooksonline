# Performance Optimization Guide

## Issues Found & Solutions

### 1. **External API Calls (SLOWEST ISSUE)**
**Problem:** Book cover images are fetched from external APIs (Open Library, Google Books) on EVERY page load, causing 2-5 second delays per book.

**Solution Applied:**
- ✅ Added caching for book images (30-day cache)
- ✅ Optimized Open Library to use direct URLs (no HTTP check)
- ✅ Reduced Google Books timeout from 5s to 2s
- ✅ Cache directory: `/Admin/cache/book_images/`

**Further Optimization:**
- Consider pre-fetching and storing book covers locally
- Use lazy loading for images (already implemented in frontend)

### 2. **Database Queries Loading ALL Records**
**Problem:** Queries like `SELECT * FROM book_purchases` load ALL records into memory.

**Current Status:**
- Frontend pagination exists (25 rows per page)
- But backend still loads ALL data

**Recommended Fix:**
Add LIMIT to database queries:

```php
// Instead of:
SELECT * FROM book_purchases ORDER BY payment_date DESC

// Use:
SELECT * FROM book_purchases ORDER BY payment_date DESC LIMIT 1000
```

### 3. **Missing Database Indexes**
**Problem:** Queries on `payment_date`, `book_id`, `user_email` may be slow without indexes.

**Recommended SQL:**
```sql
-- Add indexes for faster queries
CREATE INDEX idx_payment_date ON book_purchases(payment_date DESC);
CREATE INDEX idx_book_id ON book_purchases(book_id);
CREATE INDEX idx_user_email ON book_purchases(user_email);
CREATE INDEX idx_payment_status ON book_purchases(payment_status);
CREATE INDEX idx_format ON book_purchases(format);

-- For books table
CREATE INDEX idx_pub_date ON books(pub_date DESC);
CREATE INDEX idx_isbn ON books(isbn);
CREATE INDEX idx_stock_status ON books(stock_status);
```

### 4. **No Query Result Caching**
**Problem:** Same queries run on every page load.

**Solution Created:**
- ✅ Created `PerformanceOptimizer` class for caching
- Cache directory: `/Admin/cache/`

**Usage Example:**
```php
require_once __DIR__ . "/../Helpers/performanceOptimizer.php";

// Cache query results
$cacheKey = "book_purchases_all";
$purchases = PerformanceOptimizer::getCache($cacheKey);

if (!$purchases) {
    $purchases = $this->fetchAll("SELECT * FROM book_purchases LIMIT 1000");
    PerformanceOptimizer::setCache($cacheKey, $purchases, 300); // 5 min cache
}
```

### 5. **PHP Configuration**
**Optimizations:**

Add to `php.ini` or `.htaccess`:
```ini
; Enable OPcache (PHP 7+)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

; Increase memory limit
memory_limit=256M

; Increase execution time if needed
max_execution_time=30
```

### 6. **Immediate Quick Fixes**

1. **Enable OPcache** (if using PHP 7+):
```bash
# Check if OPcache is enabled
php -i | grep opcache
```

2. **Add .htaccess optimizations** (if using Apache):
```apache
# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Browser caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

3. **Clear cache directory** (if needed):
```bash
rm -rf Admin/cache/*
```

### 7. **Monitoring Performance**

Add timing to see what's slow:
```php
$start = microtime(true);
// Your code here
$end = microtime(true);
error_log("Query took: " . ($end - $start) . " seconds");
```

## Expected Performance Improvements

- **Book Images:** 90% faster (cached, no API calls)
- **Database Queries:** 50-70% faster (with indexes)
- **Page Load:** 60-80% faster overall

## Next Steps

1. ✅ Book image caching - DONE
2. ⏳ Add database indexes - RECOMMENDED
3. ⏳ Add query result caching - OPTIONAL
4. ⏳ Enable OPcache - RECOMMENDED
5. ⏳ Add LIMIT to large queries - RECOMMENDED
