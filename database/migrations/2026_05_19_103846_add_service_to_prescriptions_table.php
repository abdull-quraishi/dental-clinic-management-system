<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {

            $table->unsignedBigInteger('service_id')->nullable()->after('doctor_id');

            $table->decimal('appointment_fee', 10, 2)->nullable()->default(0);

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {

            $table->dropForeign(['service_id']);

            $table->dropColumn([
                'service_id',
                'appointment_fee'
            ]);
        });
    }
};
