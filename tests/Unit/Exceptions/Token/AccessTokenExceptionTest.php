<?php

namespace MelhorEnvio\Tests\Unit\Exceptions\Token;

use MelhorEnvio\Auth\Exceptions\Token\AccessTokenException;

class AccessTokenExceptionTest extends BaseClientExceptionTest
{
    protected function getClass(): string
    {
        return AccessTokenException::class;
    }
}
