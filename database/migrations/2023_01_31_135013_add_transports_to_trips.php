<?php

use App\Models\Transport;
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
        Schema::table('trips', function (Blueprint $table) {
            $table->foreignIdFor(Transport::class, 'transport_1')->nullable()->after('order');
            $table->foreignIdFor(Transport::class, 'transport_2')->nullable()->after('transport_1');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeignIdFor(Transport::class, 'transport_1');
            $table->dropForeignIdFor(Transport::class, 'transport_2');
        });
    }
};
