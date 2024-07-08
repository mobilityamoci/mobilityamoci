<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
	s.address as studente_address,
	s.deleted_at as deleted_at
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
	t.address as destinazione,
	gl.deleted_at as deleted_at
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
	s.address as studente_address,
	s.deleted_at as deleted_at
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
        Schema::table('views', function (Blueprint $table) {
            //
        });
    }
};
