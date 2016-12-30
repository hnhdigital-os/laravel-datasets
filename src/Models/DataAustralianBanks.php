<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataAustralianBanks extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_australian_banks';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'    => 'integer',
        'uuid'  => 'uuid',
        'bsb'   => 'string',
        'bank'  => 'string',
        'title' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bsb',
        'bank',
        'title',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
