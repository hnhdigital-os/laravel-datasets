<?php

/**
 * Australian Postcodes
 *
 * @source https://github.com/charliesome/australia_postcode
 */

return [
    'table'   => 'australian_postcodes',
    'path'    => 'https://github.com/charliesome/australia_postcode/raw/master/lib/australia/postcode/data.csv',
    'mapping' => [
        'postcode' => 'postcode',
        'suburb'   => 'suburb',
        'state'    => 'state',
        'dc'       => 'dc',
        'type'     => 'type',
        'lat'      => 'latitude',
        'lon'      => 'longitude',
    ],
    'modify' => [
        'type' => function (&$value, $data_row) {
            $value = trim($value);
        },
        'latitude' => function (&$value, $data_row) {
            if (empty($value)) {
                $value = 0;
            }
            $value = (float)$value;
        },
        'longitude' => function (&$value, $data_row) {
            if (empty($value)) {
                $value = 0;
            }
            $value = (float)$value;
        },
    ],
    'import_keys' => [
        'postcode',
        'suburb',
    ],
];
