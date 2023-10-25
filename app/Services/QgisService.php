<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Student;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QgisService
{
    public function __construct()
    {

    }
    public static function georefStudent(Student $student): Student
    {
        $student->geom_address = self::getGeomPoint($student->address, $student->town_istat);
        return $student;
    }

    public static function georefBuilding(Building $building): Building
    {
        $building->geom_address = self::getGeomPoint($building->address, $building->town_istat);
        return $building;
    }

    public static function getGeomPoint($address, $town_istat)
    {
        $baseUrl = 'https://nominatim.openstreetmap.org/search.php?';
        $address = $address . ', ' . self::getComune($town_istat);
        $baseUrl = $baseUrl . 'q=' . $address . '&polygon_geojson=1&format=jsonv2';

        $res = Http::get($baseUrl)->json();
        $lon = $res[0]['lon'];
        $lat = $res[0]['lat'];

        return Point::makeGeodetic($lat, $lon);
    }

    public static function getComune($town_istat)
    {
        return Cache::get('comuni')[$town_istat]['comune'];
    }

}
