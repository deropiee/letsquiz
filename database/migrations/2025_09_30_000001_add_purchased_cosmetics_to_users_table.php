<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // json kolommen voor aangekochte cosmetics
            $table->json('purchased_avatars')->nullable();
            $table->json('purchased_theme_colors')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['purchased_avatars', 'purchased_theme_colors']);
        });
    }
};
