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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->char('region', 2)->comment('地區');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('上級ID 預設0即最頂層');
            $table->integer('level')->comment('區域層級。1:省/直轄市/縣/地域 2:鄉/鎮/市/區 3:細分地區');

            $table->index(['region', 'level']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
