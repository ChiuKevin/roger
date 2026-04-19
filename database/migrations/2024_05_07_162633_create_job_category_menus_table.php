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
        Schema::create('job_category_menus', function (Blueprint $table) {
            $table->id();
            $table->char('region', 2)->comment('地區');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('工種上級ID 預設0即最頂層');
            $table->string('image')->nullable()->comment('圖片');
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
        Schema::dropIfExists('job_category_menus');
    }
};
