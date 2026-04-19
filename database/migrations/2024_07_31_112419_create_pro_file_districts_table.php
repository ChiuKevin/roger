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
        Schema::create('pro_file_districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_file_id');
            $table->unsignedBigInteger('district_id');

            $table->foreign('pro_file_id')->references('id')->on('pro_files');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_file_districts');
    }
};
