<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataCountryPopulation extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_country_population';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'uuid'       => 'uuid',
        'name'       => 'string',
        'code'       => 'string',
        'year'       => 'integer',
        'population' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'year',
        'population',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
