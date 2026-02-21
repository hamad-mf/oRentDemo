<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('address');      // 1–5 stars
            $table->boolean('is_blacklisted')->default(false)->after('rating');       // blacklisted flag
            $table->string('blacklist_reason')->nullable()->after('is_blacklisted'); // reason text
            $table->text('notes')->nullable()->after('blacklist_reason');            // internal notes
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['rating', 'is_blacklisted', 'blacklist_reason', 'notes']);
        });
    }
};
