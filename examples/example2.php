<?php

require "vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$auth = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

if (! isset($_GET['code'])) {
    header("Location {$auth->getAuthorizationUrl()}");

    $newAccessToken = $auth->getAccessToken('refresh_token', [
        'refresh_token' => $auth->refreshToken("$_GET[code]")
    ]);
}

echo "<pre>";

print_r(
    $newAccessToken
);

exit;
