<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {

            $table->decimal('appointment_fee', 10, 2)->nullable()->default(0);

            $table->decimal('service_total', 10, 2)->default(0);

            $table->decimal('medicine_total', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {

            $table->dropColumn([
                'appointment_fee',
                'service_total',
                'medicine_total',
                'total_amount'
            ]);
        });
    }
};
