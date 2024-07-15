<?php

namespace App\Models;

use App\Services\QgisService;
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeometryPoint extends Model
{

    use HasPostgisColumns, SoftDeletes;

    protected $guarded = ['id','created_at','updated_at'];

    protected array $postgisColumns = [
        'point' => [
            'type' => 'geometry',
            'srid' => 32632,
        ],
    ];

    public function getWGS84Point()
    {
        return QgisService::toWGS84($this->point);
    }
}
