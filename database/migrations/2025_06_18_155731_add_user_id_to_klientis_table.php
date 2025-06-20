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
    // kjo tash është bosh se user_id veç ekziston
}

public function down()
{
    Schema::table('klientis', function (Blueprint $table) {
        $table->dropColumn('user_id');
    });
}
};
