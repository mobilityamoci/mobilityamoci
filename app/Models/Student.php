<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use MattDaneshvar\Survey\Models\Entry;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Student extends Model
{
    use SoftDeletes, HasGeometryPoint, HasRelationships;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['geometryPoint'];

    protected $appends = ['geom_address'];

    public function geometryPoint()
    {
        return $this->morphOne(GeometryPoint::class, 'georefable');
    }

    public function schoolGeometryPoint()
    {
        return $this->hasOneDeepFromRelations($this->section(), (new Section())->building(), (new Building())->geometryPoint());
    }

    public function delete()
    {
        DB::transaction(function () {
            $this->geometryPoint()->delete();
            $this->trips()->each(function ($trip) {
                $trip->delete();
            });
            parent::delete();
        });
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function surveys()
    {
        return $this->hasManyDeepFromRelations($this->section(), (new Section)->surveys());
    }

    public function submittedSurveys()
    {
        return $this->belongsToMany(Survey::class, 'entries', 'participant_id', 'survey_id');
    }

    public function surveysToSubmit()
    {
        return $this->surveys->filter(function ($survey) {
            return !in_array($survey->id, $this->submittedSurveys->pluck('id')->toArray());
        });
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

    public function trip1(): HasOne
    {
        return $this->hasOne(Trip::class)->where('order', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fullName(): string
    {
        return ucwords($this->name . ' ' . $this->surname);
    }

    public function fullInfo(): string
    {
        $string = $this->fullName();
        $string .= ' - ' . $this->section->fullName();

        return $string;
    }




//    public function trip1()
//    {
//        $builder = $this->trips()->where('order',1);
//
//        return new HasOne($builder->getQuery(), $this, 'student_id','id');
//    }

    public function trip2()
    {
        $builder = $this->trips()->where('order', 2);

        return new HasOne($builder->getQuery(), $this, 'student_id', 'id');
    }

    public function trip3()
    {
        $builder = $this->trips()->where('order', 3);

        return new HasOne($builder->getQuery(), $this, 'student_id', 'id');
    }

    public function building()
    {
        return $this->hasOneThrough(Building::class, Section::class);
    }


}
