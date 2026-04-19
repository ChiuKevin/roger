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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('provider')->comment('發送平台 1:Every8d 2:AboSEND');
            $table->string('country_code', 5)->comment('手機國碼');
            $table->string('phone', 15)->comment('手機號碼');
            $table->char('sms_code', 6)->comment('驗證碼');
            $table->string('batch_id')->nullable()->comment('批次號');
            $table->text('send_response')->nullable()->comment('平台請求訊息');
            $table->text('callback_response')->nullable()->comment('平台回調訊息');
            $table->tinyInteger('status')->comment('發送狀態 1.已發送 2.未發送 3.發送成功 4.發送失敗');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
