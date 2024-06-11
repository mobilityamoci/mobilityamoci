<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PedibusLine extends Model
{
    use HasRelationships, HasApiTokens;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

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
    public function toArrayWGS84API()
    {
        return $this->line->toArrayWGS84API();
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
