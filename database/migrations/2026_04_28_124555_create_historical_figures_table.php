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
        Schema::create('historical_figures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('birth_year')->nullable();
            $table->integer('death_year')->nullable();
            $table->foreignId('artifact_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_figures');
    }
};
