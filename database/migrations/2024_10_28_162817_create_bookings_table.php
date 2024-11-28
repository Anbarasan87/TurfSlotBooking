<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->foreignId('user_id')->constrained('users'); // user_id bigint(20) UNSIGNED
            $table->foreignId('turf_id')->constrained('turfs'); // turf_id bigint(20) UNSIGNED
            $table->date('booking_date'); // booking_date date
            $table->string('time_slot'); // time_slot varchar(50)
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending'); // status enum
            $table->decimal('total_price', 10, 2); // total_price decimal(10,2)
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
