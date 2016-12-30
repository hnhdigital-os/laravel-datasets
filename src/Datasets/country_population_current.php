<?php

/**
 * Country Population Current
 * 
 * @source https://github.com/datasets/population
 */

return [
    'table'   => 'country_population_current',
    'path'    => 'https://github.com/datasets/population/raw/master/data/population.csv',
    'mapping' => [
        'Country Name' => 'name',
        'Country Code' => 'code',
        'Year'         => 'year',
        'Value'        => 'population',  
    ],
    'filter'  => function($reader) {

        if (!isset($_ENV['population_year'])) {
            // Find most recent year
            $year_list = array_unique(iterator_to_array($reader->fetchColumn(2), false));
            rsort($year_list);
            $_ENV['population_year'] = $year_list[1];
        }

        $population_year = $_ENV['population_year'];

        return $reader->addFilter(function($row) use ($population_year) { return array_get($row, 2, 0) === $population_year; });
    },
    'import_keys' => [
        'code',
        'year',
    ]
];
