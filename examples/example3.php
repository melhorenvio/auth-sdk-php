<?php
require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$auth = new OAuth2(
    624,
    'ZNlrnALWQf4O2Q1xFWJiwIE004rOXQH3sgKJX86f',
    'http://teste.sandbox.com.br'
);

if (! isset($_GET['code'])) {
    $auth->setScopes(
        'cart-read',
        'cart-write',
        'companies-read',
        'companies-write',
        'coupons-read',
        'coupons-write',
        'notifications-read',
        'orders-read',
        'products-read',
        'products-write',
        'purchases-read',
        'shipping-calculate',
        'shipping-cancel',
        'shipping-checkout',
        'shipping-companies',
        'shipping-generate',
        'shipping-preview',
        'shipping-print',
        'shipping-share',
        'shipping-tracking',
        'ecommerce-shipping',
        'transactions-read',
        'users-read',
        'users-write'

    );
    header("Location: {$auth->getAuthorizationUrl()}");
    exit;
}

echo "<pre>";

print_r(
    $auth->getAccessToken($_GET['code'], $_GET['state'])
);

exit;
