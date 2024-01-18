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
        Schema::create('workflow_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_action_id')->nullable()->constrained();
            $table->foreignId('workflow_step_id')->constrained();
            $table->foreignId('user_actor')->references('id')->on('users');
            $table->foreignId('next_user_responsible')->nullable()->references('id')->on('users');
            $table->json('data')->nullable();
            $table->morphs('associate');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_records');
    }
};
