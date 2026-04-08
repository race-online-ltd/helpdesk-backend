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
        Schema::create('sla_client_configs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('business_entity_id');
            $table->string('client_id');

            $table->integer('resolution_min');
            $table->string('sla_status');
            $table->string('escalation_status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_client_configs');
    }
};
