<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasGeometryPoint;

    protected $guarded = ['id','created_at','updated_at'];

    protected $with = ['geometryPoint'];

    protected $appends = ['geom_address'];
    public function geometryPoint()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }

    public function transport1(): BelongsTo
    {
        return $this->belongsTo(Transport::class, 'transport_1', 'id');
    }

    public function transport2(): BelongsTo
    {
        return $this->belongsTo(Transport::class, 'transport_2', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
