<?php

namespace Bluora\LaravelDatasets;

trait MigrationsTrait
{
    public function updateUuid($table, $column)
    {
        DB::unprepared('ALTER TABLE '.$table.' CHANGE '.$column.' '.$column.' BINARY(16) NULL DEFAULT NULL');
        DB::unprepared(
            'CREATE DEFINER = CURRENT_USER TRIGGER `'.$table.'_BEFORE_INSERT` BEFORE INSERT ON `'.$table.'` FOR EACH ROW '.
            'BEGIN  SET new.'.$column.' = UNHEX(REPLACE(UUID(),\'-\',\'\')); END'
        );
    }

    public function dropUuidTrigger($table)
    {
        DB::unprepared('DROP TRIGGER `'.$table.'_BEFORE_INSERT`');
    }
}
