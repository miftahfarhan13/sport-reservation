<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'province_id' => 'integer',
        'province_lat' => 'float',
        'province_lon' => 'float',
        'province_capital_city_id' => 'integer',
        'timezone' => 'integer',
    ];

    /**
     * Indicates if the model primary key.
     *
     * @var bool
     */
    protected $primaryKey = 'province_id';

    public function __construct()
    {
        $this->table = config('laraciproid.province');
    }

    public function cities()
    {
        return $this->hasMany(
            'App\Models\City',
            'province_id'
        );
    }
}