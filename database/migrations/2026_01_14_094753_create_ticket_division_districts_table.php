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
        Schema::create('ticket_division_districts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_number');
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();

            $table->timestamps();

            // Optional: index for faster lookup
            $table->index('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_division_districts');
    }
};
