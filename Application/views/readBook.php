<?php
require_once __DIR__ . '/../controllers/BookController.php';

$controller = new BookController();

if (isset($_GET['ContentID'])) {
  $controller->readBook( $_GET['contentID']);
} else {
  echo "No book ID specified.";
}
