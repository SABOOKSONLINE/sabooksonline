<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class UserModel extends Model
{
    public function getAdminUser(string $email): array
    {
        return $this->fetchPrepared(
            "SELECT * FROM admin_users 
            WHERE email = ?",
            "s",
            [$email]
        );
    }

    public function getAllAdmins(): array
    {
        return $this->fetchAll("SELECT * FROM admin_users");
    }

    public function getAllUsers(): array
    {
        return $this->fetchAll("SELECT ADMIN_ID, ADMIN_NAME, ADMIN_EMAIL, subscription_status
                                FROM users ORDER BY ADMIN_ID DESC");
    }

    public function countUsers(): array
    {
        $result = $this->fetchAll("SELECT COUNT(*) AS total FROM users");
        return $result[0];
    }

    public function countSubscribers(): array
    {
        $result = $this->fetchAll("SELECT COUNT(*) AS total 
                                    FROM users 
                                    WHERE LOWER(subscription_status) IN ('royalties', 'premium', 'pro');");
        return $result[0];
    }

    public function grossSubscriptionIncome(): array
    {
        $result = $this->fetchAll("SELECT SUM(amount_paid) AS gross FROM payment_plans");
        return $result[0];
    }

    public function getAllUsersDetails(): array
    {
        return $this->fetchAll("SELECT *
                                FROM users ORDER BY ADMIN_ID DESC");
    }

    public function getUsersForPublisherSelection(): array
    {
        return $this->fetchAll(
            "SELECT ADMIN_ID as user_id, ADMIN_NAME as name, ADMIN_EMAIL as email, ADMIN_USERKEY as userkey
        FROM users 
        WHERE LOWER(subscription_status) IN ('royalties', 'premium', 'pro')
        ORDER BY ADMIN_NAME ASC"
        );
    }
}
