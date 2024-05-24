<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("drop view IF EXISTS scuole_lizmap");
        DB::statement("drop view IF EXISTS studenti_lizmap");
        DB::statement("drop view IF EXISTS viaggi_lizmap");
        DB::statement("drop view IF EXISTS anon_studenti_lizmap");
        DB::statement('ALTER TABLE public.buildings ALTER COLUMN
                  town_istat TYPE varchar(10) USING (town_istat)::varchar(10)');
        DB::statement('ALTER TABLE public.trips ALTER COLUMN
                  town_istat TYPE varchar(10) USING (town_istat)::varchar(10)');
        DB::statement('ALTER TABLE public.students ALTER COLUMN
                  town_istat TYPE varchar(10) USING (town_istat)::varchar(10)');

        DB::statement("create or replace view scuole_lizmap as
select
	cast(row_number() over(
order by
	sch.id)as int) as id,
	sch.uuid as scuola_id,
	gp.point as geom,
	concat(sch.name, ' (',b.name,')') as scuola_name, b.address as scuola_address
from
	public.schools sch
left join public.my_sections sec on
	sec.school_id = sch.id
join public.buildings b on
	b.school_id = sch.id
left join public.geometry_points gp on
	gp.georefable_id = b.id
	and gp.georefable_type = 'App\Models\Building'");


        DB::statement("create or replace
view studenti_lizmap as
select
	cast(row_number() over(
order by
	s.id)as int) as id,
	sch.uuid as school_id,
	public.geometry_points.point as geom,
	concat(s.name,
	' ',
	s.surname) as studente_nome,
	s.address as studente_address
from
	public.students s
join public.geometry_points
on
	public.geometry_points.georefable_id = s.id
	and public.geometry_points.georefable_type = 'App\Models\Student'
join public.my_sections sec
	on
	s.section_id = sec.id
join public.schools sch
	on
	sec.school_id = sch.id;");


        DB::statement("create or replace
view viaggi_lizmap as
select
	cast((row_number() over(
order by
	t.id)) as int) as id,
	sch.uuid as school_id,
	gl.line as geom,
	trans.name as mezzo,
	t.address as destinazione
from
	trips t
join geometry_lines gl on
	gl.lineable_id = t.id
	and gl.lineable_type = 'App\Models\Trip'
join transports trans on
	t.transport_1 = trans.id
	join students st on
	t.student_id = st.id
	join public.my_sections sec
	on st.section_id = sec.id
join public.schools sch
	on sec.school_id = sch.id;");
        DB::statement("create or replace
view anon_studenti_lizmap as
select
	cast(row_number() over(
order by
	s.id)as int) as id,
	sch.uuid as school_id,
	public.geometry_points.point as geom,
	s.address as studente_address
from
	public.students s
join public.geometry_points
on
	public.geometry_points.georefable_id = s.id
	and public.geometry_points.georefable_type = 'App\Models\Student'
join public.my_sections sec
	on
	s.section_id = sec.id
join public.schools sch
	on
	sec.school_id = sch.id;");

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view IF EXISTS scuole_lizmap");
        DB::statement("drop view IF EXISTS studenti_lizmap");
        DB::statement("drop view IF EXISTS viaggi_lizmap");
        DB::statement("drop view IF EXISTS anon_studenti_lizmap");
        DB::statement('ALTER TABLE public.buildings ALTER COLUMN
                  town_istat TYPE integer USING (town_istat)::integer');
        DB::statement('ALTER TABLE public.trips ALTER COLUMN
                  town_istat TYPE integer USING (town_istat)::integer');
        DB::statement('ALTER TABLE public.students ALTER COLUMN
                  town_istat TYPE integer USING (town_istat)::integer');
        DB::statement("create or replace view scuole_lizmap as
select
	cast(row_number() over(
order by
	sch.id)as int) as id,
	sch.uuid as scuola_id,
	gp.point as geom,
	concat(sch.name, ' (',b.name,')') as scuola_name, b.address as scuola_address
from
	public.schools sch
left join public.my_sections sec on
	sec.school_id = sch.id
join public.buildings b on
	b.school_id = sch.id
left join public.geometry_points gp on
	gp.georefable_id = b.id
	and gp.georefable_type = 'App\Models\Building'");


        DB::statement("create or replace
view studenti_lizmap as
select
	cast(row_number() over(
order by
	s.id)as int) as id,
	sch.uuid as school_id,
	public.geometry_points.point as geom,
	concat(s.name,
	' ',
	s.surname) as studente_nome,
	s.address as studente_address
from
	public.students s
join public.geometry_points
on
	public.geometry_points.georefable_id = s.id
	and public.geometry_points.georefable_type = 'App\Models\Student'
join public.my_sections sec
	on
	s.section_id = sec.id
join public.schools sch
	on
	sec.school_id = sch.id;");


        DB::statement("create or replace
view viaggi_lizmap as
select
	cast((row_number() over(
order by
	t.id)) as int) as id,
	sch.uuid as school_id,
	gl.line as geom,
	trans.name as mezzo,
	t.address as destinazione
from
	trips t
join geometry_lines gl on
	gl.lineable_id = t.id
	and gl.lineable_type = 'App\Models\Trip'
join transports trans on
	t.transport_1 = trans.id
	join students st on
	t.student_id = st.id
	join public.my_sections sec
	on st.section_id = sec.id
join public.schools sch
	on sec.school_id = sch.id;");
        DB::statement("create or replace
view anon_studenti_lizmap as
select
	cast(row_number() over(
order by
	s.id)as int) as id,
	sch.uuid as school_id,
	public.geometry_points.point as geom,
	s.address as studente_address
from
	public.students s
join public.geometry_points
on
	public.geometry_points.georefable_id = s.id
	and public.geometry_points.georefable_type = 'App\Models\Student'
join public.my_sections sec
	on
	s.section_id = sec.id
join public.schools sch
	on
	sec.school_id = sch.id;");
    }
};
