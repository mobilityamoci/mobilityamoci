<?php

namespace App\Models;

use App\Traits\HasGeometryPoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeometryLine extends Model
{
    use HasGeometryPoint;

    protected $guarded = ['id','created_at','updated_at'];

    protected array $postgisColumns = [
        'line' => [
            'type' => 'geometry',
            'srid' => 32632,
        ],
    ];

    public function toArray()
    {
        $line = \geoPHP::load($this->line);
        dd($line);
    }
}
