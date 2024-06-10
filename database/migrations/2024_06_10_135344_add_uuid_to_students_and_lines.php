<?php

use App\Models\PedibusLine;
use App\Models\PedibusStop;
use App\Models\Student;
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
        Schema::table('students', function (Blueprint $table) {
            $table->uuid()->nullable();
        });

        Student::all()->each(function ($student) {
            $student->uuid = Str::uuid();
            $student->save();
        });

        Schema::table('pedibus_lines', function (Blueprint $table) {
            $table->uuid()->nullable();
        });

        PedibusLine::all()->each(function ($line) {
            $line->uuid = Str::uuid();
            $line->save();
        });

        Schema::table('pedibus_stops', function (Blueprint $table) {
            $table->uuid()->nullable();
        });

        PedibusStop::all()->each(function ($stop) {
            $stop->uuid = Str::uuid();
            $stop->save();
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
            $table->dropColumn('uuid');
        });
        Schema::table('pedibus_lines', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('pedibus_stops', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
