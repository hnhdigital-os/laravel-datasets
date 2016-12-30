<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataCountryCodes extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_country_codes';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'                               => 'integer',
        'uuid'                             => 'uuid',
        'name'                             => 'string',
        'official_name_en'                 => 'string',
        'official_name_fr'                 => 'string',
        'iso3166_1_alpha_2'                => 'string',
        'iso3166_1_alpha_3'                => 'string',
        'iso3166_1_numeric'                => 'string',
        'itu'                              => 'string',
        'marc'                             => 'string',
        'ds'                               => 'string',
        'wmo'                              => 'string',
        'dial'                             => 'string',
        'fifa'                             => 'string',
        'fips'                             => 'string',
        'gual'                             => 'string',
        'ioc'                              => 'string',
        'iso4217_currency_alphabetic_code' => 'string',
        'iso4217_currency_country_name'    => 'string',
        'iso4217_currency_minor_unit'      => 'integer',
        'iso4217_currency_name'            => 'string',
        'iso4217_currency_numeric_code'    => 'integer',
        'is_independent'                   => 'string',
        'capital'                          => 'string',
        'continent'                        => 'string',
        'tld'                              => 'string',
        'languages'                        => 'string',
        'geonameid'                        => 'integer',
        'edgar'                            => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'official_name_en',
        'official_name_fr',
        'iso3166_1_alpha_2',
        'iso3166_1_alpha_3',
        'iso3166_1_numeric',
        'itu',
        'marc',
        'ds',
        'wmo',
        'dial',
        'fifa',
        'fips',
        'gual',
        'ioc',
        'iso4217_currency_alphabetic_code',
        'iso4217_currency_country_name',
        'iso4217_currency_minor_unit',
        'iso4217_currency_name',
        'iso4217_currency_numeric_code',
        'is_independent',
        'capital',
        'continent',
        'tld',
        'languages',
        'geonameid',
        'edgar',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
