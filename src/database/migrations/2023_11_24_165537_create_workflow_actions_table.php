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
        Schema::create('workflow_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('workflow_step_id')->constrained();
            $table->string('next_workflow_step_name')->nullable()->default(null);
            $table->json('data')->nullable();
            $table->text('description')->nullable()->default(null);
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_actions');
    }
};
