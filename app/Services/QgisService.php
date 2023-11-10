<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Section;
use App\Models\Student;
use Clickbar\Magellan\Data\Geometries\Point;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class QgisService
{
    public function __construct()
    {

    }

    public static function georefStudent(Student $student)
    {
        [$point, $address_request] = self::getGeomPoint($student->address, $student->town_istat);

        self::updateOrCreateGeometryPoint($point, $student, $address_request);

    }

    public static function georefBuilding(Building $building)
    {
        [$point, $address_request] = self::getGeomPoint($building->address, $building->town_istat);

        self::updateOrCreateGeometryPoint($point, $building, $address_request);


    }

    public static function getGeomPoint($original_address, $town_istat)
    {
        set_time_limit(0);
        $baseUrl = 'https://nominatim.openstreetmap.org/search.php?limit=1';

        $address = $original_address . ', ' . getComune($town_istat);
        $geom = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address];
        }

        $address = str_ireplace(['località', 'localita', 'piazza', 'strada', 'stradone', 'corso', 'vicolo', 'lungarno', 'viale',
            'rione', 'contrada', 'colletta', 'salita', 'traversa', 'rampa', 'bastioni', 'piazzetta', 'chiasso', 'frazione', 'quartiere',
            'rotonda', 'vico', 'stradone', 'loc','largo'], '', $address);

        $geom = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address];
        }

        $address = sanitizeAddress($address);

        $geom = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address];
        }

        return [null, null];
    }

    /**
     * @param string $baseUrl
     * @param string $address
     * @return Point|null
     */
    private static function performGet(string $baseUrl, string $address): ?Point
    {
        $baseUrl = $baseUrl . '&q=' . $address . '&polygon_geojson=1&format=jsonv2';
        $res = Http::retry(3, 100)->get($baseUrl)->json();
        if (isset($res[0]['lon']) && isset($res[0]['lat'])) {
            $lon = $res[0]['lon'];
            $lat = $res[0]['lat'];
            return Point::makeGeodetic($lat, $lon);
        }

        return null;
    }

    public static function saveClosestPoint($section)
    {
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
                            ) as X limit '.count($students_id).';'), $students_id);


        $studentsMezzi = $section->students_mezzi;
        $students_id = $studentsMezzi->pluck('id')->toArray();
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
                            ) as X limit '.count($students_id).';'), $students_id);

    }

    /**
     * @param Point|null $point
     * @param Building|Student $model
     * @param mixed $address_request
     * @return void
     */
    private static function updateOrCreateGeometryPoint(Point|null $point, Building|Student $model, string|null $address_request): void
    {
        if (is_null($point)) {
            if ($model->geometryPoint()->exists())
                $model->geometryPoint()->delete();
        } else {
            $model->geometryPoint()->updateOrCreate(
                [
                    'georefable_id' => $model->id,
                    'georefable_type' => Building::class
                ],
                [
                    'point' => $point,
                    'address_original' => $model->address,
                    'address_request' => $address_request,
                ]
            );
        }
    }


}
