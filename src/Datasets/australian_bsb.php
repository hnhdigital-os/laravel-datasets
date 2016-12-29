<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

/**
 * Australian BSB data
 * 
 * @source ftp://apca.com.au
 */

return [
    'url'     => false,
    'table'   => 'australian_bsb',
    'source_path' => function() {

        // Connect to the host.
        $ftp = new Filesystem(new Adapter([
            'host' => 'apca.com.au',
        ]));

        // Failed to connect.
        try {
            $files = $ftp->listContents();
        } catch (\Exception $e) {

            return 1;
        }

        // Filter the folder's files to those containing 'BSBDirectory'
        $latest_file_name = sprintf('BSBDirectory%s-', date('My', time()));

        $files_filtered = array_filter($files, function($value) use ($latest_file_name) {
            return stripos($value['filename'], $latest_file_name) !== false && $value['extension'] == 'csv';
        });

        if (count($files_filtered) == 0) {

            return 1;
        }

        // Reduce to the path to this file.
        $files_filtered = array_column($files_filtered, 'path');

        // Sort so we can get the most recent at the end (in case it isn't)
        sort($files_filtered);
        $latest_file = array_pop($files_filtered);

        // Return the path so that we download it.
        return sprintf('ftp://apca.com.au/%s', $latest_file);
    },
    'mapping' => [
        0 => 'bsb',
        1 => 'bank',
        2 => 'branch',
        3 => 'address',
        4 => 'suburb',
        5 => 'state',
        6 => 'postcode',
        7 => 'payment_types',
    ]
];
