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
    $auth->setScopes('cart-write', 'products-write', 'shipping-calculate', 'shipping-checkout');

    header("Location: {$auth->getAuthorizationUrl()}");
    exit;
}

echo '<pre>';

print_r("aqui");
print_r(
    $auth->getAccessToken($_GET['code'], $_GET['state'])
);

echo '<pre>';
