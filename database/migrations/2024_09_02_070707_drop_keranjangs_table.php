<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropKeranjangsTable extends Migration
{
    /**
     * Hapus tabel 'keranjangs'.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('keranjangs');
    }

    /**
     * Kembalikan perubahan jika perlu.
     *
     * @return void
     */
    public function down()
    {
        // Menambahkan kembali tabel 'keranjangs' jika rollback dilakukan
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
            $table->timestamps();
        });
    }
};
