<?php

namespace Rockersweb\LaravelRlog;

use Monolog\Processor\GitProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;

class Rlog
{
    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if (config('laravel_rlog.log_request_details')) {
                $handler->pushProcessor(new WebProcessor);
            }

            $handler->pushProcessor(new RequestDataProcessor);

            if (config('laravel_rlog.log_memory_usage')) {
                $handler->pushProcessor(new MemoryUsageProcessor);
            }

            if (config('laravel_rlog.log_git_data')) {
                $handler->pushProcessor(new GitProcessor);
            }
        }
    }
}
