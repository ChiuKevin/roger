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
        Schema::create('pro_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_category_id');
            $table->string('name')->comment('姓名或公司名稱');
            $table->string('title')->comment('標題');
            $table->json('images')->comment('圖片連結');
            $table->unsignedInteger('experience')->comment('工作經驗');
            $table->unsignedTinyInteger('teams')->comment('團隊人數 1:1, 2:2~50, 3:51~100, 4:100+');
            $table->unsignedBigInteger('district_id')->comment('門市地區id');
            $table->string('street')->comment('門市街道名稱及門牌號碼');
            $table->string('building')->comment('門市所在大樓');
            $table->string('floor')->comment('門市樓層, 室');
            $table->text('business_hours')->comment('門市營業時間');
            $table->unsignedTinyInteger('is_remote')->comment('是否遠端');
            $table->text('introduction')->comment('專家介紹');
            $table->json('qa')->comment('Q&A');
            $table->unsignedTinyInteger('identity_type')->comment('1:永久住民 2:其他人士 3:公司/機構');
            $table->unsignedTinyInteger('is_verified')->comment('是否驗證 0:未驗證 1:已驗證');
            $table->unsignedTinyInteger('is_disabled')->comment('是否停用 0:啟用(預設) 1:停用');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('job_category_id')->references('id')->on('job_categories');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_files');
    }
};
