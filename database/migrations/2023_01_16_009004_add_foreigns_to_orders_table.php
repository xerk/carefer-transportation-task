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
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->foreign('trip_id')
                ->references('id')
                ->on('trips')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('discount_id')
                ->references('id')
                ->on('discounts')
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['trip_id']);
            $table->dropForeign(['discount_id']);
        });
    }
};
