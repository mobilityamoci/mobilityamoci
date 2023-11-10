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
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('address_request')->nullable()->after('address');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->string('address_request')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('address_request');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('address_request');
        });
    }
};
