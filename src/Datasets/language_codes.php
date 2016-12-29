<?php

/**
 * Language Codes
 * 
 * @source https://github.com/datasets/language-codes
 */

return [
    'url'     => 'https://github.com/datasets/language-codes/raw/master/data/language-codes-full.csv',
    'table'   => 'language_codes',
    'mapping' => [
        'name'                           => 'name',
        'official_name_en'               => 'official_name_en',
        'official_name_fr'               => 'official_name_fr',
        'ISO3166-1-Alpha-2'              => 'iso3166_1_alpha_2',
        'ISO3166-1-Alpha-3'              => 'iso3166_1_alpha_3',
        'ISO3166-1-numeric'              => 'iso3166_1_numeric',
        'Dial'                           => 'dial',
        'ISO4217-currency_name'          => 'iso4217_currency_name',
        'ISO4217-currency_minor_unit'    => 'iso4217_currency_minor_unit',
        'ISO4217-currency_numeric_code'  => 'iso4217_currency_numeric_code',
    ],
];
