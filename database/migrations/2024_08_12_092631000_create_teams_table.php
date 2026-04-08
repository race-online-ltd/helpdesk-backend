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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('team_name')->unique();
            $table->string('group_email')->nullable()->index();
             // JSON emails
            $table->json('additional_email')->nullable();

            // Relations
            $table->foreignId('department_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('division_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Idle time window
            $table->integer('idle_start_hr');
            $table->integer('idle_start_min');
            $table->integer('idle_end_hr');
            $table->integer('idle_end_min');
            $table->integer('idle_start_end_diff_min');

            $table->timestamps();

          // index
          $table->index('department_id');
          $table->index('division_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
