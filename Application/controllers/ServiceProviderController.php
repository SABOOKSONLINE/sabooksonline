<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ServiceProviderController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new ServiceProviderModel($conn);
    }

    public function renderServiceProvider($service)
    {
        $providers = $this->model->getServiceProviders($service);

        if ($providers) {
            include __DIR__ . "/../views/layout/serviceProviderCard.php";
        } else {
            echo "No services found.";
        }
    }
}
