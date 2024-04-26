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

    public function mySections()
    {
        return $this->morphedByMany(Section::class, 'surveyable')->withTimestamps();
    }

    public function students()
    {
        return $this->hasManyDeepFromRelations($this->mySections(), (new Section)->students());
    }
}
