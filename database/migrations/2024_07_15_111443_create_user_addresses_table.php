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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('使用者ID');
            $table->string('contact_name')->comment('聯絡人');
            $table->string('email')->comment('電郵地址');
            $table->string('country_code', 5)->comment('手機國碼');
            $table->string('phone', 15)->comment('手機號碼');
            $table->string('address_line1')->comment('地址欄1：大廈名稱');
            $table->string('address_line2')->comment('地址欄2：樓層/單位/室');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
