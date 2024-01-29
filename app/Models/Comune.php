<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comune extends Model
{
    protected $table = 'limiti_pc';
    protected $connection = 'basi_carto';
}
