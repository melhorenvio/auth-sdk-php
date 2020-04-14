<?php

namespace MelhorEnvio\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use MelhorEnvio\Auth\Exceptions\AccessTokenException;
use MelhorEnvio\Auth\Exceptions\InvalidStateException;
use MelhorEnvio\Auth\Exceptions\RefreshTokenException;

class OAuth2
{
    /**
     * API Endpoint
     * @var Array
     */
    const ENDPOINT = [
        'production' => 'https://melhorenvio.com.br',
        'sandbox' => 'https://sandbox.melhorenvio.com.br',
    ];

    /**
     * App environment
     * @var string
     */
    protected $environment = 'sandbox';

    /**
     * Client ID
     * @var String
     */
    protected $clientId;

    /**
     * Client Secret
     * @var String
     */
    protected $clientSecret;

    /**
     * Redirect URI
     * @var string
     */
    protected $redirectUri;

    /**
     * @var array
     */
    protected $scope = [];

    /**
     * New instance OAuth2
     *
     * @param $clientId
     * @param $clientSecret
     * @param null $redirectUri
     */
    public function __construct($clientId, $clientSecret, $redirectUri = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;

        $this->client = new Client();
    }

    /**
     * @param  string $path
     * @return string
     */
    protected function getEndpoint($path = '')
    {
        return self::ENDPOINT[$this->environment] . $path;
    }

    /**
     * @param  string $path
     * @return string
     */
    protected function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getAuthorizationUrl()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->getRedirectUri(),
            'scope' => $this->getScopes(),
            'state' => $this->getState(),
        ]);

        return $this->getEndpoint("/oauth/authorize?{$query}");
    }

    /**
     * @param $code
     * @param null $state
     * @return mixed
     *
     * @throws OAuth2Exception
     */
    public function getAccessToken($code, $state = null)
    {
        if ($state) {
            $this->verifyState($state);
        }

        try {
            $response = $this->client->post($this->getEndpoint('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $this->getRedirectUri(),
                    'code' => $code,
                ],
            ]);
        } catch (ClientException $exception) {
            throw new AccessTokenException($exception);
        }

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @param  string $refreshToken
     * @return mixed
     *
     * @throws OAuth2Exception
     */
    public function refreshToken($refreshToken)
    {
        try {
            $response = $this->client->post($this->getEndpoint('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $refreshToken,
                ],
            ]);
        } catch (ClientException $exception) {
            throw new RefreshTokenException($exception);
        }

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @param $scopes
     */
    public function setScopes($scopes)
    {
        $this->scope = is_array($scopes)
            ? $scopes
            : func_get_args();
    }

    /**
     * @return string
     */
    public function getScopes()
    {
        return join(" ", $this->scope);
    }

    /**
     * @param $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    protected function getState()
    {
        if (! $_SESSION['me::auth::state']) {
            $this->setState(
                hash('sha256', md5(uniqid(rand(), true)))
            );
        }

        return $_SESSION['me::auth::state'];
    }

    /**
     * @param $state
     */
    protected function setState($state)
    {
        $_SESSION['me::auth::state'] = $state;
    }

    /**
     * @param $state
     */
    protected function verifyState($state)
    {
        if (strlen($state) === 0 && $state !== $_SESSION['me::auth::state']) {
            throw new InvalidStateException;
        }
    }
}
