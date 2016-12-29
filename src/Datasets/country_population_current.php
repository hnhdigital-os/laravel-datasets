<?php

/**
 * Country Population Current
 * 
 * @source https://github.com/datasets/population
 */

return [
    'table'         => 'country_population_current',
    'download_path' => 'https://github.com/datasets/population/raw/master/data/population.csv',
    'mapping'       => [
        'Year'  => 'year',
        'Value' => 'total',  
    ],
    'filter'        => function($reader) {
        return $reader->addFilter(function($row) { return $row[2] === date('Y'); });
    }
];
