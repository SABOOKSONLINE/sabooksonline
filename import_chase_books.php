<?php
/**
 * Import CHASE Master Pricelist Excel file to academic_books database
 * 
 * Usage:
 * 1. Export Excel file to CSV format
 * 2. Update the $csvFile path below
 * 3. Run: php import_chase_books.php
 * 
 * OR
 * 
 * 1. Enable fileinfo, gd, and zip extensions in php.ini
 * 2. Install: composer require phpoffice/phpspreadsheet
 * 3. Update the $excelFile path below
 * 4. Run: php import_chase_books.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/connection.php';
require_once __DIR__ . '/Dashboard/models/AcademicBookModel.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Configuration
$excelFile = __DIR__ . DIRECTORY_SEPARATOR . "CHASE Master Pricelist July 2025_2026.xlsx";
$csvFile = __DIR__ . DIRECTORY_SEPARATOR . "CHASE Master Pricelist July 2025_2026.csv";
$publisherId = 1203; // Update with your publisher ID
$useExcel = true; // Set to true to use Excel, false for CSV

// Column mapping - Update these based on your Excel/CSV column names
$columnMapping = [
    'title' => 'Title',                              // Excel column name for title
    'isbn' => 'ISBN',                                // Excel column name for ISBN
    'author' => 'Author',                            // Excel column name for author
    'publisher' => 'Publisher',                      // Excel column name for publisher
    'price' => 'Chase UNIT PRICE incl. VAT',         // Excel column name for price
    'subject' => 'Language Designation',            // CSV Language Designation maps to db subject
    'level' => 'Grade/Phase',                        // Excel column name for level/grade
    'language' => 'Language',                        // Excel column name for language (for that book)
    'format' => 'Format',                            // Excel column name for Format (to filter out teacher/resource)
    'series' => 'Series',                            // Excel column name for series (if exists)
    'description' => 'Description',                  // Excel column name for description (if exists)
    'editor' => 'Editor',                            // Excel column name for editor (if exists)
];

function cleanValue($value) {
    if (is_null($value)) return '';
    $value = strval($value);
    return trim($value);
}

function parsePrice($value) {
    $value = cleanValue($value);
    // Remove currency symbols and spaces
    $value = preg_replace('/[R\s,]/', '', $value);
    return floatval($value);
}

function parseDate($value) {
    $value = cleanValue($value);
    if (empty($value)) return null;
    
    // Try to parse Excel date format or standard date formats
    if (is_numeric($value)) {
        // Excel date serial number
        $timestamp = ($value - 25569) * 86400; // Excel epoch is 1900-01-01
        return date('Y-m-d', $timestamp);
    }
    
    // Try standard date parsing
    $date = date_create($value);
    if ($date) {
        return date_format($date, 'Y-m-d');
    }
    
    return null;
}

function importFromCSV($filePath, $conn, $publisherId, $columnMapping) {
    $academicModel = new AcademicBookModel($conn);
    $imported = 0;
    $errors = [];
    
    if (!file_exists($filePath)) {
        die("CSV file not found: $filePath\n");
    }
    
    $handle = fopen($filePath, 'r');
    if (!$handle) {
        die("Cannot open CSV file: $filePath\n");
    }
    
    // Read header row
    $headers = fgetcsv($handle);
    if (!$headers) {
        die("Cannot read CSV headers\n");
    }
    
    // Normalize headers (remove spaces, convert to lowercase for matching)
    $headerMap = [];
    foreach ($headers as $index => $header) {
        $header = $header ?? '';
        $normalized = strtolower(trim($header));
        $headerMap[$normalized] = $index;
    }
    
    echo "Found columns: " . implode(", ", $headers) . "\n\n";
    
    // Check if Format column exists
    $formatColumnExists = false;
    foreach ($headers as $header) {
        if (stripos($header, 'format') !== false) {
            $formatColumnExists = true;
            echo "✓ Found 'Format' column for filtering teacher guides/resources\n";
            break;
        }
    }
    if (!$formatColumnExists) {
        echo "⚠ Warning: 'Format' column not found. Teacher guide filtering may not work.\n";
    }
    
    echo "Starting import...\n\n";
    
    $rowNum = 1;
    while (($row = fgetcsv($handle)) !== false) {
        $rowNum++;
        
        // Skip empty rows
        if (empty(array_filter($row))) continue;
        
        try {
            // Map columns
            $getColumn = function($key) use ($row, $headerMap, $columnMapping) {
                $excelCol = $columnMapping[$key] ?? '';
                if (empty($excelCol)) return '';
                
                // Try exact match first
                foreach ($headerMap as $header => $index) {
                    if (strtolower(trim($excelCol)) === $header) {
                        return isset($row[$index]) ? $row[$index] : '';
                    }
                }
                
                // Try partial match
                foreach ($headers as $index => $header) {
                    if (stripos($header, $excelCol) !== false || stripos($excelCol, $header) !== false) {
                        return isset($row[$index]) ? $row[$index] : '';
                    }
                }
                
                return '';
            };
            
            $title = cleanValue($getColumn('title'));
            $isbn = cleanValue($getColumn('isbn'));
            
            // Skip if no title or ISBN
            if (empty($title) && empty($isbn)) {
                continue;
            }
            
            // Skip teacher resources and guides - filter by Format column from CSV
            $formatValue = $getColumn('format');
            $format = !empty($formatValue) ? strtolower(cleanValue($formatValue)) : '';
            if (!empty($format) && (stripos($format, 'teacher') !== false || stripos($format, 'resource') !== false || stripos($format, 'guide') !== false)) {
                continue;
            }
            
            $bookData = [
                'publisher_id' => $publisherId,
                'public_key' => bin2hex(random_bytes(16)),
                'title' => $title ?: 'Untitled',
                'author' => cleanValue($getColumn('author')),
                'editor' => 'Chase Education', // Set to Chase Education
                'description' => cleanValue($getColumn('description')), // If exists in CSV
                'subject' => cleanValue($getColumn('subject')), // Language Designation from CSV maps to subject
                'level' => cleanValue($getColumn('level')), // Grade/Phase
                'language' => cleanValue($getColumn('language')), // Language for that book
                'edition' => cleanValue($getColumn('series')), // Map Series from CSV to edition
                'pages' => '', // Not in CSV
                'isbn' => $isbn,
                'publish_date' => null, // Not in CSV
                'cover_image_path' => null,
                'ebook_price' => parsePrice($getColumn('price')),
                'pdf_path' => null,
                'physical_book_price' => parsePrice($getColumn('price')),
                'link' => '',
                'approved' => 0, // Default: not approved
                'aligned' => 0, // Default: not aligned
                'status' => null // Default: no status
            ];
            
            $academicModel->insertBook($bookData);
            $imported++;
            
            if ($imported % 10 === 0) {
                echo "Imported $imported books...\n";
            }
            
        } catch (Exception $e) {
            $errors[] = "Row $rowNum: " . $e->getMessage();
            echo "Error on row $rowNum: " . $e->getMessage() . "\n";
        }
    }
    
    fclose($handle);
    
    echo "\n\nImport complete!\n";
    echo "Successfully imported: $imported books\n";
    if (!empty($errors)) {
        echo "Errors: " . count($errors) . "\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }
}

function importFromExcel($filePath, $conn, $publisherId, $columnMapping) {
    $academicModel = new AcademicBookModel($conn);
    $imported = 0;
    $errors = [];
    
    if (!file_exists($filePath)) {
        die("Excel file not found: $filePath\n");
    }
    
    echo "Loading Excel file...\n";
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    
    // Read header row
    $headers = [];
    $colIndex = 0;
    while ($colIndex <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn)) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
        $headers[] = $worksheet->getCell($colLetter . '1')->getValue();
        $colIndex++;
    }
    
    echo "Found columns: " . implode(", ", $headers) . "\n\n";
    echo "Total rows: $highestRow\n";
    echo "Starting import...\n\n";
    
    // Create header map
    $headerMap = [];
    foreach ($headers as $index => $header) {
        if ($header === null) {
            $header = '';
        }
        $normalized = strtolower(trim($header));
        $headerMap[$normalized] = $index;
    }
    
    // Create column index map
    $columnIndexMap = [];
    foreach ($columnMapping as $key => $excelCol) {
        foreach ($headerMap as $header => $index) {
            if (strtolower(trim($excelCol)) === $header) {
                $columnIndexMap[$key] = $index;
                break;
            }
        }
        // If not found, try partial match
        if (!isset($columnIndexMap[$key])) {
            foreach ($headers as $index => $header) {
                $header = $header ?? '';
                $excelCol = $excelCol ?? '';
                if (!empty($header) && !empty($excelCol) && (stripos($header, $excelCol) !== false || stripos($excelCol, $header) !== false)) {
                    $columnIndexMap[$key] = $index;
                    break;
                }
            }
        }
    }
    
    for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
        try {
            // Helper function to get cell value by column key
            $getColumn = function($key) use ($worksheet, $columnIndexMap, $rowNum) {
                if (!isset($columnIndexMap[$key])) return '';
                $colIndex = $columnIndexMap[$key];
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $value = $worksheet->getCell($colLetter . $rowNum)->getValue();
                return $value !== null ? $value : '';
            };
            
            // Get values using column mapping
            $title = cleanValue($getColumn('title'));
            $isbn = cleanValue($getColumn('isbn'));
            
            // Skip if no title or ISBN
            if (empty($title) && empty($isbn)) {
                continue;
            }
            
            // Skip teacher resources and guides - filter by Format column from Excel/CSV
            $format = strtolower(cleanValue($getColumn('format')));
            if (!empty($format) && (stripos($format, 'teacher') !== false || stripos($format, 'resource') !== false || stripos($format, 'guide') !== false)) {
                // Skip this row - it's a teacher guide/resource
                continue;
            }
            
            $bookData = [
                'publisher_id' => $publisherId,
                'public_key' => bin2hex(random_bytes(16)),
                'title' => $title ?: 'Untitled',
                'author' => cleanValue($getColumn('author')),
                'editor' => 'Chase Education', // Set to Chase Education
                'description' => cleanValue($getColumn('description')), // If exists in CSV
                'subject' => cleanValue($getColumn('subject')), // Language Designation from CSV maps to subject
                'level' => cleanValue($getColumn('level')), // Grade/Phase
                'language' => cleanValue($getColumn('language')), // Language for that book
                'edition' => cleanValue($getColumn('series')), // Map Series from CSV to edition
                'pages' => '', // Not in CSV
                'isbn' => $isbn,
                'publish_date' => null, // Not in CSV
                'cover_image_path' => null,
                'ebook_price' => parsePrice($getColumn('price')),
                'pdf_path' => null,
                'physical_book_price' => parsePrice($getColumn('price')),
                'link' => '',
                'approved' => 0, // Default: not approved
                'aligned' => 0, // Default: not aligned
                'status' => null // Default: no status
            ];
            
            $academicModel->insertBook($bookData);
            $imported++;
            
            if ($imported % 10 === 0) {
                echo "Imported $imported books...\n";
            }
            
        } catch (Exception $e) {
            $errors[] = "Row $rowNum: " . $e->getMessage();
            echo "Error on row $rowNum: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n\nImport complete!\n";
    echo "Successfully imported: $imported books\n";
    if (!empty($errors)) {
        echo "Errors: " . count($errors) . "\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }
}

// Main execution
try {
    // Normalize file paths
    $excelFile = realpath($excelFile) ?: $excelFile;
    $csvFile = realpath($csvFile) ?: $csvFile;
    
    if ($useExcel && class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        // Excel import using PhpSpreadsheet
        echo "Using Excel import (PhpSpreadsheet)\n";
        if (!file_exists($excelFile)) {
            die("Excel file not found: $excelFile\n");
        }
        importFromExcel($excelFile, $conn, $publisherId, $columnMapping);
    } else {
        // CSV import
        echo "Using CSV import\n";
        if (!file_exists($csvFile)) {
            echo "CSV file not found at: $csvFile\n";
            echo "Attempting to use Excel file instead...\n";
            if (file_exists($excelFile) && class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                echo "Found Excel file, switching to Excel import...\n";
                importFromExcel($excelFile, $conn, $publisherId, $columnMapping);
            } else {
                die("Neither CSV nor Excel file found.\nPlease:\n1. Export Excel to CSV and update \$csvFile path, OR\n2. Install PhpSpreadsheet (composer require phpoffice/phpspreadsheet) and update \$excelFile path\n");
            }
        } else {
            importFromCSV($csvFile, $conn, $publisherId, $columnMapping);
        }
    }
} catch (Exception $e) {
    die("Fatal error: " . $e->getMessage() . "\n");
}
