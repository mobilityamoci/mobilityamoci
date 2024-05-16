<?php

namespace App\Models;

use App\Services\QgisService;
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeometryPoint extends Model
{

    use HasPostgisColumns;

    protected $guarded = ['id','created_at','updated_at'];

    protected array $postgisColumns = [
        'point' => [
            'type' => 'geometry',
            'srid' => 32632,
        ],
    ];

    public function getWGS84Point()
    {
        return QgisService::to4326($this->point);
    }
}
