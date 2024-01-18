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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('workflow_id')->constrained();
            $table->foreignId('user_assigned')->nullable()->default(null)->constrained('users');
            $table->foreignId('role_assigned')->nullable()->default(null)->constrained('roles');
            $table->text('description')->nullable()->default(null);
            $table->string('color')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
