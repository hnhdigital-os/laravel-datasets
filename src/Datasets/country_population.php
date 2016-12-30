<?php

/**
 * Country Population
 * 
 * @source https://github.com/datasets/population
 */

return [
    'table'   => 'country_population',
    'path'    => 'https://github.com/datasets/population/raw/master/data/population.csv',
    'mapping' => [
        'Country Name' => 'name',
        'Country Code' => 'code',
        'Year'         => 'year',
        'Value'        => 'population'
    ],
    'import_keys' => [
        'code',
        'year',
    ]
];
