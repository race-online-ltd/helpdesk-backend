<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('team_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete()->unique();
            $table->json('sla_hold_agents')->nullable();
            $table->json('reopen_agents')->nullable();
            $table->json('merge_agents')->nullable();
            $table->json('escalate_agents')->nullable();
            $table->json('additional_emails')->nullable();
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('team_configs');
    }
};
