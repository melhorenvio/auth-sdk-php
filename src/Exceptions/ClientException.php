<?php

namespace MelhorEnvio\Auth\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;

abstract class ClientException extends Exception
{
    public function __construct(GuzzleClientException $e)
    {
        $response = $e->getResponse();

        $message = $response ? (string)($response->getBody()) : null;
        $statusCode = $response ? (string)($response->getStatusCode()) : null;

        parent::__construct($message, $statusCode);
    }
}
