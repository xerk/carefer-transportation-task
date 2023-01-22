<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_seat', function (Blueprint $table) {
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('bus_id');
            $table->boolean('active')->default(1);
            $table->timestamp('available_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_seat');
    }
};
