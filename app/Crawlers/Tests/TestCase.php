<?php

namespace App\Crawlers\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

/**
 * Class TestCase
 * @package App\Crawlers\Tests
 */
abstract class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost';

    use CreatesApplication;
}
