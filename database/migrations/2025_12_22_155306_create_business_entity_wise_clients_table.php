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
        Schema::create('business_entity_wise_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_entity_id');
            $table->unsignedInteger('client_id');
            $table->string('client_name');
            $table->timestamps();
            $table->unique(['business_entity_id', 'client_id']);

           $table->index('client_name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_entity_wise_clients');
    }
};
