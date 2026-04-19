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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 60)->nullable()->comment('姓名');
            $table->string('nickname', 60)->nullable()->comment('暱稱');
            $table->string('country_code', 5)->comment('手機國碼');
            $table->string('phone', 15)->comment('手機號碼');
            $table->string('email', 100)->nullable()->unique()->comment('電子郵件');
            $table->char('password', 60)->nullable()->comment('密碼');
            $table->string('image')->nullable()->comment('個人圖片');
            $table->json('tags')->nullable()->comment('標籤');
            $table->string('remark', 100)->nullable()->comment('備註');
            $table->boolean('is_pro')->default(0)->comment('是否為專家 0:否(預設) 1:是');
            $table->boolean('is_disabled')->default(0)->comment('是否停用 0:啟用(預設) 1:停用');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['country_code', 'phone'], 'country_code_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
