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
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->comment('使用者ID');

            // Email通知設定
            $table->boolean('email_new_quote')->default(1)->comment('電郵通知-新的報價');
            $table->boolean('email_quote_updated')->default(1)->comment('電郵通知-報價狀態更新');
            $table->boolean('email_new_message')->default(1)->comment('電郵通知-專家留言');
            $table->boolean('email_info')->default(1)->comment('電郵通知-優惠資訊');

            // 推送通知設定
            $table->boolean('push_new_quote')->default(1)->comment('通知設定-新的報價');
            $table->boolean('push_quote_updated')->default(1)->comment('通知設定-報價狀態更新');
            $table->boolean('push_new_message')->default(1)->comment('通知設定-專家留言');
            $table->boolean('push_system')->default(1)->comment('通知設定-系統通知');

            // 短訊通知設定
            $table->boolean('sms_quote_updated')->default(1)->comment('短訊設定-報價狀態更新');
            $table->boolean('sms_booking_success')->default(1)->comment('短訊設定-預約成功');

            // 專家電郵設定
            $table->boolean('pro_email_credit_refund')->default(1)->comment('專家電郵設定-退回金幣');
            $table->boolean('pro_email_new_request')->default(1)->comment('專家電郵設定-新的服務要求');
            $table->boolean('pro_email_quote_updated')->default(1)->comment('專家電郵設定-報價狀態更新');
            $table->boolean('pro_email_quote_viewed')->default(1)->comment('專家電郵設定-報價被查看');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};
