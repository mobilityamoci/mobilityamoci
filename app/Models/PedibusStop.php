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

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function studentsPresenti()
    {
        return $this->hasMany(Student::class)->whereDoesntHave('absenceDays',function ($b) {
            $b->where('date',date('Y-m-d'));
        });
    }

    public function fullName()
    {
        return $this->pedibusLine->name . ' - ' . $this->name . " ($this->address)";
    }
}
