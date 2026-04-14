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
        Schema::table('mr_allocated_doctors', function (Blueprint $table) {
            //
            $table->text('competitor_activity')->nullable()->after('vorxar_rx_per_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mr_allocated_doctors', function (Blueprint $table) {
            //
        });
    }
};
