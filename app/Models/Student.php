<?php

namespace App\Models;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at','updated_at'];

    public function delete()
    {
        DB::transaction(function()
        {
            $this->trips()->delete();
            parent::delete();
        });
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function school()
    {
        return $this->section->school;
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class)
            ->orderBy('order');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fullName(): string
    {
        return ucwords($this->name.' '.$this->surname);
    }

    public function fullInfo(): string
    {
        $string = $this->fullName();
        $string .= ' - '.$this->section->fullName();

        return $string;
    }




    public function trip1()
    {
        $builder = $this->trips()->where('order',1);

        return new HasOne($builder->getQuery(), $this, 'student_id','id');
    }

    public function trip2()
    {
        $builder = $this->trips()->where('order',2);

        return new HasOne($builder->getQuery(), $this, 'student_id','id');
    }

    public function trip3()
    {
        $builder = $this->trips()->where('order',3);

        return new HasOne($builder->getQuery(), $this, 'student_id','id');
    }
}
