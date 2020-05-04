<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Jav;

use App\JavMovies;
use App\JavMoviesXref;
use App\Jobs\Traits\HasJob;
use App\Models\JavGenres;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateGenres
 * @package App\Jobs\Jav
 */
class UpdateGenres implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private JavMovies $movie;
    private array     $genres;

    /**
     * UpdateJavGenres constructor.
     * @param  JavMovies  $movie
     * @param  array  $genres
     */
    public function __construct(JavMovies $movie, array $genres)
    {
        $this->movie  = $movie;
        $this->genres = $genres;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $genres = [];

        foreach ($this->genres as $tag) {
            $model = app(JavGenres::class);
            // Genre already exists then get data for xref
            if ($item = $model->where(['name' => $tag])->first()) {
                $genres[] = ['xref_id' => $item->id, 'xref_type' => 'genre', 'movie_id' => $this->movie->id];
                continue;
            }

            $model->name = $tag;
            $model->save();

            $genres[] = ['xref_id' => $model->id, 'xref_type' => 'genre', 'movie_id' => $this->movie->id];
            unset($model);
        }

        // Update Xref
        foreach ($genres as $genre) {
            $model = app(JavMoviesXref::class);
            if ($model->where($genre)->first()) {
                continue;
            }
            $model->insert($genre);
        }
    }
}
