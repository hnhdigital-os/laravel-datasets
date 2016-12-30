# Laravel Datasets

Provides console commands, models and migration scripts to syncronize datasets mainly found at [github.com/datasets](https://github.com/datasets) and other sites. Configuration is done by array and any data manipulation using closures.

[![Latest Stable Version](https://poser.pugx.org/bluora/laravel-datasets/v/stable.svg)](https://packagist.org/packages/bluora/laravel-datasets) [![Total Downloads](https://poser.pugx.org/bluora/laravel-datasets/downloads.svg)](https://packagist.org/packages/bluora/laravel-datasets) [![Latest Unstable Version](https://poser.pugx.org/bluora/laravel-datasets/v/unstable.svg)](https://packagist.org/packages/bluora/laravel-datasets) [![License](https://poser.pugx.org/bluora/laravel-datasets/license.svg)](https://packagist.org/packages/bluora/laravel-datasets)

[![Build Status](https://travis-ci.org/bluora/laravel-datasets.svg?branch=master)](https://travis-ci.org/bluora/laravel-datasets) [![StyleCI](https://styleci.io/repos/77605381/shield?branch=master)](https://styleci.io/repos/77605381) [![Test Coverage](https://codeclimate.com/github/bluora/laravel-datasets/badges/coverage.svg)](https://codeclimate.com/github/bluora/laravel-datasets/coverage) [![Issue Count](https://codeclimate.com/github/bluora/laravel-datasets/badges/issue_count.svg)](https://codeclimate.com/github/bluora/laravel-datasets) [![Code Climate](https://codeclimate.com/github/bluora/laravel-datasets/badges/gpa.svg)](https://codeclimate.com/github/bluora/laravel-datasets) 

This package has been developed by H&H|Digital, an Australian botique developer. Visit us at [hnh.digital](http://hnh.digital).

## Install

Via composer:

`$ composer require bluora/laravel-datasets ~1.0`

### Laravel configuration

Enable the service provider by editing config/app.php:

```php
    'providers' => [
        ...
        Bluora\LaravelDatasets\ServiceProvider::class,
        ...
    ];
```

## Contributing

Please see [CONTRIBUTING](https://github.com/bluora/laravel-datasets/blob/master/CONTRIBUTING.md) for details.

## Credits

* [Rocco Howard](https://github.com/therocis)
* [All Contributors](https://github.com/bluora/laravel-datasets/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/bluora/laravel-datasets/blob/master/LICENSE) for more information.
