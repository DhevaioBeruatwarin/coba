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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('no_transaksi'); // primary key
            $table->string('order_id')->unique(); // untuk Midtrans
            $table->string('snap_token')->nullable(); // token dari Midtrans
            $table->date('tanggal_jual');
            $table->string('kode_seni', 20);
            $table->decimal('harga', 15, 2);
            $table->integer('jumlah')->default(1); // jumlah item dibeli
            $table->unsignedBigInteger('id_pembeli');
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('payment_type')->nullable(); // jenis pembayaran (VA, gopay, dll)
            $table->timestamp('paid_at')->nullable(); // kapan dibayar
            $table->timestamps();

            // foreign keys
            $table->foreign('kode_seni')->references('kode_seni')->on('karya_seni')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembeli')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};