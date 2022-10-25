<?php

namespace MelhorEnvio\Tests\Unit;

require_once __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JsonException;
use MelhorEnvio\Auth\Exceptions\AccessTokenException;
use MelhorEnvio\Auth\Exceptions\RefreshTokenException;
use MelhorEnvio\Auth\OAuth2;
use MelhorEnvio\Tests\TestCase;
use Mockery;

class OAuthTest extends TestCase
{
    private const TEST_TOKEN_TYPE = '::token-type::';
    private const TEST_EXPIRES_IN = '::expires-in::';
    private const TEST_ACCESS_TOKEN = '::access-token::';
    private const TEST_CODE = '::code::';
    private const TEST_STATE = '::state::';
    private const TEST_REFRESH_TOKEN = '::refresh-token::';
    private const APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    private string $testClientId;
    private string $testClientSecret;
    private string $testRedirectUri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testClientId = $_ENV['TEST_CLIENT_ID'];
        $this->testClientSecret = $_ENV['TEST_CLIENT_SECRET'];
        $this->testRedirectUri = $_ENV['TEST_REDIRECT_URI'];
    }

    /**
     * @test
     * @small
     */
    public function instantiates_correctly(): void
    {
        $this->assertInstanceOf(
            OAuth2::class,
            new OAuth2(
                $this->testClientId,
                $this->testClientSecret,
                $this->testRedirectUri,
            )
        );
    }

    /**
     * @test
     * @small
     */
    public function sets_scopes(): void
    {
        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );

        $expectedScopes = ['add-cart', 'read-cart'];

        $oAuth2->setScopes(...$expectedScopes);

        $this->assertSame($expectedScopes, $oAuth2->getScopes());
    }

    /**
     * @test
     * @small
     */
    public function sets_redirect_uri(): void
    {
        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );

        $expectedRedirectUri = 'https://www.abc.com.br/callback';

        $oAuth2->setRedirectUri($expectedRedirectUri);

        $this->assertSame($expectedRedirectUri, $oAuth2->getRedirectUri());
    }

    /**
     * @test
     * @small
     * @dataProvider environmentProvider
     */
    public function sets_environment(string $environment, string $expectedUrl): void
    {
        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );

        $oAuth2->setEnvironment($environment);

        $sut = $oAuth2->getAuthorizationUrl();

        $this->assertSame($expectedUrl, substr($sut, 0, strlen($expectedUrl)));
    }

    /**
     * @test
     * @small
     */
    public function sets_client(): void
    {
        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );

        $clientMock = Mockery::mock(Client::class);

        $oAuth2->setClient($clientMock);

        $this->assertSame($clientMock, $oAuth2->getClient());
    }

    /**
     * @test
     * @small
     * @dataProvider environmentProvider
     * @throws JsonException|AccessTokenException
     */
    public function gets_access_token(string $environment, string $url): void
    {
        $expectedResponse = [
            'token_type' => self::TEST_TOKEN_TYPE,
            'expires_in' => self::TEST_EXPIRES_IN,
            'access_token' => self::TEST_ACCESS_TOKEN,
            'refresh_token' => self::TEST_REFRESH_TOKEN,
        ];

        $expectedBody = http_build_query([
            'grant_type' => 'authorization_code',
            'client_id' => $this->testClientId,
            'client_secret' => $this->testClientSecret,
            'redirect_uri' => $this->testRedirectUri,
            'code' => self::TEST_CODE,
        ]);

        $container = [];
        $history = Middleware::history($container);
        $client = $this->createClientMock($expectedResponse, $history);

        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );
        $oAuth2->setEnvironment($environment);
        $oAuth2->setClient($client);

        $sut = $oAuth2->getAccessToken(self::TEST_CODE, self::TEST_STATE);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertSame($expectedResponse, $sut);
        $this->assertSame("$url/oauth/token", (string)$request->getUri());
        $this->assertSame($expectedBody, (string)$request->getBody());
        $this->assertSame(self::APPLICATION_X_WWW_FORM_URLENCODED, $request->getHeader('Content-Type')[0]);
    }

    /**
     * @test
     * @small
     * @throws JsonException
     */
    public function throws_access_token_exception_when_a_client_errors_occurs_while_issuing_an_access_token(): void
    {
        $expectedResponse = ['foo' => 'bar'];
        $expectedResponseAsJson = json_encode($expectedResponse, JSON_THROW_ON_ERROR);
        $expectedStatusCode = 422;

        $client = $this->createMockClientThatThrowsClientException($expectedResponse, $expectedStatusCode);

        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );
        $oAuth2->setClient($client);

        try {
            $oAuth2->getAccessToken(self::TEST_CODE, self::TEST_STATE);
        } catch (AccessTokenException $e) {
            $this->assertSame($expectedResponseAsJson, $e->getMessage());
            $this->assertSame($expectedStatusCode, $e->getCode());

            return;
        }

        $this->fail(sprintf("%s exception was not thrown.", AccessTokenException::class));
    }

    /**
     * @test
     * @small
     * @dataProvider environmentProvider
     * @throws JsonException|AccessTokenException
     * @throws RefreshTokenException
     */
    public function gets_refreshed_token(string $environment, string $url): void
    {
        $expectedResponse = [
            'token_type' => self::TEST_TOKEN_TYPE,
            'expires_in' => self::TEST_EXPIRES_IN,
            'access_token' => self::TEST_ACCESS_TOKEN,
            'redirect_uri' => $this->testRedirectUri,
            'code' => self::TEST_CODE,
        ];

        $expectedBody = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => $this->testClientId,
            'client_secret' => $this->testClientSecret,
            'refresh_token' => self::TEST_REFRESH_TOKEN,
        ]);

        $container = [];
        $history = Middleware::history($container);
        $client = $this->createClientMock($expectedResponse, $history);

        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );
        $oAuth2->setEnvironment($environment);
        $oAuth2->setClient($client);

        $sut = $oAuth2->refreshToken(self::TEST_REFRESH_TOKEN);

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertSame($expectedResponse, $sut);
        $this->assertSame("$url/oauth/token", (string)$request->getUri());
        $this->assertSame($expectedBody, (string)$request->getBody());
        $this->assertSame(self::APPLICATION_X_WWW_FORM_URLENCODED, $request->getHeader('Content-Type')[0]);
    }

    /**
     * @test
     * @small
     * @throws JsonException
     */
    public function throws_refresh_token_exception_when_a_client_errors_occurs_while_issuing_an_refresh_token(): void
    {
        $expectedResponse = ['foo' => 'bar'];
        $expectedResponseAsJson = json_encode($expectedResponse, JSON_THROW_ON_ERROR);
        $expectedStatusCode = 422;

        $client = $this->createMockClientThatThrowsClientException($expectedResponse, $expectedStatusCode);

        $oAuth2 = new OAuth2(
            $this->testClientId,
            $this->testClientSecret,
            $this->testRedirectUri,
        );
        $oAuth2->setClient($client);

        try {
            $oAuth2->refreshToken(self::TEST_REFRESH_TOKEN);
        } catch (RefreshTokenException $e) {
            $this->assertSame($expectedResponseAsJson, $e->getMessage());
            $this->assertSame($expectedStatusCode, $e->getCode());

            return;
        }

        $this->fail(sprintf("%s exception was not thrown.", RefreshTokenException::class));
    }

    public function environmentProvider(): array
    {
        return [
            'PRODUCTION environment' => [
                'production',
                'https://melhorenvio.com.br'
            ],
            'SANDBOX environment' => [
                'sandbox',
                'https://sandbox.melhorenvio.com.br'
            ],
        ];
    }

    /**
     * @throws JsonException
     */
    private function createClientMock(array $response, callable $history): Client
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                json_encode($response, JSON_THROW_ON_ERROR)
            ),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * @throws JsonException
     */
    private function createMockClientThatThrowsClientException(array $responseBody, int $statusCode): Client
    {
        $request = new Request('POST', '::uri::');
        $response = new Response($statusCode, [], json_encode($responseBody, JSON_THROW_ON_ERROR));

        $mock = new MockHandler([
            new ClientException('::message::', $request, $response)
        ]);

        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }
}
