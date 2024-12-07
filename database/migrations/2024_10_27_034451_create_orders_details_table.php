<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_pelanggan')->references('id_pengguna')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('kuantitas');
            $table->integer('harga_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_details');
    }
};
