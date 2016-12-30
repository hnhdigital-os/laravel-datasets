<?php

use League\Flysystem\Adapter\Ftp as Adapter;
use League\Flysystem\Filesystem;

/*
 * Australian BSB data
 *
 * @source ftp://apca.com.au
 */

return [
    'table'   => 'australian_banks',
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
        $has_error = false;

        $time = time();
        $count = 0;

        while (!$file_found && !$has_error) {
            // Filter the folder's files to those containing 'KEY TO ABBREVIATIONS AND BSB NUMBERS'
            $latest_file_name = sprintf('KEY TO ABBREVIATIONS AND BSB NUMBERS (%s)', date('M Y', $time));

            $command->line(sprintf("Checking '%s' is available...", $latest_file_name));

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
            $count++;

            // Only check for the last year.
            if ($count == 12) {
                $has_error = true;
            }
        }

        if ($has_error) {
            $command->error('Attempted to select file for the last 12 months.');

            return 1;
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
        2 => 'bsb',
        0 => 'bank',
        1 => 'title',
    ],
    'change_read_data' => function ($data) {

        $result = [];
        foreach ($data as $row) {
            $bsb_list = explode(',', str_replace(' ', '', array_get($row, 'bsb', '')));
            foreach ($bsb_list as $bsb) {
                if (empty($bsb)) {
                    break;
                }
                if (strlen($bsb) == 1) {
                    $bsb = str_pad($bsb, 2, '0', STR_PAD_LEFT);
                }
                $result[] = [
                    'bsb'   => $bsb,
                    'bank'  => $row['bank'],
                    'title' => $row['title'],
                ];
            }
        }
        return $result;
    },
    'import_keys' => [
        'bsb',
        'bank'
    ],
];
