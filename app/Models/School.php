<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class School extends Model
{

    use HasRelationships;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Section::class, 'school_id', 'section_id', 'id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function users()
    {
        return $this->morphToMany(User::class, 'associable', 'associables');
    }

    public function pedibusLines()
    {
        return $this->hasMany(PedibusLine::class);
    }

    public function pedibusStops()
    {
        return $this->hasManyDeepFromRelations($this->pedibusLines(), (new PedibusLine())->stops());
    }

    public function usersToAccept()
    {
        return $this->morphToMany(User::class, 'associable', 'associables')
            ->whereNull('accepted_at');
    }

    public function trips()
    {
        return $this->hasManyDeepFromRelations($this->students(), (new Student())->trips());
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class)->orWhere('is_default', true);
    }


    public function scopeActive($query)
    {
        $query->whereHas('sections');
    }

    public function centerPoints(): array
    {
        $buildings = $this->buildings;
        $points = [];
        foreach ($buildings as $building) {
            $points[] = $building->centerPoint();
        }
        return $points;
    }
}
