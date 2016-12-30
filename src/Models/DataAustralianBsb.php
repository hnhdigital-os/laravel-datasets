<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataAustralianBsb extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_australian_bsb';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'uuid'          => 'uuid',
        'bank'          => 'string',
        'bsb'           => 'string',
        'branch'        => 'string',
        'address'       => 'string',
        'suburb'        => 'string',
        'state'         => 'string',
        'postcode'      => 'integer',
        'payment_types' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bsb',
        'bank',
        'branch',
        'address',
        'suburb',
        'state',
        'postcode',
        'payment_types',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

}
