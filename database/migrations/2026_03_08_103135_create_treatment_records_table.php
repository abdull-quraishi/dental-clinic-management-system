<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('treatment_records', function (Blueprint $table) {
            $table->bigIncrements('treatment_id');
            $table->unsignedBigInteger('patient_id')->index();
            $table->unsignedInteger('doctor_id')->index();
            $table->text('diagnosis');
            $table->text('treatment_plan')->nullable();
            $table->enum('treatment_status', ['Healed','In Treatment','Waiting'])->default('Waiting');
            $table->date('treatment_date')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreign('doctor_id')->references('doctor_id')->on('doctors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('treatment_records');
    }
}
