<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedibusLine extends Model
{

    protected $guarded = ['id','created_at','updated_at'];

    public function line()
    {
        return $this->morphOne(GeometryLine::class, 'lineable');
    }

    public function stops()
    {
        return $this->hasMany(PedibusStop::class);
    }
}
