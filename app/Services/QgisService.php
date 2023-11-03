<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Section;
use App\Models\Student;
use Clickbar\Magellan\Data\Geometries\Point;
use DB;
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
        //provare con la base de
        set_time_limit(0);
        sleep(1);
        $baseUrl = 'https://nominatim.openstreetmap.org/search.php?';

        $address = $address . ', ' . getComune($town_istat);
        $geom = self::performGet($baseUrl, $address);

        if (!is_null($geom)) {
            return $geom;
        }

        $address = $address . ', ' . getComune($town_istat);
        $address = str_ireplace(['località', 'localita', 'piazza', 'strada', 'stradone', 'corso', 'vicolo', 'lungarno', 'viale',
            'rione', 'contrada', 'colletta', 'salita', 'traversa', 'rampa', 'bastioni', 'piazzetta', 'chiasso', 'frazione', 'quartiere',
            'rotonda', 'vico', 'stradone', 'loc',], '', $address);

        $geom = self::performGet($baseUrl, $address);

        if (!is_null($geom)) {
            return $geom;
        }

        $address = preg_replace('/\w*\.\w*/i', '', $address); //rimuovere parole con punti (iniziali di vie abbreviate, abbreviazioni varie (es. p.no per piacentino)
        $address = preg_replace('/w{1,3}$/i', '', $address); //rimuove lettere singole o 2,3 lettere

        $geom = self::performGet($baseUrl, $address);

        if (!is_null($geom)) {
            return $geom;
        }

        return null;
    }

    /**
     * @param string $baseUrl
     * @param string $address
     * @return Point|null
     */
    private static function performGet(string $baseUrl, string $address): ?Point
    {
        $baseUrl = $baseUrl . 'q=' . $address . '&polygon_geojson=1&format=jsonv2';
        $res = Http::retry(3, 100)->get($baseUrl)->json();
        if (isset($res[0]['lon']) && isset($res[0]['lat'])) {
            $lon = $res[0]['lon'];
            $lat = $res[0]['lat'];
            return Point::makeGeodetic($lat, $lon);
        }

        return null;
    }

    public static function test()
    {
        $section = Section::find(1);
        $studentsStrada = $section->students_strada;

        $students_id = $studentsStrada->pluck('id')->toArray();
        $placeholders = implode(",", array_fill(0, count($students_id), '?'));

        $query = DB::select(DB::raw('SELECT ST_CLOSESTPOINT(X.PT1,X.PT2) as CLOSEST_POINT, X.INDIRIZZO, X.ID
                            FROM
                            (SELECT PUBLIC.STUDENTS.geom_address AS PT1,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR.THE_GEOM AS PT2,
                            	 		PUBLIC.STUDENTS.ADDRESS AS INDIRIZZO,
                            	 		PUBLIC.STUDENTS.ID AS ID
                            		FROM PUBLIC.STUDENTS,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR
                             where students.id in (' . $placeholders . ') and geom_address is not null
                            ) as X limit 3;'), $students_id);


        $studentsMezzi = $section->students_mezzi();

        $query = DB::select(DB::raw('SELECT ST_CLOSESTPOINT(X.PT1,X.PT2) as CLOSEST_POINT, X.INDIRIZZO, X.ID
                            FROM
                            (SELECT PUBLIC.STUDENTS.geom_address AS PT1,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR.THE_GEOM AS PT2,
                            	 		PUBLIC.STUDENTS.ADDRESS AS INDIRIZZO,
                            	 		PUBLIC.STUDENTS.ID AS ID
                            		FROM PUBLIC.STUDENTS,
                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR
                             where students.id in (' . $placeholders . ') and geom_address is not null
                            ) as X limit 3;'), $students_id);

    }



}
