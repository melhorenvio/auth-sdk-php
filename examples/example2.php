<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$provider = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

$provider->setScopes('users-read','users-write');
header("Location: {$provider->getAuthorizationUrl()}");

$newAccess = $provider->getAccessToken('refresh-token' , [
    'refresh-token' => $provider->refreshToken()
]);

echo "<pre>";

exit;
