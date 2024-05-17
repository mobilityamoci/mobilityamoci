<?php

namespace App\Models;

use App\Services\QgisService;
use App\Traits\HasGeometryPoint;
use geoPHP;
use Illuminate\Database\Eloquent\Model;

class GeometryLine extends Model
{
    use HasGeometryPoint;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected array $postgisColumns = [
        'line' => [
            'type' => 'geometry',
            'srid' => 32632,
        ],
    ];

    public function toArrayWGS84()
    {
        $line = geoPHP::load($this->line);
        return QgisService::lineToArrayOfPointsWGS84($line);
    }

}
