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
        Schema::create('quote_pro_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_pro_id');
            $table->unsignedBigInteger('pro_tag_id');
            $table->timestamps();

            $table->foreign('quote_pro_id')->references('id')->on('quote_pros');
            $table->foreign('pro_tag_id')->references('id')->on('pro_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_pro_tags');
    }
};
