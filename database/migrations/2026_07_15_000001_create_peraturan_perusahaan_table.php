<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peraturan_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id');
            $table->enum('jenis', ['PP', 'PKB']);
            $table->string('nomor', 255);
            $table->date('tanggal');
            $table->string('file', 255)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('perusahaan_id');
            $table->index(['perusahaan_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peraturan_perusahaan');
    }
};
