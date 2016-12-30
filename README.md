    ___        _                     _         _  _      __                                 _
   /   \ __ _ | |_  __ _  ___   ___ | |_  ___ | || |    / /   __ _  _ __  __ _ __   __ ___ | |
  / /\ // _` || __|/ _` |/ __| / _ \| __|/ __|| || |_  / /   / _` || '__|/ _` |\ \ / // _ \| |
 / /_//| (_| || |_| (_| |\__ \|  __/| |_ \__ \|__   _|/ /___| (_| || |  | (_| | \ V /|  __/| |
/___,'  \__,_| \__|\__,_||___/ \___| \__||___/   |_|  \____/ \__,_||_|   \__,_|  \_/  \___||_|

Provides console commands, models and migration scripts to syncronize datasets. Datasets are defined by array based configuration whilst advanced manipulation or data retrieval can be done through inline closures.

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

## Usage


### List

`$ php artisan datasets:list`

List available datasets that have been added to this package.

### Migrate

`$ php artisan datasets:migrate {dataset}`

Setup the table in the database. This will create the migration file in the right spot and add to the migrations table.

### Sync

`$ php artisan datasets:sync {dataset}`

Downloads the data and insert/updates the existing records.

## Contributing

Please see [CONTRIBUTING](https://github.com/bluora/laravel-datasets/blob/master/CONTRIBUTING.md) for details.

## Credits

* [Rocco Howard](https://github.com/therocis)
* [All Contributors](https://github.com/bluora/laravel-datasets/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/bluora/laravel-datasets/blob/master/LICENSE) for more information.
