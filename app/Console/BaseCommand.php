<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package App\Console
 */
class BaseCommand extends Command
{
    use Notifiable;

    const PROGRESSBAR_FORMAT = " %current%/%max% %message%"
    .PHP_EOL
    ." %step%/%steps% URLs [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%"
    .PHP_EOL." %info% [%status%]";

    protected ProgressBar $progressBar;

    /**
     * @param  int  $max
     * @return ProgressBar
     */
    protected function createProgressBar($max = 0): ProgressBar
    {
        $this->progressBar = $this->output->createProgressBar($max);
        $this->progressBar->setFormat(self::PROGRESSBAR_FORMAT);
        $this->progressBar->setMessage('Pages', 'message');
        $this->progressBar->setMessage('', 'steps');
        $this->progressBar->setMessage('', 'step');
        $this->progressBar->setMessage('', 'info');
        $this->progressBar->setMessage('', 'status');

        return $this->progressBar;
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return mixed|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->completed(parent::execute($input, $output));
    }

    /**
     * @param $status
     * @return mixed
     */
    protected function completed($status)
    {
        if (isset($this->progressBar)) {
            $this->progressBar->finish();
        }

        $this->output->newLine();
        $this->comment('Completed');

        return $status;
    }
}
