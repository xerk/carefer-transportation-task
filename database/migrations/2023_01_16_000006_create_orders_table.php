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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['open','canceled','expired', 'closed', 'processing', 'on-hold'])->nullable();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->enum('payment_type', ['cash', 'card'])->nullable();
            $table->enum('payment_status', ['paid', 'refunded', 'field'])->nullable();
            $table->string('tax')->nullable();
            $table->string('subtotal_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamp('expire_at')->nullable();

            $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
};
