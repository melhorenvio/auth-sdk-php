<?php

require "vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$authData = [];

$appData = [
    'client_id' => 'your-client',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => 'your-redirect-uri'
];

$provider = new OAuth2($appData['client_id'], $appData['client_secret'], $appData['redirect_uri']);

if (isset($_GET['error'])) {
    print($_GET['error_description']);
    exit;
}

if (! isset($_GET['code'])) {
    $provider->setScopes(['users-read']);
    header("Location: {$provider->getAuthorizationUrl()}");
    exit;
}

$authData[] = $provider->getAccessToken($_GET['code'], $_GET['state']);

echo "<pre>";

print_r($authData);

exit;
