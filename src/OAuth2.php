<?php

namespace MelhorEnvio\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use MelhorEnvio\Auth\Exceptions\AccessTokenException;
use MelhorEnvio\Auth\Exceptions\InvalidStateException;
use MelhorEnvio\Auth\Exceptions\RefreshTokenException;

class OAuth2
{
    const ENDPOINT = [
        'production' => 'https://melhorenvio.com.br',
        'sandbox' => 'https://sandbox.melhorenvio.com.br',
    ];

    protected string $environment = 'sandbox';

    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUri;

    protected array $scope = [];

    private Client $client;

    public function __construct(string  $clientId, string $clientSecret, string $redirectUri = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;

        $this->client = new Client();
    }

    protected function getEndpoint(string $path = ''): string
    {
        return self::ENDPOINT[$this->environment] . $path;
    }

    protected function setEnvironment(string $environment): string
    {
        $this->environment = $environment;
    }

    public function getAuthorizationUrl(): string
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
     * @throws AccessTokenException
     */
    public function getAccessToken(string $code, ?string $state)
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
     * @throws RefreshTokenException
     */
    public function refreshToken(string $refreshToken): array
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

    public function setScopes($scopes): void
    {
        $this->scope = is_array($scopes)
            ? $scopes
            : func_get_args();
    }

    public function getScopes(): string
    {
        return join(" ", $this->scope);
    }

    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    protected function getState(): string
    {
        if (empty($_SESSION['me::auth::state'])) {
            $this->setState(
                hash('sha256', md5(uniqid(rand(), true)))
            );
        }

        return $_SESSION['me::auth::state'];
    }

    protected function setState(string $state): void
    {
        $_SESSION['me::auth::state'] = $state;
    }

    /**
     * @throws InvalidStateException
     */
    protected function verifyState(string $state): void
    {
        if (strlen($state) === 0 && $state !== $_SESSION['me::auth::state']) {
            throw new InvalidStateException;
        }
    }
}
