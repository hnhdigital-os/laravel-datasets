 ```
    ___        _                     _         _  _      __                                 _
   /   \ __ _ | |_  __ _  ___   ___ | |_  ___ | || |    / /   __ _  _ __  __ _ __   __ ___ | |
  / /\ // _` || __|/ _` |/ __| / _ \| __|/ __|| || |_  / /   / _` || '__|/ _` |\ \ / // _ \| |
 / /_//| (_| || |_| (_| |\__ \|  __/| |_ \__ \|__   _|/ /___| (_| || |  | (_| | \ V /|  __/| |
/___,'  \__,_| \__|\__,_||___/ \___| \__||___/   |_|  \____/ \__,_||_|   \__,_|  \_/  \___||_|
```

Provides console commands, models and migration scripts to syncronize datasets into your applications database. Datasets are defined by array based configuration whilst advanced manipulation or data retrieval can be done through inline closures.

[![Latest Stable Version](https://poser.pugx.org/hnhdigital-os/laravel-datasets/v/stable.svg)](https://packagist.org/packages/hnhdigital-os/laravel-datasets) [![Total Downloads](https://poser.pugx.org/hnhdigital-os/laravel-datasets/downloads.svg)](https://packagist.org/packages/hnhdigital-os/laravel-datasets) [![Latest Unstable Version](https://poser.pugx.org/hnhdigital-os/laravel-datasets/v/unstable.svg)](https://packagist.org/packages/hnhdigital-os/laravel-datasets) [![Built for Laravel](https://img.shields.io/badge/Built_for-Laravel-green.svg)](https://laravel.com/) [![License](https://poser.pugx.org/hnhdigital-os/laravel-datasets/license.svg)](https://packagist.org/packages/hnhdigital-os/laravel-datasets)

[![Build Status](https://travis-ci.org/hnhdigital-os/laravel-datasets.svg?branch=master)](https://travis-ci.org/hnhdigital-os/laravel-datasets) [![StyleCI](https://styleci.io/repos/77605381/shield?branch=master)](https://styleci.io/repos/77605381) [![Test Coverage](https://codeclimate.com/github/hnhdigital-os/laravel-datasets/badges/coverage.svg)](https://codeclimate.com/github/hnhdigital-os/laravel-datasets/coverage) [![Issue Count](https://codeclimate.com/github/hnhdigital-os/laravel-datasets/badges/issue_count.svg)](https://codeclimate.com/github/hnhdigital-os/laravel-datasets) [![Code Climate](https://codeclimate.com/github/hnhdigital-os/laravel-datasets/badges/gpa.svg)](https://codeclimate.com/github/hnhdigital-os/laravel-datasets) 

This package has been developed by H&H|Digital, an Australian botique developer. Visit us at [hnh.digital](http://hnh.digital).

## Install

Via composer:

`$ composer require hnhdigital-os/laravel-datasets ~1.0`

This package's service provider will autoload from Laravel 5.5.

To enable the service provider in versions prior to Laravel 5.4, edit the config/app.php:

```php
    'providers' => [
        ...
        Bluora\LaravelDatasets\ServiceProvider::class,
        ...
    ];
```

### Available datasets

#### [DATA.OKFN Collection](https://github.com/hnhdigital-os/laravel-datasets-okfn) (hnhdigital-os/laravel-datasets-okfn)

NOTE: This collection is included by default when you install this package.

* Country Codes
* Country Population
* Country Population (current)
* Language Codes

#### [Australia Collection](https://github.com/hnhdigital-os/laravel-datasets-australia) (hnhdigital-os/laravel-datasets-australia)

* Banks
* BSB
* Postcodes


Need a dataset? Request it, Pull Request it, or build it. Use the DATA.OKFN as a template.

If you do setup your own collection, please let us know so that we can put it on the official list.

## Usage

### List

`$ php artisan datasets:list`

Lists all the available dataset collections available to be installed.

### Install

`$ php artisan datasets:install {dataset}`

Installs the specified dataset. This will create the table in the database and do an initial sync of the data.

### Migrate

`$ php artisan datasets:migrate {dataset}`

Setup the table in the database. This will create the migration file in the right spot and add to the migrations table.

Use this console command when scheduling dataset update.

### Sync

`$ php artisan datasets:sync {dataset}`

Downloads the data and insert/updates the existing records.

## Contributing

Please see [CONTRIBUTING](https://github.com/hnhdigital-os/laravel-datasets/blob/master/CONTRIBUTING.md) for details.

## Credits

* [Rocco Howard](https://github.com/RoccoHoward)
* [All Contributors](https://github.com/hnhdigital-os/laravel-datasets/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/hnhdigital-os/laravel-datasets/blob/master/LICENSE) for more information.
