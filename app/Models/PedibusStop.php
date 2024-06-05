<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedibusStop extends Model
{

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function pedibusLine()
    {
        return $this->belongsTo(PedibusLine::class);
    }

    public function point()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }
}
