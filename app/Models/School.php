<?php

namespace App\Models;

use App\Http\Livewire\Students;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
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
        return $this->hasMany(Survey::class);
    }


    public function scopeActive(Builder $query)
    {
        $query->whereHas('sections');
    }
}
