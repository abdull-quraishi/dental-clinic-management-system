<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'referred_by_doctor_id')) {
                $table->unsignedInteger('referred_by_doctor_id')->nullable()->after('doctor_id');
            }

            if (!Schema::hasColumn('appointments', 'referred_to_doctor_id')) {
                $table->unsignedInteger('referred_to_doctor_id')->nullable()->after('referred_by_doctor_id');
            }

            if (!Schema::hasColumn('appointments', 'appointment_message')) {
                $table->text('appointment_message')->nullable()->after('notes');
            }

            $table->foreign('referred_by_doctor_id')
                ->references('doctor_id')->on('doctors')
                ->onDelete('set null');

            $table->foreign('referred_to_doctor_id')
                ->references('doctor_id')->on('doctors')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['referred_by_doctor_id']);
            $table->dropForeign(['referred_to_doctor_id']);

            $table->dropColumn([
                'referred_by_doctor_id',
                'referred_to_doctor_id',
                'appointment_message',
            ]);
        });
    }
};
