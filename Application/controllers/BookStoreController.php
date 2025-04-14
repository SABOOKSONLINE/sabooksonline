<?php
// Start session
session_start();

// Include DB connection and model
include '../includes/database_connections/sabooks.php';
include '../model/BookstoreModel.php';

// Create model instance
$model = new BookStoreModel($conn);

// Get optional province filter from URL
$provinceFilter = isset($_GET['province']) ? $_GET['province'] : null;

// Fetch bookstores
$bookstores = $model->fetchBookstores($provinceFilter);

// Pass data to view
include '../views/bookstoreListView.php';
?>
