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
        Schema::table('trips', function (Blueprint $table) {
            $table
                ->foreign('pickup_id')
                ->references('id')
                ->on('stations')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('destination_id')
                ->references('id')
                ->on('stations')
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
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['pickup_id']);
            $table->dropForeign(['destination_id']);
            $table->dropForeign(['bus_id']);
        });
    }
};
