<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{

    use HasGeometryPoint;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['geometryPoint'];

    protected $appends = ['geom_address'];


    public function schools(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function geometryPoint()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }


}
