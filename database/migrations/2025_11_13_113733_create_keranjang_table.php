<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->unsignedBigInteger('id_pembeli');
            $table->string('kode_seni');
            $table->integer('jumlah')->default(1);
            $table->timestamps();

            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembeli')->onDelete('cascade');
            $table->foreign('kode_seni')->references('kode_seni')->on('karya_seni')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
