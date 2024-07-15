<?php

namespace App\Services;

use App\Models\Building;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;
use DB;
use geoPHP;
use Illuminate\Support\Facades\Http;
use Log;
use proj4php\Point as ProjPoint;
use proj4php\Proj;
use proj4php\Proj4php;

class QgisService
{
    public function __construct()
    {

    }

    public static function georefStudent(Student $student)
    {
        if ($student->town_istat) {
            [$point, $address_request] = self::getGeomPoint($student->address, $student->town_istat);

            self::updateOrCreateGeometryPoint($point, $student, $address_request);
        }
    }

    public static function georefBuilding(Building $building)
    {
        [$point, $address_request] = self::getGeomPoint($building->address, $building->town_istat);

        self::updateOrCreateGeometryPoint($point, $building, $address_request);
    }

    public static function georefTrip(Trip $trip)
    {
        if ($trip->student->town_istat) {
            [$point, $address_request] = self::getGeomPoint($trip->address, $trip->town_istat);

            self::updateOrCreateGeometryPoint($point, $trip, $address_request);
            if (!is_null($point)) {
                self::createLineOfTrip($trip);
            }
        }
    }

    public static function createLineOfTrip(Trip $trip)
    {
        try {
            if ($trip->order == 1) {
                $georefable_id1 = $trip->student->id;
                $georefable_type1 = Student::class;
                $georefable_id2 = $trip->id;
                $georefable_type2 = Trip::class;
            } else if ($trip->order == 2) {
                $georefable_id1 = $trip->previousTrip->id;
                $georefable_type1 = Trip::class;
                $georefable_id2 = $trip->id;
                $georefable_type2 = Trip::class;
            } else {
                $georefable_id1 = $trip->previousTrip->id;
                $georefable_type1 = Trip::class;
                $georefable_id2 = $trip->student->school->id;
                $georefable_type2 = School::class;
            }
            if ($trip->hasMezzoPrivato()) {

                $result = DB::select(DB::raw("SELECT
    ST_LineMerge(ST_Union(arr.array)) as line
FROM
    (
        SELECT
            ARRAY(
                SELECT
                    r.geom AS geom
                FROM
                    pgr_dijkstra(
                        'SELECT CAST(id AS BIGINT), source, target, length AS cost, length as reverse_cost  FROM basi_carto.routes',
                        (
                            SELECT
                                b.id AS gp_id
                            FROM
                                geometry_points AS a
                            CROSS JOIN LATERAL (
                                SELECT
                                    id, rv.the_geom
                                FROM
                                    basi_carto.routes_vertices rv
                                ORDER BY
                                    rv.the_geom <-> ST_Transform(a.point, 32632)
                                LIMIT 1
                            ) AS b
                            WHERE
                                a.georefable_type = ? AND a.georefable_id = ?
                        ),
                        (
                            SELECT
                                b.id AS gp_id
                            FROM
                                geometry_points AS a
                            CROSS JOIN LATERAL (
                                SELECT
                                    id, rv.the_geom
                                FROM
                                    basi_carto.routes_vertices rv
                                ORDER BY
                                    rv.the_geom <-> ST_Transform(a.point, 32632)
                                LIMIT 1
                            ) AS b
                            WHERE
                                a.georefable_type = ? AND a.georefable_id = ?
                        ),
                        FALSE
                    ) AS di
                JOIN basi_carto.routes r ON
                    r.id = di.edge
            )
        ) AS arr"), [$georefable_type1, $georefable_id1, $georefable_type2, $georefable_id2])[0];

            } else {
                $result = DB::select(DB::raw("select
	(
	select
		ST_LineMerge(ST_Union(dijkstra.array)) as line
	from
		(
		select
			array(
			select
				tt.geom as geom
			from
				pgr_dijkstra(
'SELECT CAST(id AS BIGINT), source, target, 1 AS cost, 1 as reverse_cost FROM basi_carto.tratti_tpl_32632 where shape_id = ' || shape_id,
				start_shape.source,
				end_shape.target,
				false) as di
			join basi_carto.tratti_tpl_32632 tt on
				tt.id = di.edge)
			)as dijkstra)
from
	(
	select
		a.id as start_id,
		st_transform(a.geom,
		32632) as start_geom,
		b.id as end_id,
		st_transform(b.geom,
		32632) as end_geom,
		(array
    (
		select
			unnest(a.route_ids)
	intersect
		select
			unnest(b.route_ids)
    ))[1] as route_name,
		shapes_tratti.shape_id as shape_id
	from
		(
		select
			row_number() over () as rnum,
			id,
			geom,
			route_ids
		from
			basi_carto.stops_tpl_14092023 st
		order by
			ST_Transform(st.geom,
			32632) <-> (
			select
				point
			from
				public.geometry_points gp
			where
				gp.georefable_type = ? AND gp.georefable_id = ?)
		limit 10) as a,
		(
		select
			row_number() over () as rnum,
			id,
			geom,
			route_ids
		from
			basi_carto.stops_tpl_14092023 st
		order by
			ST_Transform(st.geom,
			32632) <-> (
			select
				point
			from
				public.geometry_points gp
			where
				gp.georefable_type = ? AND gp.georefable_id = ?)
		limit 10) as b,
		basi_carto.shapes_tratti_32632 shapes_tratti
	where
		a.route_ids && b.route_ids
		and
	shapes_tratti.route_id = (array
    (
		select
			unnest(a.route_ids)
	intersect
		select
			unnest(b.route_ids)
    ))[1]
	order by
		(a.rnum + b.rnum,
		a.rnum,
		b.rnum)
	limit 1) as result
cross join lateral (
	select
		source
	from
		basi_carto.tratti_tpl_32632 tt
	where
		tt.shape_id = result.shape_id
	order by
		start_geom <-> tt.geom
	limit 1
) as start_shape
cross join lateral (
	select
		target
	from
		basi_carto.tratti_tpl_32632 tt
	where
		tt.shape_id = result.shape_id
	order by
		end_geom <-> tt.geom
	limit 1
) as end_shape;
"), [$georefable_type1, $georefable_id1, $georefable_type2, $georefable_id2])[0] ?? null;
            }


            if (!is_null($result) && !is_null($result->line))
                $trip->geometryLine()->updateOrCreate([
                    'lineable_id' => $trip->id,
                    'lineable_type' => Trip::class
                ], [
                    'line' => $result->line,
                    'is_public_transportation' => $trip->hasMezzoPubblico()
                ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public
    static function getGeomPoint($original_address, $town_istat)
    {
        set_time_limit(0);
        /* @var Point $geom */
        $limit = DB::selectOne('SELECT ST_Transform(lc.geom, 4326) as geom from basi_carto.limiti_comuni lc where lc.cod_istat = ?', [$town_istat]);
        if (!is_null($limit)) {
            $multipolygon = geoPHP::load($limit->geom);
            $polygon = $multipolygon->components[0];
        } else
            $polygon = false;

        $baseUrl = config('custom.nominatim.url');
        $WKBgenerator = new WKBGenerator();
        $address = $original_address . ', ' . getComune($town_istat);
        [$geom, $address_res] = self::performGet($baseUrl, $address);


        if (!is_null($geom)) {
            $point = geoPHP::load($WKBgenerator->generate($geom));
            if ($polygon && $polygon->pointInPolygon($point))
                return [$geom, $address_res];
        }
        $address = str_ireplace(['località', 'localita', 'piazza', 'strada', 'stradone', 'corso', 'vicolo', 'lungarno', 'viale',
            'rione', 'contrada', 'colletta', 'salita', 'traversa', 'rampa', 'bastioni', 'piazzetta', 'chiasso', 'frazione', 'quartiere',
            'rotonda', 'vico', 'stradone', 'loc', 'largo'], '', $address);

        [$geom, $address_res] = self::performGet($baseUrl, $address);

        if (!is_null($geom)) {
            $point = geoPHP::load($WKBgenerator->generate($geom));
            if ($polygon && $polygon->pointInPolygon($point))
                return [$geom, $address_res];
        }

        $address = sanitizeAddress($address);

        [$geom, $address_res] = self::performGet($baseUrl, $address);

        if (!is_null($geom)) {
            $point = geoPHP::load($WKBgenerator->generate($geom));
            if ($polygon && $polygon->pointInPolygon($point))
                return [$geom, $address_res];
        }
        if ($polygon) {
            $centroid = $polygon->getCentroid("32632");
//            dd($polygon, $centroid, $centroid->out("wkt"), Point::make($centroid->getY(), $centroid->getX()));
            return [Point::makeGeodetic($centroid->getY(), $centroid->getX()), $original_address];
        } else {
            return [null, null];
        }

    }

    /**
     * @param string $baseUrl
     * @param string $address
     * @return array
     */
    private
    static function performGet(string $baseUrl, string $address): array
    {
        $baseUrl = $baseUrl . '&q=' . $address . '&addressdetails=1&polygon_geojson=1&format=jsonv2';
        $res = Http::retry(3, 100)->get($baseUrl)->json();
        if (isset($res[0]['lon']) && isset($res[0]['lat'])) {
            $address = $res[0]['address']['road'] ?? '';
            $address .= ' ' . ($res[0]['address']['house_number'] ?? '');
            $address .= ' ,' . ($res[0]['address']['village'] ?? '');
            $lon = $res[0]['lon'];
            $lat = $res[0]['lat'];

            return [Point::makeGeodetic($lat, $lon), $address];
        }

        return [null, null];
    }

//    public static function saveClosestPoint($section)
//    {
//        $studentsStrada = $section->students_strada;
//
//        $students_id = $studentsStrada->pluck('id')->toArray();
//        $placeholders = implode(",", array_fill(0, count($students_id), '?'));
//
//        $query = DB::select(DB::raw('SELECT ST_CLOSESTPOINT(X.PT1,X.PT2) as CLOSEST_POINT, X.INDIRIZZO, X.ID
//                            FROM
//                            (SELECT PUBLIC.STUDENTS.geom_address AS PT1,
//                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR.THE_GEOM AS PT2,
//                            	 		PUBLIC.STUDENTS.ADDRESS AS INDIRIZZO,
//                            	 		PUBLIC.STUDENTS.ID AS ID
//                            		FROM PUBLIC.STUDENTS,
//                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR
//                             where students.id in (' . $placeholders . ') and geom_address is not null
//                            ) as X limit ' . count($students_id) . ';'), $students_id);
//
//
//        $studentsMezzi = $section->students_mezzi;
//        $students_id = $studentsMezzi->pluck('id')->toArray();
//        $placeholders = implode(",", array_fill(0, count($students_id), '?'));
//
//
//        $query = DB::select(DB::raw('SELECT ST_CLOSESTPOINT(X.PT1,X.PT2) as CLOSEST_POINT, X.INDIRIZZO, X.ID
//                            FROM
//                            (SELECT PUBLIC.STUDENTS.geom_address AS PT1,
//                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR.THE_GEOM AS PT2,
//                            	 		PUBLIC.STUDENTS.ADDRESS AS INDIRIZZO,
//                            	 		PUBLIC.STUDENTS.ID AS ID
//                            		FROM PUBLIC.STUDENTS,
//                            			BASI_CARTO.GRAFO_STRADALE_E_VERTICES_PGR
//                             where students.id in (' . $placeholders . ') and geom_address is not null
//                            ) as X limit ' . count($students_id) . ';'), $students_id);
//
//    }

    /**
     * @param Point|null $point
     * @param Building|Student|Trip $model
     * @param mixed $address_request
     * @return void
     */
    private
    static function updateOrCreateGeometryPoint(Point|null $point, Building|Student|Trip $model, string|null $address_request): void
    {
        if (is_null($point)) {
            if ($model->geometryPoint()->exists())
                $model->geometryPoint()->delete();
        } else {
            $model->geometryPoint()->updateOrCreate(
                [
                    'georefable_id' => $model->id,
                    'georefable_type' => $model::class
                ],
                [
                    'point' => $point,
                    'address_original' => $model->address,
                    'address_request' => $address_request,
                ]
            );
        }
    }

    public static function calculatePollutionAndCaloriesForSection(Section $section)
    {
        $trips = $section->trips;

        return self::calculatePollutionAndCaloriesForTripsSum($trips);
    }

    public static function calculatePollutionAndCaloriesForSchool(School $school)
    {
        $trips = $school->trips;

        return self::calculatePollutionAndCaloriesForTripsSum($trips);
    }

    public static function calculatePollutionAndCaloriesForAllSchools()
    {
        $schools = School::all();
        $trips = collect();
        foreach ($schools as $school) {
            $trips->push($school->trips);
        }
        $trips = $trips->flatten();

        return self::calculatePollutionAndCaloriesForTripsSum($trips);
    }


    /**
     * @param mixed $trips
     * @return float[]
     */
    public static function calculatePollutionAndCaloriesForTripsSum(mixed $trips): array
    {
        if (count($trips) > 0) {

            list($resPol, $resPiedi, $resBici) = self::calculateDistanceForTransport($trips);

            if ($resPol && isset($resPol[0])) {
                $distance = round($resPol[0]->round / 1000, 2);
            } else {
                $distance = 0;
            }

            if ($resPiedi && isset($resPiedi[0])) {
                $distancePiedi = $resPiedi[0]->round;
            } else {
                $distancePiedi = 0;
            }

            if ($resBici && isset($resBici[0])) {
                $distanceBici = $resBici[0]->round;
            } else {
                $distanceBici = 0;
            }
            $co2 = round($distance * 0.1630846 * 2 * 200, 2);

            return [
                'carburante' => round($distance * 0.000869 * 2 * 200, 2),
                'co2' => $co2,
                'co' => round($distance * 0.0007853 * 2 * 200, 2),
                'nox' => round($distance * 0.0004256 * 2 * 200, 2),
                'pm10' => round($distance * 0.0000297 * 2 * 200, 2),
                'trees' => round($co2 / 30),
                'kcal_piedi' => round($distancePiedi * 0.0225 * 2 * 200, 2),
                'kcal_bici' => round($distanceBici * 0.0075 * 2 * 200, 2),
            ];
        }
        return [
            'carburante' => 0,
            'co2' => 0,
            'co' => 0,
            'nox' => 0,
            'pm10' => 0,
            'kcal_piedi' => 0,
            'kcal_bici' => 0,
        ];
    }

    public static function calculatePollutionAndCaloriesForTrips(mixed $trips)
    {
        if (count($trips) > 0) {

            $tripsIds = $trips->pluck('id')->toArray();
            $placeholder = '(' . rtrim(str_repeat('?,', count($tripsIds)), ',') . ')';


            $res = collect(DB::select(DB::raw("select
	                                        	round(ST_Length(gl.line)::numeric,
	                                        	2) as distance,
	                                        	t.transport_1 as transport_id,
	                                        	trans.name as transport_name,
	                                        	t.id as trip_id,
	                                        	gl.id as line_id,
                                                st.id as student_id
	                                        from
	                                        	trips t
	                                        join geometry_lines gl on
	                                        	gl.lineable_type = 'App\Models\Trip'
	                                        	and gl.lineable_id = t.id
	                                        	join transports trans on
	                                        	t.transport_1 = trans.id
	                                        join students st on
	                                        	t.student_id = st.id
	                                        where
	                                        	t.id in $placeholder and gl.deleted_at is null"), array_merge($tripsIds)));

            foreach ($res as $trip) {
                if ($trip->transport_id == Transport::AUTO) {
                    $trip->carburante = round($trip->distance * 0.0000869 * 2 * 200, 2);
                    $trip->co2 = round($trip->distance * 0.1630846 * 2 * 200, 2);
                    $trip->co = round($trip->distance * 0.0007853 * 2 * 200, 2);
                    $trip->nox = round($trip->distance * 0.0004256 * 2 * 200, 2);
                    $trip->pm10 = round($trip->distance * 0.0000297 * 2 * 200, 2);
                    $trip->kcal = 0;
                } else if ($trip->transport_id == Transport::BICICLETTA || $trip->transport_id == Transport::PIEDI) {
                    $trip->carburante = 0;
                    $trip->co2 = 0;
                    $trip->co = 0;
                    $trip->nox = 0;
                    $trip->pm10 = 0;
                    $trip->kcal = $trip->transport_id == Transport::BICICLETTA ? round($trip->distance * 0.0075 * 2 * 200, 2) : round($trip->distance * 0.0225, 2);
                } else {
                    $trip->carburante = 0;
                    $trip->co2 = 0;
                    $trip->co = 0;
                    $trip->nox = 0;
                    $trip->pm10 = 0;
                    $trip->kcal = 0;
                }
            }
            return $res;
        }
        return null;
    }

    /**
     * @param mixed $trips
     * @return array
     */
    public static function calculateDistanceForTransport(mixed $trips): array
    {
        $tripsIds = $trips->pluck('id')->toArray();
        $placeholder = '(' . rtrim(str_repeat('?,', count($tripsIds)), ',') . ')';


        $resPol = DB::select(DB::raw("select round(sum(ST_Length(gl.line)::numeric),2)
                                        from trips t
                                        join geometry_lines gl on gl.lineable_type = 'App\Models\Trip' and gl.lineable_id = t.id
                                        where t.id in $placeholder and t.transport_1 = ?"), array_merge($tripsIds, [Transport::AUTO]));

        $resPiedi = DB::select(DB::raw("select round(sum(ST_Length(gl.line)::numeric),2)
                                        from trips t
                                        join geometry_lines gl on gl.lineable_type = 'App\Models\Trip' and gl.lineable_id = t.id
                                        where t.id in $placeholder and t.transport_1 = ?"), array_merge($tripsIds, [Transport::PIEDI]));
        $resBici = DB::select(DB::raw("select round(sum(ST_Length(gl.line)::numeric),2)
                                        from trips t
                                        join geometry_lines gl on gl.lineable_type = 'App\Models\Trip' and gl.lineable_id = t.id
                                        where t.id in $placeholder and t.transport_1 = ?"), array_merge($tripsIds, [Transport::BICICLETTA]));
        $resBus = DB::select(DB::raw("select round(sum(ST_Length(gl.line)::numeric),2)
                                        from trips t
                                        join geometry_lines gl on gl.lineable_type = 'App\Models\Trip' and gl.lineable_id = t.id
                                        where t.id in $placeholder and t.transport_1 = ?"), array_merge($tripsIds, [Transport::BUS_COMUNALE]));
        return array($resPol, $resPiedi, $resBici, $resBus);
    }

    public static function toWGS84(Point $point, bool $pointClass = true)
    {
        $proj4 = new Proj4php();

        $myProj = new Proj('EPSG:32632', $proj4);
        $projWGS84 = new Proj('EPSG:4326', $proj4);

        $pointSrc = new ProjPoint($point->getX(), $point->getY(), $myProj);
        $pointDest = $proj4->transform($projWGS84, $pointSrc);
        $pointDestArr = $pointDest->toArray();

        if ($pointClass)
            return Point::makeGeodetic($pointDestArr[1], $pointDestArr[0]);
        else
            return [$pointDestArr[1], $pointDestArr[0]];
    }

//    public static function fromWGS84To(int $srid, Point $point)
//    {
//        $proj4 = new Proj4php();
//
//        $myProj = new Proj('EPSG:4326', $proj4);
//        $projDest = new Proj('EPSG:' . $srid, $proj4);
//
//        $pointSrc = new ProjPoint($point->getX(), $point->getY(), projection: $myProj);
//        $pointDest = $proj4->transform($projDest, $pointSrc);
//        $pointDestArr = $pointDest->toArray();
//        return Point::make($pointDestArr[1], $pointDestArr[0], srid: $srid);
//    }

    public static function lineToArrayOfPointsWGS84(\LineString $linestring)
    {
        $points = $linestring->getPoints();
        $WGS84Points = array();
        foreach ($points as $point) {
            /* @var \Point $point */
            $WGS84Points[] = self::toWGS84(Point::makeGeodetic($point->getY(), $point->getX()));
        }
        return $WGS84Points;
    }
    public static function lineToArrayOfPointsWGS84NotClass(\LineString $linestring)
    {
        $points = $linestring->getPoints();
        $WGS84Points = array();
        foreach ($points as $point) {
            /* @var \Point $point */
            $WGS84Points[] = self::toWGS84(Point::makeGeodetic($point->getY(), $point->getX()), false);
        }
        return $WGS84Points;
    }

//    public static function fromArrayToArrayOfPoints(array $array, string $latKey = 'lat', string $lonKey = 'lng')
//    {
//        $points = array();
//
//        foreach ($array as $item) {
//            $points[] = self::fromWGS84To(32632,Point::make($item[$latKey], $item[$lonKey], srid: 4326));
//        }
//        return $points;
//    }

//    public static function transformWKT(int $fromSrid, int $toSrid, string $WKT)
//    {
//        $result = DB::select(DB::raw("select ST_asText(ST_Transform(ST_GeomFromText('$WKT',
//	                $fromSrid),
//	                $toSrid)) as geom;"));
//
//        $geoPHP = geoPHP::load($result[0]->geom);
//        $points = [];
//        foreach ($geoPHP->getComponents() as $point) {
//            $points[] = Point::make($point->getY(), $point->getX(), srid: $toSrid);
//        }
//        return $points;
//    }

    public static function transformWKT(int $fromSrid, int $toSrid, string $WKT)
    {
        $result = DB::select(DB::raw("select ST_asText(ST_Transform(ST_GeomFromText('$WKT',
	                $fromSrid),
	                $toSrid)) as geom;"));

        return $result[0]->geom;
    }


}
