<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurfsTable extends Migration
{
    public function up()
    {
        Schema::create('turfs', function (Blueprint $table) {
            $table->id(); // id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->string('owner_name'); // owner_name varchar(255)
            $table->foreignId('owner_id')->constrained('users'); // owner_id bigint(20) UNSIGNED
            $table->string('name'); // name varchar(255)
            $table->string('location'); // location varchar(255)
            $table->string('image')->default('default.jpg'); // image varchar(255) DEFAULT 'default.jpg'
            $table->string('sport_type', 50); // sport_type varchar(50)
            $table->decimal('price_per_hour', 10, 2); // price_per_hour decimal(10,2)
            $table->enum('availability', ['Available', 'Unavailable']); // availability enum
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('turfs');
    }
}
