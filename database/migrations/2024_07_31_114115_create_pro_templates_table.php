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
        Schema::create('pro_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->comment('標題');
            $table->unsignedTinyInteger('type')->comment('選項類型 1:價格/單位 2:範圍價格/單位 3:可商討');
            $table->string('price')->comment('如果是可商討 則寫入可商討相關說明');
            $table->unsignedTinyInteger('unit')->comment('單位類型 0:自定義 其他系統預設');
            $table->string('custom_unit')->comment('自定義單位');
            $table->text('description')->comment('專家描述');
            $table->json('images')->comment('圖片連結');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_templates');
    }
};
