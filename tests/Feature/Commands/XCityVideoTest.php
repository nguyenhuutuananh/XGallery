<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use App\Jobs\XCityVideo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class XCityProfileTest
 * @package Tests\Feature\Commands
 */
class XCityVideoTest extends TestCase
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
        $this->artisan('xcity:video')
            ->assertExitCode(0);
        Queue::assertPushed(XCityVideo::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDaily()
    {
        $this->artisan('xcity:video daily')
            ->assertExitCode(0);
        Queue::assertPushed(XCityVideo::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        Queue::fake();
    }
}
