<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Traits\Crawlers;

use App\CrawlerEndpoints;
use App\Crawlers\Crawler\CrawlerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * Trait HasCrawler
 * @package App\Console\Traits
 */
trait HasCrawler
{
    protected CrawlerInterface $crawler;

    protected Model $model;

    public function handle()
    {
        $task = $this->argument('task');
        $this->output->writeln('<info>Running </info>'.$task);

        if (!method_exists($this, $task)) {
            return;
        }

        call_user_func([$this, $task]);
    }

    /**
     * Process WHOLE site by specific URL
     * @return bool
     */
    abstract protected function fully(): bool;

    /**
     * Process specific daily index page
     * @return bool
     */
    abstract protected function daily(): bool;

    abstract protected function index(): bool;

    abstract protected function item(): bool;

    /**
     * @param  array  $data
     */
    protected function insertItem(array $data)
    {
        $model = $this->getModel();

        /**
         * @var Model $item
         */
        if ($item = $model->where(['url' => $data['url']])->first()) {
            if (!isset($item->created_at)) {
                $item->created_at = Date::now();
                $item->updated_at = Date::now();
                $item->save();
            }
            $item->touch();
            return;
        }

        // Can not use fill() because it will be required fillable properties
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        $this->model = app('\App\\'.$this->getShortClassname());

        return $this->model;
    }

    protected function getIndexLinks()
    {
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        if (!$pages = $this->getCrawler()->getIndexLinks(
            $endpoint->url,
            (int) $endpoint->page,
            (int) $endpoint->page
        )) {
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

        return $pages;
    }

    /**
     * @return Model|null
     */
    protected function getCrawlerEndpoint()
    {
        /**
         * @var Model $endpoint
         */
        if (!$endpoint = CrawlerEndpoints::where(['crawler' => $this->getShortClassname()])->orderBy(
            'updated_at',
            'asc'
        )->get()->first()) {
            return null;
        }

        if ($endpoint->page === null || $endpoint === 0) {
            $endpoint->page = 1;
        }

        return $endpoint;
    }

    /**
     * @return CrawlerInterface
     */
    protected function getCrawler(): CrawlerInterface
    {
        if (isset($this->crawler)) {
            return $this->crawler;
        }

        $this->crawler = app('\App\Crawlers\Crawler\\'.$this->getShortClassname());

        return $this->crawler;
    }
}
