<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('articles', 'is_visible')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->boolean('is_visible')->default(true)->after('image_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('articles', 'is_visible')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('is_visible');
            });
        }
    }
};
