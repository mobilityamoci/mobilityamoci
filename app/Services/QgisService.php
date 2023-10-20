<?php

namespace App\Services;

use App\Models\Student;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QgisService
{
    public function __construct()
    {

    }

    public static function georefStudent(Student $student)
    {
        $baseUrl = 'https://nominatim.openstreetmap.org/search.php?';
        $address = $student->address . ', ' . Cache::get('comuni')[$student->town_istat]['comune'];
        $baseUrl = $baseUrl . 'q=' . $address . '&polygon_geojson=1&format=jsonv2';

        $res = Http::get($baseUrl)->json();
        $lon = $res[0]['lon'];
        $lat = $res[0]['lat'];

        $student->geom_address = Point::makeGeodetic($lat, $lon);
        return $student;
    }
}
