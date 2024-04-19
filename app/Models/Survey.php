<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends \MattDaneshvar\Survey\Models\Survey
{
    protected $fillable = ['school_id', 'settings', 'name'];
}
