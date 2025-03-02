<?php

use App\Models\User;
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
        Schema::create('associables', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained();
            $table->morphs('associable');
            $table->timestamps();

            $table->unique(['user_id', 'associable_id', 'associable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('associables');
    }
};
