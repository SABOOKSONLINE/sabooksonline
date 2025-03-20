<?php
session_start();

// Error reporting
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Database connection script
include '../database_connections/sabooks.php';

$userkey = $_SESSION['ADMIN_USERKEY'];
$usertype = strtolower($_SESSION['ADMIN_TYPE']);
$name = strtolower($_SESSION['ADMIN_NAME']);
$reg_name = strtolower($_SESSION['ADMIN_NAME']);
$reg_email = strtolower($_SESSION['ADMIN_EMAIL']);

// Sanitize input data using prepared statements
$title = $_POST['book_title'];
$website = $_POST['book_website'];
$desc = $_POST['book_desc'];
$price = empty($_POST['book_price']) ? 0 : $_POST['book_price'];
$isbn = $_POST['book_isbn'];
$date_published = $_POST['book_date_published'];
$stock = $_POST['stock'];
$authors = $_POST['author']; 

// Initialize arrays for multi-select fields
$book_stores = isset($_POST['book_stores']) ? $_POST['book_stores'] : array();
$book_lang = isset($_POST['book_lang']) ? $_POST['book_lang'] : array();
$category = isset($_POST['book_category']) ? $_POST['book_category'] : array();

// Convert arrays to comma-separated strings
$book_stores_str = implode('|', $book_stores);
$book_lang_str = implode('|', $book_lang);
$category_str = implode('|', $category);

$book_store_code = '';

// Current time
$current_time = date('l jS \of F Y');

// Fetch user data using prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE ADMIN_USERKEY = ?");
$stmt->bind_param("s", $userkey);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$publisher = $row['ADMIN_NAME'];
$subscription = $row['ADMIN_SUBSCRIPTION'];

// Generate content ID
$contentid = substr(str_shuffle(time() . uniqid()), 4, 6);

// Handle file upload
$sourcePath = $_FILES['book_cover']['tmp_name'];

// Sanitize and strip tags for description (database-safe)
$desc_database = substr(strip_tags($desc), 0, 100);

// Generate folder name for book upload
$folder = strtolower(str_replace(' ', '-', $title));

// Include book permissions script
include 'scripts/functions/book_permissions.php';
?>
