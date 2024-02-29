<?php

namespace App\Services;

use App\Models\Building;
use App\Models\School;
use App\Models\Student;
use App\Models\Trip;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;
use DB;
use geoPHP;
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
        if (!is_null($point)) {
            self::createLineOfTrip($trip);
        }
    }

    public static function createLineOfTrip(Trip $trip)
    {
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
                        'SELECT CAST(id AS BIGINT), source, target, length AS cost FROM basi_carto.routes',
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
'SELECT CAST(id AS BIGINT), source, target, 1 AS cost FROM basi_carto.tratti_tpl_32632 where shape_id = ' || shape_id,
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
		limit 5) as a,
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
		limit 5) as b,
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
"), [$georefable_type1, $georefable_id1, $georefable_type2, $georefable_id2])[0];
        }

        $test = $trip->geometryLine()->updateOrCreate([
            'lineable_id' => $trip->id,
            'lineable_type' => Trip::class
        ], [
            'line' => $result->line,
            'is_public_transportation' => $trip->hasMezzoPubblico()
        ]);
    }

    public
    static function getGeomPoint($original_address, $town_istat)
    {
        set_time_limit(0);
        /* @var Point $geom */
        $limit = DB::connection('basi_carto')->selectOne('SELECT geom from limiti_pc where cod_istat = ?', [$town_istat]);
        if (!is_null($limit)) {
            $multipolygon = geoPHP::load($limit->geom);
            $polygon = $multipolygon->components[0];
            $polygon->setSRID("32632");
        } else
            $polygon = false;

        $baseUrl = 'http://10.0.16.23:8080/search.php?limit=1';
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
            $address .= ' ,' . ($res[0]['address']['county'] ?? '');
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


}
