<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PedibusLine extends Model
{
    use HasRelationships;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function line()
    {
        return $this->morphOne(GeometryLine::class, 'lineable');
    }

    public function stops()
    {
        return $this->hasMany(PedibusStop::class)->orderBy('order');
    }

    public function students()
    {
        return $this->hasManyDeepFromRelations($this->stops(), (new PedibusStop())->students());
    }


    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function toArrayWGS84()
    {
        return $this->line->toArrayWGS84();
    }

    public function centerPoint()
    {
        return $this->school->centerPoints()[0];
    }

    public function studentMarkers()
    {
        return $this->students()->map(function ($student) {
            return $student->marker;
        });
    }


}
