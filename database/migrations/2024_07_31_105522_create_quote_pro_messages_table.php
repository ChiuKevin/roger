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
        Schema::create('quote_pro_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_pro_id')->comment('需求專家id');
            $table->unsignedBigInteger('sender_id')->comment('發送人id');
            $table->unsignedTinyInteger('type')->comment('訊息類型 1:文字 2:圖片 3:影片');
            $table->text('message')->comment('訊息內容，對於圖片和影片為URL');
            $table->unsignedTinyInteger('is_read')->default(0)->comment('是否已讀 0:未讀 1:已讀');
            $table->timestamps();

            $table->foreign('quote_pro_id')->references('id')->on('quote_pros');
            $table->foreign('sender_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_pro_messages');
    }
};
