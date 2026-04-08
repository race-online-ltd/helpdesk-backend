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
        Schema::create('reopen_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_number');
            $table->unsignedBigInteger('reopened_by');
            $table->text('note');
            $table->timestamps();

            $table->index('reopened_by');
            $table->index('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reopen_tickets');
    }
};
