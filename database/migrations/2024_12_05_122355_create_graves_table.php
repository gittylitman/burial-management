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
        Schema::create('graves', function (Blueprint $table) {
            $table->id();
            $table->string('cemetery');
            $table->string('burial_type');
            $table->string('plot');
            $table->string('block');
            $table->string('chevra_kadisha');
            $table->string('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graves');
    }
};
