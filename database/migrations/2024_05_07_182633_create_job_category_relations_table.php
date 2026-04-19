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
        Schema::create('job_category_relations', function (Blueprint $table) {
            $table->id();
            $table->biginteger('menu_id')->unsigned()->comment('目錄ID');
            $table->biginteger('category_id')->unsigned()->comment('分類ID');
            $table->boolean('is_primary')->default(0)->comment('是否為主要關聯 0:否(預設) 1:是');

            $table->foreign('menu_id')->references('id')->on('job_category_menus');
            $table->foreign('category_id')->references('id')->on('job_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_category_relations');
    }
};
