<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use App\Jobs\OneJav;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class OnejavTest
 * @package Tests\Feature\Commands
 */
class OnejavTest extends TestCase
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
        $this->artisan('onejav fully')
            ->assertExitCode(0);
        Queue::assertPushed(OneJav::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDaily()
    {
        $this->artisan('onejav daily')
            ->assertExitCode(0);
        Queue::assertPushed(OneJav::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        Queue::fake();
    }
}
