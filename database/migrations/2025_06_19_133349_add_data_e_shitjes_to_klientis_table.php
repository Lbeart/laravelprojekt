<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('klientis', function (Blueprint $table) {
        $table->date('data_e_shitjes')->nullable();
    });
}

public function down()
{
    Schema::table('klientis', function (Blueprint $table) {
        $table->dropColumn('data_e_shitjes');
    });
}
};
