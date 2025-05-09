<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/checkout') {
    require_once '../app/controllers/CheckoutController.php';
    (new CheckoutController())->buy($_POST['contentId']);
    exit;
}
