<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * Class MigrateEnpoints
 */
class MigrateEnpoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crawler_endpoints', function (Blueprint $table) {
            //
        });

        DB::table('crawler_endpoints')->insert(
            [
                'crawler' => 'Phodacbiet',
                'url' => 'https://phodacbiet.info/forums/anh-hotgirl-nguoi-mau.43/',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'crawler' => 'Kissgoddess',
                'url' => 'https://kissgoddess.com/gallery/',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crawler_endpoints', function (Blueprint $table) {
            //
        });
    }
}
