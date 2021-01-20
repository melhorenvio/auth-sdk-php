<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$provider = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

if (! isset($_GET['code'])) {
    $provider->setScopes('users-read', 'users-write');
    header("Location: {$provider->getAuthorizationUrl()}");
    exit;
}

print_r(
    $access = $provider->getAccessToken($_GET['code'], $_GET['state'])
);

if (is_null($access)) {
    print_r(
        $newAccess = $provider->getAccessToken('refresh-token' , [
            'refresh-token' => $provider->refreshToken()
        ])
    );
    exit;
}

echo "<pre>";

exit;
