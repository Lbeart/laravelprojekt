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
        Schema::create('klientis', function (Blueprint $table) {
            $table->id();
            $table->string('emri');
            $table->string('mbiemri');
            $table->integer('telefoni');
            $table->string('produkti');
            $table->integer('sasia');
            $table->decimal('qmimi');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('klientis');
    }
};
