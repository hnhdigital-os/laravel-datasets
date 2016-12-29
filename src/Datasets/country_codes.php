<?php

/**
 * Country Codes
 * 
 * @source https://github.com/datasets/country-codes
 */

return [
    'table'   => 'country_codes',
    'path'    => 'https://github.com/datasets/country-codes/raw/master/data/country-codes.csv',
    'mapping' => [
        'name' => 'name',
        'official_name_en'                 => 'official_name_en',
        'official_name_fr'                 => 'official_name_fr',
        'ISO3166-1-Alpha-2'                => 'iso3166_1_alpha_2',
        'ISO3166-1-Alpha-3'                => 'iso3166_1_alpha_3',
        'ISO3166-1-numeric'                => 'iso3166_1_numeric',
        'ITU'                              => 'itu',
        'MARC'                             => 'marc',
        'DS'                               => 'ds',
        'WMO'                              => 'wmo',
        'Dial'                             => 'dial',
        'FIFA'                             => 'fifa',
        'FIPS'                             => 'fips',
        'GAUL'                             => 'gual',
        'IOC'                              => 'ioc',
        'ISO4217-currency_alphabetic_code' => 'iso4217_currency_alphabetic_code',
        'ISO4217-currency_country_name'    => 'iso4217_currency_country_name',
        'ISO4217-currency_minor_unit'      => 'iso4217_currency_minor_unit',
        'ISO4217-currency_name'            => 'iso4217_currency_name',
        'ISO4217-currency_numeric_code'    => 'iso4217_currency_numeric_code',
        'is_independent'                   => 'is_independent',
        'Capital'                          => 'capital',
        'Continent'                        => 'continent',
        'TLD'                              => 'tld',
        'Languages'                        => 'languages',
        'geonameid'                        => 'geonameid',
        'EDGAR'                            => 'edgar',
    ],
    'modify' => [
        'name' => function(&$value, $data) {
            if (empty($value)) {
                $value = $data['official_name_en'];
            }
        },
    ],
];
