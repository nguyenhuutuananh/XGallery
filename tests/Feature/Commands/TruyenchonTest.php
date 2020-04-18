<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class TruyenchonTest
 * @package Tests\Feature\Commands
 */
class TruyenchonTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFully()
    {
        $this->artisan('truyenchon')
            ->assertExitCode(0);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->artisan('db:seed');
    }
}
