<?php

require_once __DIR__ . '/../models/AnalysisModel.php';

class AnalysisController
{
    private $model;

    public function __construct($con)
    {
        $this->model = new AnalyticsModel($con);
    }

    // Dashboard analytics
    public function getNetIncome()
    {
        return $this->model->getNetIncome();
    }

    public function getTotalTransactions()
    {
        return $this->model->getTotalTransactions();
    }

    public function getTotalCustomers()
    {
        return $this->model->getTotalCustomers();
    }

    public function getPendingOrders()
    {
        return $this->model->getPendingOrders();
    }

    // Service analytics
    public function getServiceViews($provider_id, $start_date, $end_date)
    {
        return $this->model->getServiceViews($provider_id, $start_date, $end_date);
    }

    public function getUniqueServiceUsers($provider_id, $start_date, $end_date)
    {
        return $this->model->getUniqueServiceUsers($provider_id, $start_date, $end_date);
    }

    // Book analytics
    public function getBookViews($user_id, $start_date, $end_date)
    {
        return $this->model->getBookViews($user_id, $start_date, $end_date);
    }

    public function getUniqueBookUsers($user_id, $start_date, $end_date)
    {
        return $this->model->getUniqueBookUsers($user_id, $start_date, $end_date);
    }

    // Event analytics
    public function getEventViews($user_id, $start_date, $end_date)
    {
        return $this->model->getEventViews($user_id, $start_date, $end_date);
    }

    public function getUniqueEventUsers($user_id, $start_date, $end_date)
    {
        return $this->model->getUniqueEventUsers($user_id, $start_date, $end_date);
    }
}
