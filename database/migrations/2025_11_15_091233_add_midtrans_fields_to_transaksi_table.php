<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambah kolom baru setelah no_transaksi
            $table->string('order_id')->unique()->after('no_transaksi');
            $table->string('snap_token')->nullable()->after('order_id');

            // Tambah kolom jumlah jika belum ada
            if (!Schema::hasColumn('transaksi', 'jumlah')) {
                $table->integer('jumlah')->default(1)->after('harga');
            }

            // Tambah status dan payment info
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])
                ->default('pending')->after('jumlah');
            $table->string('payment_type')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'snap_token',
                'jumlah',
                'status',
                'payment_type',
                'paid_at'
            ]);
        });
    }
};