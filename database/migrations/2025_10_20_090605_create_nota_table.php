<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nota', function (Blueprint $table) {
            $table->id('id_nota'); // primary key
            $table->unsignedBigInteger('no_transaksi'); // FK ke transaksi
            $table->unsignedBigInteger('id_pembeli');   // FK ke pembeli
            $table->unsignedBigInteger('id_seniman');   // FK ke seniman
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();

            // foreign keys
            $table->foreign('no_transaksi')->references('no_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembeli')->onDelete('cascade');
            $table->foreign('id_seniman')->references('id_seniman')->on('seniman')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};
