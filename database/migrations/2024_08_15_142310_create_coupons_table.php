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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code', 50)->unique()->comment('折扣券代碼，必須唯一');
            $table->enum('discount_type', ['fixed', 'percentage'])->comment('折扣類型 fixed:固定金額 percentage:百分比折扣');
            $table->decimal('discount_value', 10, 2)->comment('折扣值，金額或百分比');
            $table->decimal('min_purchase_amount', 10, 2)->nullable()->comment('使用折扣券的最小消費金額，如果為NULL則無最小消費額要求');
            $table->date('valid_from')->comment('折扣券有效期的開始日期');
            $table->date('valid_until')->comment('折扣券有效期的結束日期');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
