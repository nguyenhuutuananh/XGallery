<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use App\Jobs\R18;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class R18Test
 * @package Tests\Feature\Commands
 */
class R18Test extends TestCase
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
        $this->artisan('r18 fully')
            ->assertExitCode(0);
        Queue::assertPushed(R18::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDaily()
    {
        $this->artisan('daily daily')
            ->assertExitCode(0);
        Queue::assertPushed(R18::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        Queue::fake();
    }
}
