<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mr_allocated_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('mr_id')->nullable()->index();
            $table->string('msl_code', 50)->nullable();
            $table->string('name', 100);
            $table->string('specialization', 100)->nullable();
            $table->string('lipaglyn_rx_br_type', 50)->nullable();
            $table->decimal('avg_lipaglyn_pr_month', 8, 2)->nullable();
            $table->string('actual_speciality', 100)->nullable();
            $table->integer('Diabetes_patients_day')->nullable();
            $table->string('kol_kbl', 20)->nullable();
            $table->string('inst_dr', 30)->nullable();
            $table->string('govt_dropdown', 200)->nullable();
            $table->decimal('udca_rx_per_month', 8, 2)->nullable();
            $table->decimal('sema_rx_prer_month', 8, 2)->nullable();
            $table->decimal('other_saro_rm_per_month', 8, 2)->nullable();
            $table->decimal('total_business_value', 8, 2)->nullable();
            $table->string('planned_for_conversition', 30)->nullable();
            $table->decimal('incremental_lipaglyn_busines', 8, 2)->nullable();
            $table->decimal('everage_lipaglyn_pr_month', 8, 2)->nullable();
            $table->decimal('bilypsa_rx_per_month', 8, 2)->nullable();
            $table->decimal('linvas_rx_per_month', 8, 2)->nullable();
            $table->decimal('vorxar_rx_per_month', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mr_allocated_doctors');
    }
};
