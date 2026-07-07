<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
  {
    Schema::create('appointments', function (Blueprint $table) {

        $table->increments('appointment_id');

        $table->unsignedBigInteger('patient_id');
        $table->unsignedInteger('doctor_id')->nullable();
        $table->dateTime('appointment_date');

        $table->string('service_type')->nullable(); 

        $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])
              ->default('Pending');

        $table->enum('priority', ['Critical', 'Medium', 'Low'])->nullable();
        $table->unsignedInteger('referred_to')->nullable();
        $table->string('image')->nullable();
        $table->text('notes')->nullable();

        $table->timestamps();

        $table->foreign('patient_id')
              ->references('patient_id')->on('patients')
              ->onDelete('cascade');

        $table->foreign('doctor_id')
              ->references('doctor_id')->on('doctors')
              ->onDelete('set null');
    });
  }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}