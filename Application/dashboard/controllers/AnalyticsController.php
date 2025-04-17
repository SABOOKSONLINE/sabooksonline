<?php
require_once 'AnalyticsModel.php';
include '../includes/database_connections/sabooks.php';

/**
 * Class AnalyticsController
 *
 * Handles dashboard analytics logic, including access-level-based blurring of data.
 * Accepts a user context and delegates data fetching to AnalyticsModel.
 */
class AnalyticsController {
    /** @var AnalyticsModel */
    private $model;

    /** @var array Associative array containing user data (e.g., id, plan) */
    private $user;

    /**
     * AnalyticsController constructor.
     *
     * @param mysqli $con The Database connection
     * @param array $user Associative array containing user details (e.g., ['id' => 1, 'plan' => 'Free'])
     */
    public function __construct($con, $user) {
        $this->model = new AnalyticsModel($con);
        $this->user = $user;
    }

    /**
     * Retrieves dashboard data for a user, with conditional blurring if on Free plan.
     *
     * @param string $start_date The start date for analytics range (YYYY-MM-DD)
     * @param string $end_date The end date for analytics range (YYYY-MM-DD)
     *
     * @return void Outputs JSON directly with analytics data
     */
    public function getDashboardData($start_date, $end_date) {
        $isFreeUser = $this->user['plan'] === 'Free';
        $user_id = $this->user['id'];

        $data = [
            // Financial & sales metrics
            "netIncome" => $this->applyBlur($this->model->getNetIncome(), $isFreeUser),
            "totalTransactions" => $this->applyBlur($this->model->getTotalTransactions(), $isFreeUser),
            "totalCustomers" => $this->applyBlur($this->model->getTotalCustomers(), $isFreeUser),
            "pendingOrders" => $this->applyBlur($this->model->getPendingOrders(), $isFreeUser),

            // Book interaction metrics
            "bookViews" => $this->applyBlur(
                $this->model->getBookViews($user_id, $start_date, $end_date),
                $isFreeUser
            ),
            "uniqueBookUsers" => $this->applyBlur(
                $this->model->getUniqueBookUsers($user_id, $start_date, $end_date),
                $isFreeUser
            ),

            // Event interaction metrics
            "eventViews" => $this->applyBlur(
                $this->model->getEventViews($user_id, $start_date, $end_date),
                $isFreeUser
            ),
            "uniqueEventUsers" => $this->applyBlur(
                $this->model->getUniqueEventUsers($user_id, $start_date, $end_date),
                $isFreeUser
            ),

            // Blur flag for frontend conditional rendering
            "blurred" => $isFreeUser,
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Applies a teaser blur to analytics values if the user is on a Free plan.
     *
     * @param int|float $number The actual metric value
     * @param bool $blur Whether to apply the teaser blur
     *
     * @return string|int Returns blurred string (e.g., "~480") or full number if not blurred
     */
    private function applyBlur($number, $blur = false) {
        if (!$blur) return $number;

        // Show ~40%-60% of actual value for "teasing"
        $teased = round($number * (rand(4, 6) / 10));
        return "~" . number_format($teased);
    }
}
