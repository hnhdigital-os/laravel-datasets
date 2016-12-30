<?php

namespace Bluora\LaravelDatasets\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class DataLanguageCodes extends EloquentModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'data_language_codes';

    /**v
     * The attributes that require casting
     *
     * @var array
     */
    protected $casts = [
        'id'                 => 'integer',
        'uuid'               => 'uuid',
        'iso3166_1_alpha_2'  => 'string',
        'iso3166_1_alpha_3'  => 'string',
        'iso3166_1_alpha_3t' => 'string',
        'english'            => 'string',
        'french'             => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iso3166_1_alpha_2',
        'iso3166_1_alpha_3',
        'iso3166_1_alpha_3t',
        'english',
        'french',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
