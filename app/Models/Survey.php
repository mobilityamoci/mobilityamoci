<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Survey extends \MattDaneshvar\Survey\Models\Survey
{
    protected $fillable = ['school_id', 'settings', 'name', 'uuid'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    public function mySections()
    {
        return $this->morphedByMany(Section::class, 'surveyable')->withTimestamps();
    }
}
