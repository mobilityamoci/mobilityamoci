<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Section;
use App\Models\Student;
use App\Models\Trip;
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
    public static function georefTrip(Trip $trip)
    {
        [$point, $address_request] = self::getGeomPoint($trip->address, $trip->town_istat);

        self::updateOrCreateGeometryPoint($point, $trip, $address_request);
    }

    public static function getGeomPoint($original_address, $town_istat)
    {
        set_time_limit(0);
        $baseUrl = 'https://nominatim.openstreetmap.org/search.php?limit=1';

        $address = $original_address . ', ' . getComune($town_istat);
        [$geom, $address_res] = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address_res];
        }

        $address = str_ireplace(['località', 'localita', 'piazza', 'strada', 'stradone', 'corso', 'vicolo', 'lungarno', 'viale',
            'rione', 'contrada', 'colletta', 'salita', 'traversa', 'rampa', 'bastioni', 'piazzetta', 'chiasso', 'frazione', 'quartiere',
            'rotonda', 'vico', 'stradone', 'loc','largo'], '', $address);

        [$geom, $address_res] = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address_res];
        }

        $address = sanitizeAddress($address);

        [$geom, $address_res] = self::performGet($baseUrl, $address);
        sleep(1);

        if (!is_null($geom)) {
            return [$geom, $address_res];
        }

        return [null, null];
    }

    /**
     * @param string $baseUrl
     * @param string $address
     * @return array
     */
    private static function performGet(string $baseUrl, string $address): array
    {
        $baseUrl = $baseUrl . '&q=' . $address . '&addressdetails=1&polygon_geojson=1&format=jsonv2';
        $res = Http::retry(3, 100)->get($baseUrl)->json();
        if (isset($res[0]['lon']) && isset($res[0]['lat'])) {
            $address = $res[0]['address']['road'] ?? '';
            $address .= ' ' . $res[0]['address']['house_number'] ?? '';
            $address .= ' ,' . $res[0]['address']['county'] ?? '';
            $lon = $res[0]['lon'];
            $lat = $res[0]['lat'];
            return [Point::makeGeodetic($lat, $lon), $address];
        }

        return [null,null];
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
     * @param Building|Student|Trip $model
     * @param mixed $address_request
     * @return void
     */
    private static function updateOrCreateGeometryPoint(Point|null $point, Building|Student|Trip $model, string|null $address_request): void
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
