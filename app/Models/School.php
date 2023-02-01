<?php

namespace App\Models;

use App\Http\Livewire\Students;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{

    public function students()
    {
        return $this->hasManyThrough(Student::class, Section::class,'school_id','section_id','id','id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
