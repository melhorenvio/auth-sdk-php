<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$provider = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

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
