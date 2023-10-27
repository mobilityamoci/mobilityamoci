<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Section;
use App\Models\Student;
use Clickbar\Magellan\Data\Geometries\Point;
use DB;
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

    public static function test()
    {
        $section = Section::find(1);
        $students = $section->students;

        $students_id = $students->pluck('id')->toArray();
        $placeholders = implode(",", array_fill(0, count($students_id), '?'));

        $query = DB::select(DB::raw('SELECT ST_CLOSESTPOINT(X.PT1,X.PT2) as CLOSEST_POINT, X.INDIRIZZO, X.ID
                            FROM
                            (SELECT PUBLIC.STUDENTS.geom_address AS PT1,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR.THE_GEOM AS PT2,
                            	 		PUBLIC.STUDENTS.ADDRESS AS INDIRIZZO,
                            	 		PUBLIC.STUDENTS.ID AS ID
                            		FROM PUBLIC.STUDENTS,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR
                             where students.id in ('.$placeholders.') and geom_address is not null
                            ) as X limit 3;'), $students_id);
dd($query);


    }

}
