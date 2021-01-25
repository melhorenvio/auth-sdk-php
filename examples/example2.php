<?php

require "../vendor/autoload.php";

use MelhorEnvio\Auth\OAuth2;

session_start();

$token = 'your-token';
$refreshToken = 'your-refresh';

$client_id = 'your-clientId';
$client_secret = 'your-clientSecret';
$redirect_uri = 'your-redirectUri';

$provider = new OAuth2($client_id, $client_secret, $redirect_uri);

//$auth_data = [
//  'access-token' => ,
//  'refresh-token' => ,
//  'created_at' => ,
//  'expires_at' => ,
//];

list($token, $refreshToken) = [$token => '', $refreshToken => 2];

echo "sucesso";

exit;
