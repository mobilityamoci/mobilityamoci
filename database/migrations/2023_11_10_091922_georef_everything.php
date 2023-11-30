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
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('address_request');
            $table->dropColumn('geom_address');
        });
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('address_request');
            $table->dropColumn('geom_address');
        });

        Schema::create('geometry_points', function (Blueprint $table) {
            $table->id();
            $table->morphs('georefable');
            $table->magellanGeometry('point',32632);
            $table->string('address_original')->nullable();
            $table->string('address_request')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('address_request')->nullable()->after('address');
            $table->magellanGeometry('geom_address')->nullable();
        });
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('address_request')->nullable()->after('address');
            $table->magellanGeometry('geom_address')->nullable();
        });
        Schema::dropIfExists('geometry_points');
    }
};
