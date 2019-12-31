[![Latest Stable Version](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/v/stable)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![Total Downloads](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/downloads)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![License](https://poser.pugx.org/freshbitsweb/laravel-log-enhancer/license)](https://packagist.org/packages/freshbitsweb/laravel-log-enhancer)
[![StyleCI](https://styleci.io/repos/126970369/shield?branch=master)](https://styleci.io/repos/126970369)
[![Build Status](https://travis-ci.org/freshbitsweb/laravel-log-enhancer.svg?branch=master)](https://travis-ci.org/freshbitsweb/laravel-log-enhancer)

# Laravel Rlog (Laravel 5.6 to Laravel 6.0)
 Laravel Rlog's logging system helps a lot for storing data as well as while troubleshooting some hidden bugs. The data related to the exception automatically gets logged whenever something goes wrong.



## Requirements

* PHP 7.1+
* Laravel 5.6+


## Installation

1) Install the package by running this command in your terminal/cmd:
```
composer require rockersweb/laravel-rlog
```

2) Add this package's LogEnhancer class to the tap option of your log channel in **config/logging.php**:
```
'production_stack' => [
    'driver' => 'stack',
    'tap' => [Rockersweb\LaravelRlog\Rlog::class],
    'channels' => ['daily', 'slack'],
],
```

Optionally, you can import config file by running this command in your terminal/cmd:
```
php artisan vendor:publish --tag=laravel-rlog-config
```

It has following configuration settings:
* (bool) log_request_details => Set to *true* if you wish to log request data. [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/WebProcessor.php)

* (bool) log_input_data => Set to *true* if you wish to log user input data

* (bool) log_request_headers => Set to *true* if you wish to log request headers

* (bool) log_session_data => Set to *true* if you wish to log session data

* (bool) log_memory_usage => Set to *true* if you wish to log memory usage [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/MemoryUsageProcessor.php)

* (bool) log_git_data => Set to *true* if you wish to log git branch and commit details [Reference](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Processor/GitProcessor.php)

* (array) ignore_input_fields => If input data is being sent, you can specify the inputs from the user that should not be logged. for example, password,cc number, etc.

## Authors

* [**Raksh Patel**](https://github.com/rockers007) - *Initial work*


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Special Thanks to

* [Laravel](https://laravel.com) Community
