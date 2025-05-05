<?php

namespace App\Services;

use Google\Client as Google_Client;

class GoogleAuthService {
    public static function getClient(): Google_Client {
        $client = new Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $client->addScope('profile');
        return $client;
    }

    public static function getAuthUrl(): string {
        $client = self::getClient();
        return $client->createAuthUrl();
    }
}
