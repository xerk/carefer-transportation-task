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
        Schema::table('bus_seat', function (Blueprint $table) {
            $table
                ->foreign('seat_id')
                ->references('id')
                ->on('seats')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('bus_id')
                ->references('id')
                ->on('buses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_seat', function (Blueprint $table) {
            $table->dropForeign(['seat_id']);
            $table->dropForeign(['bus_id']);
        });
    }
};
