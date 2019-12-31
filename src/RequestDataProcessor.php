<?php

namespace Rockersweb\LaravelRlog;

class RequestDataProcessor
{
    /**
     * Adds additional request data to the log message.
     */
    public function __invoke($record)
    {
        if (config('laravel_rlog.log_input_data')) {
            $record['extra']['inputs'] = request()->except(config('laravel_rlog.ignore_input_fields'));
        }

        if (config('laravel_rlog.log_request_headers')) {
            $record['extra']['headers'] = request()->header();
        }

        if (config('laravel_rlog.log_session_data')) {
            $record['extra']['session'] = session()->all();
        }

        return $record;
    }
}
