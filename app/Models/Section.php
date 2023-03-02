<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at','updated_at'];

    public function students()
    {
        return $this->hasMany(Student::class);
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
}
