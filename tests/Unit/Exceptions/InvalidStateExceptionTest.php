<?php

namespace MelhorEnvio\Tests\Unit\Exceptions;

use InvalidArgumentException;
use MelhorEnvio\Auth\Exceptions\Token\InvalidStateException;
use MelhorEnvio\Tests\TestCase;

class InvalidStateExceptionTest extends TestCase
{
    /**
     * @test
     * @small
     */
    public function it_is_an_instance_of_InvalidArgumentException(): void
    {
        $this->assertInstanceOf(
            InvalidArgumentException::class,
            new InvalidStateException()
        );
    }
}
