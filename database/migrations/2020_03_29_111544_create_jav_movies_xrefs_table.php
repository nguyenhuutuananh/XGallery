<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateJavMoviesXrefsTable
 */
class CreateJavMoviesXrefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jav_movies_xrefs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('xref_id')->nullable(false);
            $table->string('xref_type')->nullable(false);
            $table->integer('movie_id')->nullable(false);
            $table->unique(['xref_id','movie_id','xref_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jav_movies_xrefs');
    }
}
