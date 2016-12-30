<?php

use League\Flysystem\Adapter\Ftp as Adapter;
use League\Flysystem\Filesystem;

/*
 * Australian BSB data
 *
 * @source ftp://apca.com.au
 */

return [
    'table'   => 'australian_bsb',
    'path'    => function ($command) {

        // Connect to the host.
        $ftp = new Filesystem(new Adapter([
            'host' => 'apca.com.au',
        ]));

        // Failed to connect.
        try {
            $files = $ftp->listContents();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return 1;
        }

        $file_found = false;

        $time = time();

        while (!$file_found) {
            // Filter the folder's files to those containing 'BSBDirectory'
            $latest_file_name = sprintf('BSBDirectory%s-', date('My', $time));

            $files_filtered = array_filter($files, function ($value) use ($latest_file_name) {
                return stripos($value['filename'], $latest_file_name) !== false && $value['extension'] == 'csv';
            });

            if (count($files_filtered) > 0) {
                $file_found = true;
                $command->line('Using data from '.date('F Y', $time).'.');
                $this->line('');
                break;
            }

            $time = (new DateTime())->setTimestamp($time)->modify('-1 month')->getTimestamp();
        }

        // Reduce to the path to this file.
        $files_filtered = array_column($files_filtered, 'path');

        // Sort so we can get the most recent at the end (in case it isn't)
        sort($files_filtered);
        $latest_file = array_pop($files_filtered);

        $this->line('Path successfully generated.');

        // Return the path so that we download it.
        return sprintf('ftp://apca.com.au/%s', $latest_file);
    },
    'no_header' => true,
    'mapping'   => [
        0 => 'bsb',
        1 => 'bank',
        2 => 'branch',
        3 => 'address',
        4 => 'suburb',
        5 => 'state',
        6 => 'postcode',
        7 => 'payment_types',
    ],
    'import_keys' => [
        'bsb',
    ],
];
