<?php

namespace MelhorEnvio\Tests\Unit\Exceptions\Token;

use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JsonException;
use MelhorEnvio\Auth\Exceptions\ClientException as MEClientException;
use MelhorEnvio\Tests\TestCase;

abstract class BaseClientExceptionTest extends TestCase
{
    abstract protected function getClass(): string;

    /**
     * @test
     * @small
     * @throws JsonException
     */
    public function it_sets_the_error_message_as_the_exception_response_data(): void
    {
        $response = $this->getTestResponse();

        $clientException = GuzzleClientException::create(
            $this->getTestRequest(),
            $response,
        );

        $sut = $this->getInstance($clientException);

        $this->assertSame((string)$response->getBody(), $sut->getMessage());
    }

    /**
     * @test
     * @small
     * @throws JsonException
     */
    public function it_sets_the_error_code_as_the_exception_response_status_code(): void
    {
        $response = $this->getTestResponse();

        $clientException = GuzzleClientException::create(
            $this->getTestRequest(),
            $response,
        );

        $sut = $this->getInstance($clientException);

        $this->assertSame($response->getStatusCode(), $sut->getCode());
    }

    private function getTestRequest(): Request
    {
        return new Request(
            'POST',
            '::uri::'
        );
    }

    /**
     * @throws JsonException
     */
    private function getTestResponse(): Response
    {
        return new Response(
            400,
            [],
            json_encode(['foo' => 'bar'], JSON_THROW_ON_ERROR)
        );
    }

    private function getInstance(GuzzleClientException $clientException): MEClientException
    {
        $class = ($this->getClass());

        return new $class($clientException);
    }
}
