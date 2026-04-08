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
        Schema::create('ticket_assign_agent_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_number')->index();
            $table->integer('assigned_in')->nullable();
            $table->integer('assigned_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_assign_agent_logs');
    }
};
