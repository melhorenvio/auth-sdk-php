<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$client_id = 'your-clientId';
$client_secret = 'your-clientSecret';
$redirect_uri = 'your-redirectUri';

$provider = new OAuth2($client_id, $client_secret, $redirect_uri);

//$auth_data = [
//  'access-token' => ,
//  'refresh-token' => ,
//  'created_at' => ,
//  'expires_at' => ,
//];

if (isset($_GET['error'])) {
    print($_GET['error_description']);
    exit;
}

if (! isset($_GET['code'])) {
    $provider->setScopes('users-read');
    header("Location: {$provider->getAuthorizationUrl()}");
    exit;
}

echo "<pre>";

print_r(
    $provider->getAccessToken($_GET['code'], $_GET['state'])
);

exit;
