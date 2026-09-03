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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            // Identitas pelanggan (opsional, untuk keperluan tindak lanjut internal)
            $table->string('nama', 100)->nullable();
            $table->string('no_hp', 20)->nullable();

            // A. Teknisi
            $table->enum('teknisi_jadwal', ['ya', 'tidak']);
            $table->enum('teknisi_kualitas_instalasi', ['baik', 'cukup', 'kurang_baik']);
            $table->enum('teknisi_penampilan', ['ya', 'tidak']);
            $table->enum('teknisi_panduan', ['ya', 'tidak']);
            $table->enum('teknisi_sikap', ['sangat_baik', 'baik', 'cukup', 'kurang_baik']);

            // B. Sales
            $table->enum('sales_penjelasan', ['jelas', 'cukup_jelas', 'tidak_jelas']);
            $table->enum('sales_bantuan', ['sangat_membantu', 'cukup_membantu', 'tidak_membantu']);
            $table->enum('sales_respons', ['sangat_responsif', 'cukup_responsif', 'lambat']);
            $table->enum('sales_sikap', ['sangat_baik', 'baik', 'cukup', 'kurang_baik']);

            // C. Kepuasan keseluruhan (1-5)
            $table->unsignedTinyInteger('kepuasan_keseluruhan');

            // D. Saran / masukan bebas
            $table->text('saran')->nullable();

            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
