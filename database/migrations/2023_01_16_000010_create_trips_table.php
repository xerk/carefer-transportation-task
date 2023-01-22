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
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('frequent')->nullable();
            $table->unsignedBigInteger('pickup_id');
            $table->unsignedBigInteger('destination_id');
            $table->unsignedBigInteger('bus_id');
            $table->enum('type', ['short', 'long']);
            $table->decimal('price');
            $table->decimal('distance');
            $table->boolean('active')->default(1);
            $table->time('start_at')->nullable();
            $table->time('end_at')->nullable();
            $table->string('cron_experations')->nullable();

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
        Schema::dropIfExists('trips');
    }
};
