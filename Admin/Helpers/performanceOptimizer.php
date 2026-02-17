<?php
/**
 * Performance Optimizer Helper
 * Provides caching and optimization utilities
 */

class PerformanceOptimizer
{
    private static $cacheDir = __DIR__ . '/../../cache/';
    private static $cacheEnabled = true;
    private static $cacheTTL = 3600; // 1 hour default

    /**
     * Initialize cache directory
     */
    public static function init(): void
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }
    }

    /**
     * Get cached data
     */
    public static function getCache(string $key): ?array
    {
        if (!self::$cacheEnabled) {
            return null;
        }

        self::init();
        $cacheFile = self::$cacheDir . md5($key) . '.cache';

        if (!file_exists($cacheFile)) {
            return null;
        }

        $data = unserialize(file_get_contents($cacheFile));
        
        if (time() > $data['expires']) {
            unlink($cacheFile);
            return null;
        }

        return $data['content'];
    }

    /**
     * Set cache data
     */
    public static function setCache(string $key, array $data, int $ttl = null): void
    {
        if (!self::$cacheEnabled) {
            return;
        }

        self::init();
        $cacheFile = self::$cacheDir . md5($key) . '.cache';
        $ttl = $ttl ?? self::$cacheTTL;

        $cacheData = [
            'content' => $data,
            'expires' => time() + $ttl,
            'created' => time()
        ];

        file_put_contents($cacheFile, serialize($cacheData));
    }

    /**
     * Clear cache by key pattern
     */
    public static function clearCache(string $pattern = '*'): void
    {
        self::init();
        $files = glob(self::$cacheDir . md5($pattern) . '.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Clear all cache
     */
    public static function clearAllCache(): void
    {
        self::init();
        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Enable/disable cache
     */
    public static function setCacheEnabled(bool $enabled): void
    {
        self::$cacheEnabled = $enabled;
    }
}
