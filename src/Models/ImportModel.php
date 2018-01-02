<?php

namespace HnhDigital\Datasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class ImportModel extends EloquentModel
{
    /**
     * Set table.
     *
     * @param void
     */
    public function setTable($table)
    {
        $this->table = $table;
    }
}
