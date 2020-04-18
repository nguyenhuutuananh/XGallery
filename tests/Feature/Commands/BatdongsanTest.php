<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace Tests\Feature\Commands;

use App\Jobs\Batdongsan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BatdongsanTest extends TestCase
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
        $this->artisan('batdongsan')
            ->assertExitCode(0);

        Queue::assertPushed(Batdongsan::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->artisan('db:seed');
    }
}
