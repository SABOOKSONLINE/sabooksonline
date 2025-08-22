<?php
class BillingModel {
    private $plans = [
        "Pro-Monthly" => ["name" => "Pro", "billing" => "Monthly", "amount" => 199],
        "Pro-Yearly"  => ["name" => "Pro", "billing" => "Yearly", "amount" => 1911],
        "Premium-Monthly" => ["name" => "Premium", "billing" => "Monthly", "amount" => 499],
        "Premium-Yearly"  => ["name" => "Premium", "billing" => "Yearly", "amount" => 4791]
    ];

    public function getPlanDetails($planType) {
        return $this->plans[$planType];
    }

    public function save(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments (
                invoice_id, payment_date, amount_paid, token, status
            ) VALUES (?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param(
            'ssdss',
            $data['invoice_id'],
            $data['payment_date'],
            $data['amount_paid'],
            $data['token'],
            $data['status']
        );

        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        return $success;
    }
}
