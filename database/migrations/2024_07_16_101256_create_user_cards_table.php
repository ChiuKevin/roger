<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('使用者ID');
            $table->char('region', 2)->comment('地區');
            $table->unsignedTinyInteger('provider')->comment('第三方 1:綠界 2:BBMSL。保留此欄位，第三方更換時可以切換');
            $table->char('card_last_four', 4)->comment('卡號後四位數字');
            $table->string('brand')->comment('發卡組織');
            $table->char('exp_month', 2)->comment('到期月');
            $table->char('exp_year', 2)->comment('到期年');
            $table->string('card_token')->comment('卡片唯一識別碼');
            $table->boolean('is_default')->default(0)->comment('是否為預設卡片 預設0:否 1:是');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
