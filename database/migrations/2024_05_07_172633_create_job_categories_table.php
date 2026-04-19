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
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->char('region', 2)->comment('地區');
            $table->tinyInteger('type')->default(1)->comment('類別 1:個人專家(預設) 2:商店 3.平台家居接單');
            $table->unsignedInteger('price')->default(1)->comment('報價金幣價格');
            $table->string('image')->nullable()->comment('圖片');
            $table->boolean('is_hot')->default(0)->comment('是否熱門 0:否(預設) 1:是');
            $table->tinyInteger('sort')->default(0)->comment('排序 預設0');
            $table->boolean('is_disabled')->default(0)->comment('是否停用 0:否(預設) 1:是');
            $table->string('creator', 60)->nullable()->comment('創建人員');
            $table->string('updater', 60)->nullable()->comment('更新人員');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_categories');
    }
};
