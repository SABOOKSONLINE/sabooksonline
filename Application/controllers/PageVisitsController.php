<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use GeoIp2\Database\Reader;

class PageVisitsController
{

    private $pageVisitsModel;

    public function __construct($conn)
    {
        $this->pageVisitsModel = new PageVisitsModel($conn);
    }

    public function trackVisits()
    {
        try {
            $visitData = [
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'page_url' => $_SERVER['REQUEST_URI'] ?? '/',
                'referer' => $_SERVER['HTTP_REFERER'] ?? 'Direct',
                'visit_time' => date('Y-m-d H:i:s'),
                'duration' => 0,
                'user_country' => $this->getGeoLocationData($this->getClientIP())['user_country'],
                'user_city' => $this->getGeoLocationData($this->getClientIP())['user_city'],
                'user_province' => $this->getGeoLocationData($this->getClientIP())['user_province'],
                'user_ip' => $this->getClientIP(),
            ];

            return $this->pageVisitsModel->insertVisit($visitData);
        } catch (Exception $ex) {
            error_log("Tracking error: " . $ex->getMessage());
            return false;
        }
    }

    private function getGeoLocationData($ip)
    {
        $geoData = [
            'user_country' => 'Unknown',
            'user_city' => 'Unknown',
            'user_province' => 'Unknown'
        ];

        if ($ip === '127.0.0.1' || substr($ip, 0, 3) === '10.' || substr($ip, 0, 8) === '192.168.') {
            return $geoData;
        }

        try {
            // Use stream context with timeout to prevent hanging
            $context = stream_context_create([
                'http' => [
                    'timeout' => 2, // 2 second timeout
                    'ignore_errors' => true
                ]
            ]);
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city,regionName", false, $context);
            if ($response) {
                $data = json_decode($response, true);
                if ($data && !isset($data['message'])) {
                    $geoData = [
                        'user_country' => $data['country'] ?? 'Unknown',
                        'user_city' => $data['city'] ?? 'Unknown',
                        'user_province' => $data['regionName'] ?? 'Unknown'
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Geolocation error: " . $e->getMessage());
        }

        return $geoData;
    }

    private function getClientIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }
}
