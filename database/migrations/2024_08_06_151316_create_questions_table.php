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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('category')->comment('constants.QUESTION_CATEGORY');
            $table->unsignedTinyInteger('type')->comment('constants.QUESTION_TYPE');
            $table->boolean('is_addable')->default(false)->comment('是否可動態新增 0:否 1:是');
            $table->boolean('is_custom')->default(false)->comment('是否可客製選項 0:否 1:是');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
