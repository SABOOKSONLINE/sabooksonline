<?php
// controllers/ProviderProfileController.php

session_start();

if (!isset($_GET['provider'])) {
    echo 'Provider not specified';
    exit;
}

// Normalize and sanitize the provider identifier
$providerId = str_replace(['-', '_'], ' ', $_GET['provider']);

// DB connection
require_once __DIR__ . '/../config/connection.php';
include_once 'models/ProviderModel.php';

// Instantiate the model
$providerModel = new ProviderModel($conn);

// Fetch data
$providerData = $providerModel->getProviderById($providerId);

// Close DB
$conn->close();

// Include view
include 'views/provider_profile_view.php';
