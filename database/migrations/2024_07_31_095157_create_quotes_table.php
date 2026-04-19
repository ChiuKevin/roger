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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->comment('1:等待專家報價 2:待聘用 3:待評價 4:已完成 5:已過期 6:已取消');
            $table->unsignedBigInteger('job_category_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('credits')->default(0)->comment('需花費點數');
            $table->json('qna')->comment('使用者問答');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('job_category_id')->references('id')->on('job_categories');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
