<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->string('name'); // name varchar(255)
            $table->string('email')->unique(); // email varchar(255) UNIQUE INDEX
            $table->string('password'); // password varchar(255)
            $table->enum('role', ['admin', 'user', 'owner']); // role enum
            $table->string('location')->nullable(); // location varchar(255) NULL
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
