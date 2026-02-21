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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('monthly_rate', 10, 2)->nullable()->after('daily_rate');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('rental_type', ['daily', 'monthly'])->default('daily')->after('vehicle_id');
            $table->date('actual_end_date')->nullable()->after('end_date');
            $table->decimal('overdue_amount', 10, 2)->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('monthly_rate');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['rental_type', 'actual_end_date', 'overdue_amount']);
        });
    }
};
