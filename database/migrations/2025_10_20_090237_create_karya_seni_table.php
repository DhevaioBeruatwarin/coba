<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karya_seni', function (Blueprint $table) {
            $table->string('kode_seni', 20)->primary();
            $table->string('nama_karya', 100);
            $table->decimal('harga', 15, 2);
            $table->unsignedBigInteger('id_seniman');
            $table->timestamps();

            // foreign key ke tabel seniman
            $table->foreign('id_seniman')->references('id_seniman')->on('seniman')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_seni');
    }
};
