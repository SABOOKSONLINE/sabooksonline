<?php
require_once '../config/connection.php';
require_once '../controllers/CheckoutController.php';

$controller = new CheckoutController($conn);
$controller->paymentNotify();
