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
        Schema::create('self_ticket_orbits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticket_number')->unique();
            $table->string('client_type');
            $table->unsignedInteger('client_id_helpdesk');
            $table->string('client_id_vendor');
            $table->string('sid_uid')->nullable();
            $table->string('billing_source');
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->index('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_ticket_orbits');
    }
};
