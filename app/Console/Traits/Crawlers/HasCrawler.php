<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Traits\Crawlers;

use App\Crawlers\Crawler\CrawlerInterface;
use App\Models\CrawlerEndpoints;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Trait HasCrawler
 * @package App\Console\Traits\Crawlers
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
     * @param  bool  $isNew
     * @return Model
     */
    protected function insertItem(array $data, &$isNew = false)
    {
        $model = $this->getModel();

        /**
         * @var Model $item
         */
        if ($item = $model->getItemByUrl($data['url'])) {
            $item->touch();
            $isNew = true;
            return $item;
        }

        // Can not use fill() because it will be required fillable properties
        foreach ($data as $key => $value) {
            if (empty($value) || is_null($value)) {
                continue;
            }
            $model->{$key} = $value;
        }

        $model->save();

        return $model;
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        $this->model = app('\App\\'.$this->getShortClassname());

        return $this->model;
    }

    /**
     * @return bool|Collection
     * @throws Exception
     */
    protected function getIndexLinks()
    {
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        $pages = $this->getCrawler()->getIndexLinks(
            $endpoint->url,
            (int) $endpoint->page,
            (int) $endpoint->page
        );

        if ($pages->isEmpty()) {
            $endpoint->failed = (int) $endpoint->failed + 1;
            if ($endpoint->failed === 10) {
                $endpoint->page = 1;
                $endpoint->failed = 0;
                $endpoint->save();
                return false;
            }

            $endpoint->page = (int) $endpoint->page + 1;
            $endpoint->save();
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

        return $pages;
    }

    /**
     * @return Model|null
     * @throws Exception
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
            throw new Exception('Crawler endpoint not found');
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
