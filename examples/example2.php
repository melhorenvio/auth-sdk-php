<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$appData = [
    'client_id' => 'your-client',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => 'your-redirect-uri'
];

$provider = new OAuth2($appData['client_id'], $appData['client_secret'], $appData['redirect_uri']);

$authData = [
    'access_token' => 'your-token',
    'refresh_token' => 'your-refresh-token',
    'expires_in' => 'token-expires-timestamp',
];

$newAuthData = $provider->refreshToken($authData['refresh_token']);

echo "<pre>";

print_r($newAuthData);

exit;
