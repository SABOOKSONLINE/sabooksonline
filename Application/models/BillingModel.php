<?php
class BillingModel {
    private $plans = [
        "Pro-Monthly" => ["name" => "Pro", "billing" => "Monthly", "amount" => 199],
        "Pro-Yearly"  => ["name" => "Pro", "billing" => "Yearly", "amount" => 1908],
        "Premium-Monthly" => ["name" => "Premium", "billing" => "Monthly", "amount" => 499],
        "Premium-Yearly"  => ["name" => "Premium", "billing" => "Yearly", "amount" => 4788]
    ];

    public function getPlanDetails($planType) {
        return $this->plans[$planType] ?? null;
    }
}
