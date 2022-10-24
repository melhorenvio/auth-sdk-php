<?php

namespace MelhorEnvio\Tests\Unit;

require_once __DIR__. '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use MelhorEnvio\Auth\OAuth2;
use PHPUnit\Framework\TestCase;

class OAuthTest extends TestCase
{
    private string $testClientId;
    private string $testClientSecret;
    private string $testRedirectUri;

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();

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
}
