<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataAustralianPostcodes extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_australian_postcodes';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'uuid'      => 'uuid',
        'postcode'  => 'integer',
        'suburb'    => 'string',
        'state'     => 'string',
        'dc'        => 'string',
        'type'      => 'string',
        'latitude'  => 'double',
        'longitude' => 'double',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'postcode',
        'suburb',
        'state',  
        'dc',   
        'type',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
