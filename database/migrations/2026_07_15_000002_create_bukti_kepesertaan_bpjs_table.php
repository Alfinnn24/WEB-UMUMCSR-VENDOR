<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bukti_kepesertaan_bpjs', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id');
            $table->enum('kategori', ['kesehatan', 'ketenagakerjaan']);
            $table->string('file', 255)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('perusahaan_id');
            $table->index(['perusahaan_id', 'kategori']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bukti_kepesertaan_bpjs');
    }
};
