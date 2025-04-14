<?php
include_once 'models/ServiceProvider.php';
require_once __DIR__ . '/../config/connection.php';

$service = $_GET['service'] ?? null;

$serviceProviderModel = new ServiceProviderModel($conn);
$providers = $serviceProviderModel->getServiceProviders($service);

// Pass data to view
include 'views/service_providers_view.php';
?>
