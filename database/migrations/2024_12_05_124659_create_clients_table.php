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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identity')->unique();
            $table->string('city');
            $table->string('nation');
            $table->string('religion');
            $table->string('phone')->nullable();
            $table->string('death_date');
            $table->string('burial_city');
            $table->string('burial_type');
            $table->string('cemetery');
            $table->foreignId('grave_id')->constrained('graves')->cascadeOnDelete();
            $table->foreignId('representative_id')->nullable()->constrained('representatives')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
