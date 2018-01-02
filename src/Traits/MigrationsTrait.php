<?php

namespace HnhDigital\Datasets\Traits;

use DB;

trait MigrationsTrait
{
    /**
     * Convert the column to binary(16) and create trigger.
     *
     * @param string $table
     * @param string $column
     *
     * @return void
     */
    public static function updateUuid($table, $column)
    {
        DB::unprepared('ALTER TABLE '.$table.' CHANGE '.$column.' '.$column.' BINARY(16) NULL DEFAULT NULL');
        DB::unprepared(
            'CREATE DEFINER = CURRENT_USER TRIGGER `'.$table.'_BEFORE_INSERT` BEFORE INSERT ON `'.$table.'` FOR EACH ROW '.
            'BEGIN  SET new.'.$column.' = UNHEX(REPLACE(UUID(),\'-\',\'\')); END'
        );
    }

    /**
     * Drop the trigger associated with this table.
     *
     * @param string $table
     *
     * @return void
     */
    public static function dropUuidTrigger($table)
    {
        DB::unprepared('DROP TRIGGER `'.$table.'_BEFORE_INSERT`');
    }
}
