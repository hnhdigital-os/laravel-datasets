<?php

/**
 * Country Population Current
 * 
 * @source https://github.com/datasets/population
 */

return [
    'url'     => 'https://github.com/datasets/population/raw/master/data/population.csv',
    'table'   => 'country_population_current',
    'mapping' => [
        'Year'  => 'year',
        'Value' => 'total',  
    ],
    'filter' => function($reader) {
        return $reader->addFilter(function($row) { return $row[2] === date('Y'); });
    }
];
