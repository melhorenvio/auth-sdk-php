<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$me_data = [];

$user_data = [
    'client_id' => '624',
    'client_secret' => 'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'redirect_uri' => 'http://teste.sandbox.com.br'
];

$provider = new OAuth2($user_data['client_id'], $user_data['client_secret'], $user_data['redirect_uri']);

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
    $me_data[] = $provider->getAccessToken($_GET['code'], $_GET['state'])
);

exit;
