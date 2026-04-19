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
        Schema::create('merchant_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('使用者ID');
            $table->unsignedBigInteger('job_category_id')->comment('工種ID');
            $table->json('name')->comment('商家名稱');
            $table->json('description')->comment('商家名稱');
            $table->json('image')->comment('商家大頭照');
            $table->unsignedBigInteger('district_id')->comment('商家地區ID');
            $table->json('address')->comment('商家地址');
            $table->string('country_code', 5)->comment('手機國碼');
            $table->string('phone', 15)->comment('手機號碼');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_files');
    }
};
