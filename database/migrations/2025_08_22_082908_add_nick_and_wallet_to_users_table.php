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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nick')->nullable()->unique()->after('name');     // ник (можно пустым)
            $table->string('wallet', 64)->nullable()->index()->after('email'); // реквизиты для P2P
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nick']);
            $table->dropIndex(['wallet']);
            $table->dropColumn(['nick', 'wallet']);
        });
    }
};
