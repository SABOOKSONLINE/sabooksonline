<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/xml");

require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/Onix/OnixGenerator.php';

$bookModel = new BookModel($conn);
$books = $bookModel->getBooks();

$onixXml = OnixGenerator::generate($books);

echo $onixXml;
