<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mr_allocated_doctors', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('specialization');
            $table->string('state')->nullable()->after('qualification');
            $table->string('city')->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('mr_allocated_doctors', function (Blueprint $table) {
            $table->dropColumn(['qualification', 'state', 'city']);
        });
    }
};
