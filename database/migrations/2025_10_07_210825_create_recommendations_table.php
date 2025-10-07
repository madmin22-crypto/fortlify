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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('priority');
            $table->integer('impact_score');
            $table->integer('effort_score');
            $table->string('title');
            $table->text('description');
            $table->text('how_to_fix');
            $table->json('technical_details')->nullable();
            $table->timestamps();
            
            $table->index(['audit_id', 'priority']);
            $table->index(['priority', 'impact_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
