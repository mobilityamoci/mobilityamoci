<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
join public.sections sec
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
        DB::statement("DROP VIEW IF EXISTS anon_studenti_lizmap");
    }
};
