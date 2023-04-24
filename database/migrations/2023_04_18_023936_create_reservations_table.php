<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('bus_id');
            $table->foreign('bus_id')->references('id')->on('buses');

            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes');

            $table->integer('seats_count');
            $table->decimal('seats_price');

            $table->decimal('total')->default(0);
            $table->decimal('discount')->default(0);

            $table->string('email');
            $table->enum('status', ['1', '2', '3'])->default('1') ;

            $table->date('reservation_date');
            $table->dateTime('cancelled_date')->nullable();

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
        Schema::dropIfExists('reservations');
    }
}
