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
        Schema::table('geometry_lines', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('geometry_points', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('geometry_lines', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('geometry_points', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
