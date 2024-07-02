<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceDays extends Model
{
    protected $casts = ['date' => 'date'];

    protected $guarded = ['id','created_at','updated_at'];
}
