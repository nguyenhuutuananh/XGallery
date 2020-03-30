<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\OneJav;

use App\JavIdols;
use App\JavMovies;
use App\JavMoviesXref;
use App\Services\Crawler\XCityProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateJavIdols
 * @package App\Jobs\OneJav
 */
class UpdateJavIdols implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private JavMovies $movie;
    private array     $itemDetail;

    /**
     * UpdateJavIdols constructor.
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
        $crawler = app(XCityProfile::class);

        // Process all actresses in this movie
        foreach ($this->itemDetail['actresses'] as $actress) {
            /**
             * Collection of actresses grouped by page
             * Convert to array because we will need use $actress variable
             */
            $results = $crawler->search(['genre' => 'idol', 'q' => $actress, 'sg' => 'idol'])->toArray();

            foreach ($results as $actresses) {
                // This idol is not exists on XCity
                if (empty($actresses)) {
                    $model = app(JavIdols::class);
                    /**
                     * Because we can't determine idol profile than only way to use reference_url as unique
                     */
                    if ($item = $model->where(['reference_url' => $this->itemDetail['url']])->first()) {
                        $this->insertXRef($item);
                        continue;
                    }

                    // Save to idol with OneJAV reference_url
                    $model->name          = $actress;
                    $model->reference_url = $this->itemDetail['url'];
                    $model->save();
                    $this->insertXRef($model);
                    unset($model);
                    continue;
                }

                // Found actresses on XCity
                foreach ($actresses as $actress) {
                    // Actress detail not found
                    if (!$actressDetail = app(XCityProfile::class)->getItemDetail('https://xxx.xcity.jp/idol/'.$actress['url'])) {
                        continue;
                    }

                    $model = app(JavIdols::class);
                    if ($item = $model->where(['reference_url' => $actressDetail->url])->first()) {
                        $this->insertXRef($item);
                        unset($model);
                        return;
                    }

                    $model->name          = $actressDetail->name;
                    $model->reference_url = $actressDetail->url;
                    $model->cover         = $actressDetail->cover;
                    $model->favorite      = $actressDetail->favorite ?? null;
                    $model->birthday      = $actressDetail->birthday ?? null;
                    $model->blood_type    = $actressDetail->blood_type ?? null;
                    $model->city          = $actressDetail->city ?? null;
                    $model->height        = $actressDetail->height ?? null;
                    $model->breast        = $actressDetail->breast ?? null;
                    $model->waist         = $actressDetail->waist ?? null;
                    $model->hips          = $actressDetail->hips ?? null;

                    $model->save();
                    $this->insertXRef($model);
                    unset($model);
                }
            }
        }
    }

    /**
     * @param  JavIdols  $idolModel
     */
    private function insertXRef(JavIdols $idolModel)
    {
        $model = app(JavMoviesXref::class);
        $xref  = ['xref_id' => $idolModel->id, 'xref_type' => 'idol', 'movie_id' => $this->movie->id];
        if ($model->where($xref)->first()) {
            return;
        }

        $model->insert($xref);
    }
}
