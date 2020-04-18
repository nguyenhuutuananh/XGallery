<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use App\Jobs\XCityProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class XCityProfileTest
 * @package Tests\Feature\Commands
 */
class XCityProfileTest extends TestCase
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
        $this->artisan('xcity:profile')
            ->assertExitCode(0);
        Queue::assertPushed(XCityProfile::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDaily()
    {
        $this->artisan('xcity:profile daily')
            ->assertExitCode(0);
        Queue::assertPushed(XCityProfile::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        Queue::fake();
    }
}
