<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPrescriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            // که لا دمخه ستونزه وي، enum/string کارولای شي. دلته simple enum:
            $table->enum('status', ['pending', 'dispensed'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
