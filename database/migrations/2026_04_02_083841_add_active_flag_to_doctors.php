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
        Schema::table('mr_allocated_doctors', function (Blueprint $table) {
            //
            $table->boolean('is_active')->default(0)->after('mr_id');

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
