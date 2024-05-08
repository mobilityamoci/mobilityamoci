<?php

namespace App\Models;

use Illuminate\Support\Str;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Survey extends \MattDaneshvar\Survey\Models\Survey
{

    use HasRelationships;

    protected $fillable = ['school_id', 'settings', 'name', 'uuid'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

    public function entries()
    {
        $students = getUserStudents();
        return $this->hasMany(Entry::class)->whereIn('participant_id', $students->pluck('id')->toArray());
    }

    public function mySections()
    {
        return $this->morphedByMany(Section::class, 'surveyable')->withTimestamps();
    }

    public function students()
    {
        $students = getUserStudents();
        return $this->hasManyDeepFromRelations($this->mySections(), (new Section)->students())->whereIn('students.id', $students->pluck('id')->toArray());
    }
}
