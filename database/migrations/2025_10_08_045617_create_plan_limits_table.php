<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_limits', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->unique();
            $table->integer('pages_per_month');
            $table->timestamps();
        });

        // Seed default plan limits
        DB::table('plan_limits')->insert([
            ['plan_name' => 'free', 'pages_per_month' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['plan_name' => 'starter', 'pages_per_month' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['plan_name' => 'growth', 'pages_per_month' => 500, 'created_at' => now(), 'updated_at' => now()],
            ['plan_name' => 'onetime', 'pages_per_month' => 50, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_limits');
    }
};
