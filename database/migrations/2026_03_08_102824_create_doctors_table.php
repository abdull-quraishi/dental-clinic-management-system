<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('doctor_id'); // که غواړې 'id' وکاروه: $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('role')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            // FK to users.id (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
