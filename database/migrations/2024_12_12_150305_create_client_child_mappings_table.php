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
        Schema::create('client_child_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_entity_id');
            $table->integer('client_id_helpdesk');
            $table->string('client_id_vendor');
            $table->string('client_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_child_mappings');
    }
};
