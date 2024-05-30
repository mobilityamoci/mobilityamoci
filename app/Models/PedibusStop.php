<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedibusStop extends Model
{

    public function point()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }
}
