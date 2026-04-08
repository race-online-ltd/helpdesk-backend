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
        Schema::create('ticket_aggregators', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aggregator_id');
            $table->unsignedBigInteger('ticket_number');
            $table->timestamps();

            $table->index('aggregator_id');
            $table->index('ticket_number');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_aggregators');
    }
};
