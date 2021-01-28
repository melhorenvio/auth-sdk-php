<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$meData = [];

$appData = [
    'client_id' => 'your-clientId',
    'client_secret' => 'your-clientSecret',
    'redirect_uri' => 'your-redirectUri'
];

$provider = new OAuth2($appData['client_id'], $appData['client_secret'], $appData['redirect_uri']);

if (! isset($_GET['code'])) {
    $provider->setScopes(
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
    header("Location: {$provider->getAuthorizationUrl()}");
    exit;
}

$me_data[] = $provider->getAccessToken($_GET['code'], $_GET['state']);

echo "<pre>";

print_r($me_data);

exit;
