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
        Schema::create('user_client_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_entity_id'); // Column for type
            $table->unsignedInteger('user_id'); // Unique username
            $table->string('client_id'); // Column for fullname
            $table->string('client_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_client_mappings');
    }
};
