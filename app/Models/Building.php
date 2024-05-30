<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Building extends Model
{

    use HasGeometryPoint;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['geometryPoint'];

    protected $appends = ['geom_address'];


    public function school(): BelongsTo
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

    public function fullName()
    {
        return $this->name . ' di ' . $this->school->name;
    }

    public function centerPoint()
    {
        $point = $this->geometryPoint->getWGS84Point();
        return ['lat' => $point->getLatitude(), 'lon' => $point->getLongitude(), 'title' => $this->fullName()];
    }


}
