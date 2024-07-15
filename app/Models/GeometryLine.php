<?php

namespace App\Models;

use App\Services\QgisService;
use App\Traits\HasGeometryPoint;
use geoPHP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeometryLine extends Model
{
    use HasGeometryPoint, SoftDeletes;

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

    public function toArrayWGS84API()
    {

        $line = geoPHP::load($this->line);
        return QgisService::lineToArrayOfPointsWGS84NotClass($line);
    }

}
