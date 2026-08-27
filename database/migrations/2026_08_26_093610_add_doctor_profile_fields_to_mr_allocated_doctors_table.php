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

            $table->string('wall_doctor', 10)->nullable()->after('qualification');

            $table->string('trade_govt_corporate', 50)->nullable()->after('wall_doctor');

            $table->unsignedInteger('national_regional_speaker_exp')
                ->nullable()
                ->after('trade_govt_corporate');

            $table->string('engaged_as_2026_faculty', 20)
                ->nullable()
                ->after('national_regional_speaker_exp');

            $table->decimal('lipaglyn_rx_per_month', 8, 2)
                ->nullable()
                ->after('engaged_as_2026_faculty');

            $table->decimal('lipaglyn_rx_trend', 8, 2)
                ->nullable()
                ->after('lipaglyn_rx_per_month');

            $table->text('lipaglyn_indication')
                ->nullable()
                ->after('lipaglyn_rx_trend');

            $table->string('mobile_number', 10)
                ->nullable()
                ->after('lipaglyn_indication');

            $table->date('key_dr_birthday')
                ->nullable()
                ->after('mobile_number');

            $table->string('hobby', 255)
                ->nullable()
                ->after('key_dr_birthday');
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
