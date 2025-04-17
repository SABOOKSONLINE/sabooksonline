<?php
require_once 'AnalyticsModel.php';

class AnalyticsController {
    private $model;
    private $user;

    public function __construct($con, $user) {
        $this->model = new AnalyticsModel($con);
        $this->user = $user; // associative array with plan, id, etc.
    }

    public function getDashboardData($start_date, $end_date) {
        $isFreeUser = $this->user['plan'] === 'Free';
        $user_id = $this->user['id'];

        $data = [
            "netIncome" => $this->applyBlur($this->model->getNetIncome(), $isFreeUser),
            "totalTransactions" => $this->applyBlur($this->model->getTotalTransactions(), $isFreeUser),
            "totalCustomers" => $this->applyBlur($this->model->getTotalCustomers(), $isFreeUser),
            "pendingOrders" => $this->applyBlur($this->model->getPendingOrders(), $isFreeUser),
            "bookViews" => $this->applyBlur($this->model->getBookViews($user_id, $start_date, $end_date), $isFreeUser),
            "uniqueBookUsers" => $this->applyBlur($this->model->getUniqueBookUsers($user_id, $start_date, $end_date), $isFreeUser),
            "eventViews" => $this->applyBlur($this->model->getEventViews($user_id, $start_date, $end_date), $isFreeUser),
            "uniqueEventUsers" => $this->applyBlur($this->model->getUniqueEventUsers($user_id, $start_date, $end_date), $isFreeUser),
            "blurred" => $isFreeUser,
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function applyBlur($number, $blur = false) {
        if (!$blur) return $number;

        // Tease: show around 40%-60% of actual value
        $teased = round($number * (rand(4, 6) / 10));
        return "~" . number_format($teased); // the "~" symbol hints it's not exact
    }
}
