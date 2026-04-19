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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 60)->comment('姓名');
            $table->string('email', 100)->unique()->comment('電子郵件');
            $table->char('password', 60)->comment('密碼');
            $table->string('image')->nullable()->comment('個人圖片');
            $table->string('remark', 100)->nullable()->comment('備註');
            $table->boolean('is_disabled')->default(0)->comment('是否停用 0:啟用(預設) 1:停用');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
