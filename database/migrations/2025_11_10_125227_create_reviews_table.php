<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->string('kode_seni', 20);
            $table->unsignedBigInteger('id_user')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('kode_seni')
                ->references('kode_seni')
                ->on('karya_seni')
                ->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
