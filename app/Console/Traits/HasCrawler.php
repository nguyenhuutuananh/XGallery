<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Traits;

use App\Services\Crawler\CrawlerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use ReflectionException;

/**
 * Trait HasCrawler
 * @package App\Console\Traits
 */
trait HasCrawler
{
    protected CrawlerInterface $crawler;

    protected Model $model;

    protected array $initData = [1, 0];

    public function handle()
    {
        $task = $this->argument('task');
        $this->output->writeln('<info>Running </info>'.$task);

        if (!method_exists($this, $task)) {
            return;
        }

        if ($task === 'fully') {
            $tmpFile = strtolower($this->getShortClassname()).'.tmp';
            if (Storage::disk('local')->exists($tmpFile)) {
                $this->initData = explode(':', Storage::disk('local')->get($tmpFile));
            }

            if ($this->initData[1] == 4) {
                Storage::disk('local')->delete($tmpFile);
                return;
            }
        }

        $result = call_user_func([$this, $task]);

        if ($task !== 'fully') {
            return;
        }

        if ($result) {
            $this->initData[1] = 0;
        } else {
            $this->initData[1]++;
        }

        $this->initData[0]++;
        Storage::disk('local')->put($tmpFile, $this->initData[0].':'.$this->initData[1]);
    }

    /**
     * @return string|null
     * @throws ReflectionException
     */
    private function getShortClassname(): ?string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * @return CrawlerInterface
     * @throws ReflectionException
     */
    protected function getCrawler(): CrawlerInterface
    {
        if (isset($this->crawler)) {
            return $this->crawler;
        }

        $this->crawler = app('\App\Services\Crawler\\'.$this->getShortClassname());

        return $this->crawler;
    }

    /**
     * Process specific daily index page
     * @return bool
     */
    abstract protected function daily(): bool;

    /**
     * Process WHOLE site by specific URL
     * @return bool
     */
    abstract protected function fully(): bool;

    abstract protected function index(): bool;

    abstract protected function item(): bool;

    /**
     * @param $data
     * @throws ReflectionException
     */
    protected function insertItem($data)
    {
        $model = $this->getModel();

        if ($model->where(['url' => $data['url']])->first()) {
            return;
        }

        $model->insert($data);
    }

    /**
     * @return Model
     * @throws ReflectionException
     */
    protected function getModel(): Model
    {
        if (isset($this->model)) {
            return $this->model;
        }

        $this->model = app('\App\\'.$this->getShortClassname());

        return $this->model;
    }

    /**
     * @return string|null
     */
    protected function getOptionUrl(): ?string
    {
        if (!$url = $this->option('url')) {
            $url = $this->ask('Please enter URL');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $url;
    }
}
