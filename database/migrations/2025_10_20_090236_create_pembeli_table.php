<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the <migrations class=""></migrations>
     */
    public function up(): void
    {
        Schema::create('pembeli', function (Blueprint $table) {
            $table->id('id_pembeli'); // primary key
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->rememberToken(); // kolom untuk Laravel Auth "remember me"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembeli');
    }
};
