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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->char('region', 2)->comment('地區');
            $table->unsignedTinyInteger('link_type')->default(0)->comment('類型 1:內連導航 2:外部連結');
            $table->string('name')->nullable()->comment('廣告名稱');
            $table->unsignedInteger('position_type')->default(0)->comment('位置類型 1:首頁 2:工種目錄頁');
            $table->unsignedInteger('position_id')->comment('首頁或工種目錄頁的位置ID，例如0:上方 1:中間 2:底部');
            $table->unsignedBigInteger('menu_id')->nullable()->comment('工種目錄ID，僅當 position_type = 2 時有效');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('image')->nullable()->comment('圖片');
            $table->string('link')->nullable()->comment('廣告連結路徑');
            $table->boolean('is_disabled')->default(0)->comment('是否停用 0:啟用(預設) 1:停用');
            $table->timestamp('start_time')->comment('開始時間');
            $table->timestamp('end_time')->comment('結束時間');
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('job_category_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
