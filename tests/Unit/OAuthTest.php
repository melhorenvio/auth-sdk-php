<?php

namespace MelhorEnvio\Tests\Unit;

require_once __DIR__. '/../../vendor/autoload.php';

use MelhorEnvio\Auth\OAuth2;
use MelhorEnvio\Tests\TestCase;

class OAuthTest extends TestCase
{
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

        $oAuth2->setScopes('add-cart', 'read-cart');

        $this->assertSame("add-cart read-cart", $oAuth2->getScopes());
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
}
