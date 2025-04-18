<?php
// controllers/ProviderProfileController.php

session_start();

require_once __DIR__ . '/../config/connection.php';
include_once 'models/ProviderModel.php';

/**
 * Class ProviderProfileController
 * Responsible for fetching and displaying a provider's profile.
 */
class ProviderProfileController
{
    private ProviderModel $providerModel;

    public function __construct(mysqli $conn)
    {
        $this->providerModel = new ProviderModel($conn);
    }

    /**
     * Show a provider profile based on the URL slug (e.g., "city-plumbing").
     *
     * @param string|null $slug Provider slug from the URL
     * @return void
     */
    public function showProfile(?string $slug): void
    {
        if (!$slug) {
            echo 'Provider not specified';
            exit;
        }

        // Convert slug to readable ID (e.g., "city-plumbing" → "city plumbing")
        $providerId = str_replace(['-', '_'], ' ', $slug);
        $providerData = $this->providerModel->getProviderById($providerId);

        if (!$providerData) {
            echo 'Provider not found';
            exit;
        }

        // Include view (assumes $providerData will be available inside it)
        include 'views/provider_profile_view.php';
    }
}

// Instantiate controller and run
$controller = new ProviderProfileController($conn);
$controller->showProfile($_GET['provider'] ?? null);

// Close DB
$conn->close();
