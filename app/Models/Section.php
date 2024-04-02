<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Section extends Model
{
    use HasRelationships;

    protected $guarded = ['id', 'created_at','updated_at'];


    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function students_strada()
    {
        return $this->students()->whereHas('trip1', function ($q) {
            $q->whereIn('transport_1', Transport::MEZZI_PRIVATI);
        });
    }

    public function students_mezzi()
    {
        return $this->students()->whereHas('trip1', function ($q) {
            $q->whereIn('transport_1', Transport::MEZZI_PUBBLICI);
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function users()
    {
        return $this->morphToMany(User::class, 'associable','associables');
    }

    public function fullName(): string
    {
        return $this->name .' di '.$this->school->name;
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function trips()
    {
        return $this->hasManyDeepFromRelations($this->students(), (new Student())->trips());
    }

    public function geometry_lines()
    {
        return $this->hasManyDeepFromRelations($this->trips(), (new Trip())->geometryLine());

    }
}
