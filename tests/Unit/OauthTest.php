<?php

namespace MelhorEnvio\MelhorEnvio\Tests\Unit;

require "vendor/autoload.php";

use Dotenv\Dotenv;
use MelhorEnvio\Auth\OAuth2;
use PHPUnit\Framework\TestCase;

class OauthTest extends TestCase
{
    protected OAuth2 $provider;

    public function __construct()
    {
        parent::__construct();

        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->provider = new OAuth2(
            $_ENV['TEST_CLIENT_ID'],
            $_ENV['TEST_CLIENT_SECRET'],
            $_ENV['TEST_REDIRECT_URI']
        );
    }

    /** @test */
    public function returns_melhor_envio_endpoint_when_use_method_get_endpoint()
    {
        $this->assertTrue(method_exists($this->provider, 'getEndpoint'));
    }

    /** @test */
    public function returns_true_when_exists_method_set_environment()
    {
        $this->assertTrue(method_exists($this->provider, 'setEnvironment'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_environment()
    {
        $this->assertTrue(method_exists($this->provider, 'getEnvironment'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_authorization_url()
    {
        $this->assertTrue(method_exists($this->provider, 'getAuthorizationUrl'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_access_token()
    {
        $this->assertTrue(method_exists($this->provider, 'getAccessToken'));
    }

    /** @test */
    public function returns_true_when_exists_method_refresh_token()
    {
        $this->assertTrue(method_exists($this->provider, 'refreshToken'));
    }

    /** @test */
    public function returns_true_when_exists_method_set_scopes()
    {
        $this->assertTrue(method_exists($this->provider, 'setScopes'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_scopes()
    {
        $this->assertTrue(method_exists($this->provider, 'getScopes'));
    }

    /** @test */
    public function returns_true_when_exists_method_set_redirect_uri()
    {
        $this->assertTrue(method_exists($this->provider, 'setRedirectUri'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_redirect_uri()
    {
        $this->assertTrue(method_exists($this->provider, 'getRedirectUri'));
    }

    /** @test */
    public function returns_true_when_exists_method_get_state()
    {
        $this->assertTrue(method_exists($this->provider, 'getState'));
    }

    /** @test */
    public function returns_true_when_exists_method_set_state()
    {
        $this->assertTrue(method_exists($this->provider, 'setState'));
    }

    /** @test */
    public function returns_true_when_exists_method_verify_state()
    {
        $this->assertTrue(method_exists($this->provider, 'verifyState'));
    }
}
