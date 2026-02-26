<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../Core/Model.php";

class UserModel extends Model
{
    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->createBookCollectionAddressTable();
    }

    private function createBookCollectionAddressTable(): void
    {
        $this->createTable('book_collection_addresses', [
            'id'               => 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'user_id'          => 'INT NOT NULL',
            'nickname'         => 'VARCHAR(100) NOT NULL COMMENT "e.g. Home, Office"',
            'contact_name'     => 'VARCHAR(150) NOT NULL',
            'contact_phone'    => 'VARCHAR(20) NOT NULL',
            'contact_email'    => 'VARCHAR(255) NOT NULL',
            'unit_number'      => 'VARCHAR(20) DEFAULT NULL',
            'complex_name'     => 'VARCHAR(150) DEFAULT NULL',
            'street_number'    => 'VARCHAR(20) NOT NULL',
            'street_name'      => 'VARCHAR(255) NOT NULL',
            'suburb'           => 'VARCHAR(150) NOT NULL',
            'city'             => 'VARCHAR(150) NOT NULL',
            'province'         => 'VARCHAR(100) NOT NULL',
            'postal_code'      => 'VARCHAR(10) NOT NULL',
            'country_code'     => 'CHAR(2) NOT NULL DEFAULT "ZA"',
            'special_instructions' => 'TEXT DEFAULT NULL',
            'is_default'       => 'TINYINT(1) NOT NULL DEFAULT 0',
            'created_at'       => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at'       => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public function getAddressesByUser(int $userId): array
    {
        return $this->fetchPrepared(
            "SELECT * FROM book_collection_addresses 
             WHERE user_id = ? 
             ORDER BY is_default DESC, created_at DESC",
            'i',
            [$userId]
        );
    }

    public function getAddressById(int $addressId, int $userId): array
    {
        $result = $this->fetchPrepared(
            "SELECT * FROM book_collection_addresses 
             WHERE id = ? AND user_id = ?",
            'ii',
            [$addressId, $userId]
        );
        return $result[0] ?? [];
    }

    public function getDefaultAddress(int $userId): array
    {
        $result = $this->fetchPrepared(
            "SELECT * FROM book_collection_addresses 
             WHERE user_id = ? AND is_default = 1 
             LIMIT 1",
            'i',
            [$userId]
        );
        return $result[0] ?? [];
    }

    public function addAddress(int $userId, array $data): int
    {
        if (!empty($data['is_default'])) {
            $this->clearDefaultAddress($userId);
        }

        return $this->insert(
            "INSERT INTO book_collection_addresses 
                (user_id, nickname, contact_name, contact_phone, contact_email,
                 unit_number, complex_name, street_number, street_name,
                 suburb, city, province, postal_code, country_code,
                 special_instructions, is_default)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            'issssssssssssssi',
            [
                $userId,
                $data['nickname'],
                $data['contact_name'],
                $data['contact_phone'],
                $data['contact_email'],
                $data['unit_number'] ?? null,
                $data['complex_name'] ?? null,
                $data['street_number'],
                $data['street_name'],
                $data['suburb'],
                $data['city'],
                $data['province'],
                $data['postal_code'],
                $data['country_code'] ?? 'ZA',
                $data['special_instructions'] ?? null,
                $data['is_default'] ?? 0,
            ]
        );
    }

    public function updateAddress(int $addressId, int $userId, array $data): int
    {
        if (!empty($data['is_default'])) {
            $this->clearDefaultAddress($userId);
        }

        return $this->update(
            "UPDATE book_collection_addresses SET
                nickname = ?, contact_name = ?, contact_phone = ?, contact_email = ?,
                unit_number = ?, complex_name = ?, street_number = ?, street_name = ?,
                suburb = ?, city = ?, province = ?, postal_code = ?, country_code = ?,
                special_instructions = ?, is_default = ?
             WHERE id = ? AND user_id = ?",
            'sssssssssssssssii',
            [
                $data['nickname'],
                $data['contact_name'],
                $data['contact_phone'],
                $data['contact_email'],
                $data['unit_number'] ?? null,
                $data['complex_name'] ?? null,
                $data['street_number'],
                $data['street_name'],
                $data['suburb'],
                $data['city'],
                $data['province'],
                $data['postal_code'],
                $data['country_code'] ?? 'ZA',
                $data['special_instructions'] ?? null,
                $data['is_default'] ?? 0,
                $addressId,
                $userId,
            ]
        );
    }

    public function setDefaultAddress(int $addressId, int $userId): int
    {
        $this->clearDefaultAddress($userId);

        return $this->update(
            "UPDATE book_collection_addresses 
             SET is_default = 1 
             WHERE id = ? AND user_id = ?",
            'ii',
            [$addressId, $userId]
        );
    }

    public function deleteAddress(int $addressId, int $userId): int
    {
        return $this->delete(
            "DELETE FROM book_collection_addresses 
             WHERE id = ? AND user_id = ?",
            'ii',
            [$addressId, $userId]
        );
    }

    private function clearDefaultAddress(int $userId): void
    {
        $this->update(
            "UPDATE book_collection_addresses 
             SET is_default = 0 
             WHERE user_id = ?",
            'i',
            [$userId]
        );
    }

    public function formatForCourierGuy(array $address): array
    {
        return [
            'contact' => [
                'name'  => $address['contact_name'],
                'phone' => $address['contact_phone'],
                'email' => $address['contact_email'],
            ],
            'address' => [
                'unit_no'      => $address['unit_number']  ?? '',
                'complex'      => $address['complex_name'] ?? '',
                'street_no'    => $address['street_number'],
                'street'       => $address['street_name'],
                'suburb'       => $address['suburb'],
                'city'         => $address['city'],
                'province'     => $address['province'],
                'postal_code'  => $address['postal_code'],
                'country_code' => $address['country_code'],
            ],
            'special_instructions' => $address['special_instructions'] ?? '',
        ];
    }

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
             WHERE LOWER(subscription_status) IN ('royalties', 'premium', 'pro', 'free')
             ORDER BY ADMIN_NAME ASC"
        );
    }

    public function getAllUsersForSelector(): array
    {
        return $this->fetchAll(
            "SELECT ADMIN_ID as user_id, ADMIN_NAME as name, ADMIN_EMAIL as email
             FROM users
             ORDER BY ADMIN_NAME ASC"
        );
    }

    public function getAllAddresses(): array
    {
        return $this->fetchAll(
            "SELECT a.*, u.ADMIN_NAME as user_name, u.ADMIN_EMAIL as user_email
         FROM book_collection_addresses a
         LEFT JOIN users u ON a.user_id = u.ADMIN_ID
         ORDER BY a.created_at DESC"
        );
    }
}
