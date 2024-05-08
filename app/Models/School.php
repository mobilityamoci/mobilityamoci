<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class School extends Model
{

    use HasRelationships;

    protected $guarded = ['id', 'created_at','updated_at'];

    public function students()
    {
        return $this->hasManyThrough(Student::class, Section::class,'school_id','section_id','id','id');
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
        return $this->morphToMany(User::class, 'associable','associables');
    }

    public function usersToAccept()
    {
        return $this->morphToMany(User::class, 'associable','associables')
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
}
