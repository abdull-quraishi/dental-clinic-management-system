<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('billings', function (Blueprint $table) {
            if (! Schema::hasColumn('billings', 'prescription_id')) {
                $table->unsignedBigInteger('prescription_id')->nullable()->after('doctor_id')->index();
                // foreign key (match your PK type: here prescription_id is unsigned integer)
                $table->foreign('prescription_id')
                      ->references('prescription_id')->on('prescriptions')
                      ->onDelete('set null');
            }
        });
    }
    public function down() {
        Schema::table('billings', function (Blueprint $table) {
            if (Schema::hasColumn('billings', 'prescription_id')) {
                $table->dropForeign(['prescription_id']);
                $table->dropColumn('prescription_id');
            }
        });
    }
};