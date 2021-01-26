<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$token = 'your-token';
$refreshToken = 'your-refresh-token';

$user_data = [
    'client_id' => '624',
    'client_secret' => 'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'redirect_uri' => 'http://teste.sandbox.com.br'
];

$provider = new OAuth2($user_data['client_id'], $user_data['client_secret'], $user_data['redirect_uri']);

$auth_data = [
    'access-token' => $token,
    'refresh-token' => '',
    'created_at' => '',
    'expires_at' => '',
];

$newAccessToken = $provider->refreshToken($auth_data['access-token']);

print_r($newAccessToken);

exit;
