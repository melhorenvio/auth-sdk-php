<?php

namespace MelhorEnvio\Tests\Unit\Exceptions\Token;

use MelhorEnvio\Auth\Exceptions\RefreshTokenException;

class RefreshTokenExceptionTest extends BaseClientExceptionTest
{
    protected function getClass(): string
    {
        return RefreshTokenException::class;
    }
}
