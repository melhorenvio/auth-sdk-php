<?php

namespace MelhorEnvio\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}
