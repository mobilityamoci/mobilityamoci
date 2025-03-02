<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Trip extends Model
{
    use HasGeometryPoint, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['geometryPoint', 'transport1', 'transport2'];

    protected $appends = ['geom_address'];

    public function geometryPoint()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }

    public function geometryLine()
    {
        return $this->morphOne(GeometryLine::class, 'lineable');
    }

    public function delete()
    {
        DB::transaction(function () {
            $this->geometryLine()->delete();
            $this->geometryPoint()->delete();
            parent::delete();
        });
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

    public function previousTrip()
    {
        return $this->hasOne(Trip::class, 'student_id', 'student_id')->where('order', $this->order - 1);
    }

    public function hasMezzoPrivato(): bool
    {
        return in_array($this->transport_1, Transport::MEZZI_PRIVATI);
    }

    public function hasMezzoPubblico(): bool
    {
        return in_array($this->transport_1, Transport::MEZZI_PUBBLICI);
    }
}
