<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\OneJav;

use App\JavGenres;
use App\JavMovies;
use App\JavMoviesXref;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateJavGenres
 * @package App\Jobs
 */
class UpdateJavGenres implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private JavMovies $movie;
    private array     $itemDetail;

    /**
     * UpdateJavGenres constructor.
     * @param  JavMovies  $movie
     * @param  array  $itemDetail
     */
    public function __construct(JavMovies $movie, array $itemDetail)
    {
        $this->movie      = $movie;
        $this->itemDetail = $itemDetail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $genres = [];

        foreach ($this->itemDetail['tags'] as $tag) {
            $model = app(JavGenres::class);
            if ($item = $model->where(['name' => $tag])->first()) {
                $genres[] = ['xref_id' => $item->id, 'xref_type' => 'genre', 'movie_id' => $this->movie->id];
                continue;
            }

            $model->name = $tag;
            $model->save();

            $genres[] = ['xref_id' => $model->id, 'xref_type' => 'genre', 'movie_id' => $this->movie->id];
            unset($model);
        }

        foreach ($genres as $genre) {
            $model = app(JavMoviesXref::class);
            if ($model->where($genre)->first()) {
                continue;
            }
            $model->insert($genre);
        }
    }
}
