<?php
/**
 * Book Image Helper
 * Fetches book cover images using ISBN from various sources
 * WITH CACHING for performance
 */

// Cache directory for book images
$bookImageCacheDir = __DIR__ . '/../../cache/book_images/';
if (!is_dir($bookImageCacheDir)) {
    mkdir($bookImageCacheDir, 0755, true);
}

function getBookCoverImage(string $isbn, ?string $title = null, ?string $author = null): string
{
    if (empty($isbn) || $isbn === 'N/A') {
        return getDefaultBookCover();
    }

    // Clean ISBN (remove dashes and spaces)
    $cleanIsbn = preg_replace('/[^0-9X]/', '', $isbn);
    
    if (empty($cleanIsbn)) {
        return getDefaultBookCover();
    }

    // Check cache first (cache for 30 days)
    global $bookImageCacheDir;
    $cacheFile = $bookImageCacheDir . md5($cleanIsbn) . '.cache';
    
    if (file_exists($cacheFile)) {
        $cached = unserialize(file_get_contents($cacheFile));
        // Cache valid for 30 days
        if (time() - $cached['time'] < 2592000) {
            return $cached['url'];
        }
    }

    // Try multiple sources
    $imageUrl = null;

    // 1. Try Open Library API (most reliable and free) - FAST, no API call needed
    $imageUrl = getOpenLibraryCover($cleanIsbn);
    if ($imageUrl) {
        // Cache the result
        file_put_contents($cacheFile, serialize(['url' => $imageUrl, 'time' => time()]));
        return $imageUrl;
    }

    // 2. Try Google Books API (only if Open Library fails)
    $imageUrl = getGoogleBooksCover($cleanIsbn);
    if ($imageUrl) {
        // Cache the result
        file_put_contents($cacheFile, serialize(['url' => $imageUrl, 'time' => time()]));
        return $imageUrl;
    }

    // Return default if no image found (also cache this to avoid repeated API calls)
    $defaultUrl = getDefaultBookCover();
    file_put_contents($cacheFile, serialize(['url' => $defaultUrl, 'time' => time()]));
    return $defaultUrl;
}

/**
 * Get cover from Open Library API
 * OPTIMIZED: Direct URL construction, no HTTP check needed (Open Library returns placeholder if not found)
 */
function getOpenLibraryCover(string $isbn): ?string
{
    if (empty($isbn)) {
        return null;
    }
    
    // Open Library uses ISBN-13 format
    // Try both ISBN-10 and ISBN-13
    $isbn10 = convertToISBN10($isbn);
    $isbn13 = convertToISBN13($isbn);

    // Try large size first (best quality)
    // Open Library returns a placeholder image if book not found, so we can use direct URLs
    // This is MUCH faster than checking each URL
    $urls = [
        "https://covers.openlibrary.org/b/isbn/{$isbn13}-L.jpg",
        "https://covers.openlibrary.org/b/isbn/{$isbn10}-L.jpg",
    ];

    // Return first URL (Open Library handles missing covers gracefully)
    // We'll let the browser handle 404s, which is faster than checking server-side
    return !empty($isbn13) ? $urls[0] : (!empty($isbn10) ? $urls[1] : null);
}

/**
 * Get cover from Google Books API
 * OPTIMIZED: Only called if Open Library fails, with timeout
 */
function getGoogleBooksCover(string $isbn): ?string
{
    // Skip Google Books API if Open Library URL is available (faster)
    // Only use as fallback
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:{$isbn}";
    
    // Use shorter timeout for faster failure
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 2, // Reduced from 5 to 2 seconds
        CURLOPT_CONNECTTIMEOUT => 1, // Connection timeout
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = @curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $data = @json_decode($response, true);
        
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
            $imageUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
            // Replace small thumbnail with larger image
            $imageUrl = str_replace('zoom=1', 'zoom=3', $imageUrl);
            $imageUrl = str_replace('&edge=curl', '', $imageUrl);
            return $imageUrl;
        }
        
        // Try other image sizes
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['large'])) {
            return $data['items'][0]['volumeInfo']['imageLinks']['large'];
        }
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['medium'])) {
            return $data['items'][0]['volumeInfo']['imageLinks']['medium'];
        }
        if (isset($data['items'][0]['volumeInfo']['imageLinks']['small'])) {
            return $data['items'][0]['volumeInfo']['imageLinks']['small'];
        }
    }

    return null;
}

/**
 * Check if image URL exists and is accessible
 * NOTE: This is slow - avoid using it. Let browser handle 404s instead.
 */
function checkImageExists(string $url): bool
{
    // Skip HTTP check for performance - let browser handle it
    // This function is kept for backward compatibility but not recommended
    return true; // Assume exists, browser will handle 404
}

/**
 * Convert ISBN-13 to ISBN-10
 */
function convertToISBN10(string $isbn): string
{
    // Remove dashes and spaces
    $isbn = preg_replace('/[^0-9X]/', '', $isbn);
    
    // If already ISBN-10, return as is
    if (strlen($isbn) === 10) {
        return $isbn;
    }
    
    // If ISBN-13, convert to ISBN-10
    if (strlen($isbn) === 13 && substr($isbn, 0, 3) === '978') {
        $isbn10 = substr($isbn, 3, 9);
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$isbn10[$i] * (10 - $i);
        }
        $checkDigit = (11 - ($sum % 11)) % 11;
        $checkDigit = $checkDigit === 10 ? 'X' : (string)$checkDigit;
        return $isbn10 . $checkDigit;
    }
    
    return $isbn;
}

/**
 * Convert ISBN-10 to ISBN-13
 */
function convertToISBN13(string $isbn): string
{
    // Remove dashes and spaces
    $isbn = preg_replace('/[^0-9X]/', '', $isbn);
    
    // If already ISBN-13, return as is
    if (strlen($isbn) === 13) {
        return $isbn;
    }
    
    // If ISBN-10, convert to ISBN-13
    if (strlen($isbn) === 10) {
        $isbn13 = '978' . substr($isbn, 0, 9);
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $multiplier = ($i % 2 === 0) ? 1 : 3;
            $sum += (int)$isbn13[$i] * $multiplier;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;
        return $isbn13 . $checkDigit;
    }
    
    return $isbn;
}

/**
 * Get default book cover placeholder
 */
function getDefaultBookCover(): string
{
    // You can use a local placeholder or a service like placeholder.com
    return 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="300" viewBox="0 0 200 300">
            <rect width="200" height="300" fill="#e5e7eb"/>
            <text x="50%" y="50%" font-family="Arial" font-size="16" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">
                No Cover
            </text>
        </svg>
    ');
}
