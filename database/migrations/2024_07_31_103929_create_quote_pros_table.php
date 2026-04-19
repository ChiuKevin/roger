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
        Schema::create('quote_pros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('pro_id');
            $table->unsignedTinyInteger('type')->comment('選項類型 1:價格/單位 2:範圍價格/單位 3:可商討');
            $table->string('price')->comment('如果是可商討 則寫入可商討相關說明');
            $table->unsignedTinyInteger('unit')->comment('單位類型 0:自定義 其他系統預設');
            $table->string('custom_unit')->nullable()->comment('自定義單位');
            $table->unsignedTinyInteger('is_hired')->comment('是否聘用');
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('pro_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_pros');
    }
};
