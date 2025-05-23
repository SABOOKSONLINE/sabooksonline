<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ServicesController
{
    private $serviceModel;

    public function __construct($conn)
    {
        $this->serviceModel = new ServiceModel($conn);
    }

    public function renderServicesByUserId($userId)
    {
        $services = $this->serviceModel->getServicesByUserId($userId);

        if ($services) {
            include __DIR__ . "/../views/includes/layouts/tables/services_table.php";
        }
    }

    public function renderServiceById($userId, $contentId)
    {
        $service = $this->serviceModel->getServiceById($userId, $contentId);

        include __DIR__ . "/../views/includes/layouts/forms/service_form.php";
    }

    public function insertServiceData($data)
    {
        $this->serviceModel->insertService($data);
    }

    public function updateServiceData($id, $data)
    {
        $this->serviceModel->updateService($id, $data);
    }

    public function deleteService($id)
    {
        $this->serviceModel->deleteService($id);
    }
}
