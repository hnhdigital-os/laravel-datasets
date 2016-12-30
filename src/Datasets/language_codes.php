<?php

/**
 * Language Codes.
 *
 * @source https://github.com/datasets/language-codes
 */

return [
    'table'   => 'language_codes',
    'path'    => 'https://github.com/datasets/language-codes/raw/master/data/language-codes-full.csv',
    'mapping' => [
        'alpha2'   => 'iso3166_1_alpha_2',
        'alpha3-b' => 'iso3166_1_alpha_3',
        'alpha3-t' => 'iso3166_1_alpha_3t',
        'English'  => 'english',
        'French'   => 'french',
    ],
    'import_keys' => [
        'iso3166_1_alpha_3',
    ],
];
