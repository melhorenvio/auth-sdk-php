<?php

namespace MelhorEnvio\Tests\Feature;

require_once __DIR__. '/../../vendor/autoload.php';

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
    public function returns_path_when_use_method_get_endpoint()
    {
        $this->assertEquals($this->provider->getEndpoint(), "https://sandbox.melhorenvio.com.br");
    }

    /** @test */
    public function returns_ok_when_use_method_set_environment()
    {
        $this->provider->setEnvironment("testing");
        $this->assertEquals($this->provider->getEnvironment(), "testing");
    }

    /** @test */
    public function returns_an_url_when_use_method_get_authorization_url()
    {
        $authorizarionUrl = $this->provider->getAuthorizationUrl();
        $this->assertTrue(filter_var($authorizarionUrl, FILTER_VALIDATE_URL) !== false);
    }

    /** @test */
    public function returns_scopes_when_use_method_get_scopes()
    {
        $this->provider->setScopes('add-cart', 'read-cart');
        $this->assertEquals($this->provider->getScopes(), "add-cart read-cart");
    }

    /** @test */
    public function returns_redirect_url_when_use_methods_to_change_redirect_url()
    {
        $this->provider->setRedirectUri('https://www.abc.com.br/callback');
        $this->assertEquals($this->provider->getRedirectUri(), 'https://www.abc.com.br/callback');
    }
}
