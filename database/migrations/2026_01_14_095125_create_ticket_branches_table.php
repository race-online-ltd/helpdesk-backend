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
        Schema::create('ticket_branches', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_number');
            $table->unsignedBigInteger('branch_id');

            $table->timestamps();

            // Indexes for performance
            $table->index('ticket_number');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_branches');
    }
};
