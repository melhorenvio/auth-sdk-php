<?php

require "vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;


var_dump("aqui");
session_start();

$auth = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

if (isset($_GET['error'])) {
    print($_GET['error_description']);
    exit;
}

if (! isset($_GET['code'])) {
    $auth->setScopes('user-read');
    header("Location: {$auth->getAuthorizationUrl()}");
    exit;
}

echo "<pre>";

print_r(
    $auth->getAccessToken($_GET['code'], $_GET['state'])
);

exit;
