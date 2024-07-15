<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $casts = ['graph_data' => 'collection'];
}
