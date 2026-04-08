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
        Schema::create('customer_parent_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('client_id');
            $table->string('client_name');
            $table->unsignedInteger('user_id')->unique;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_parent_mappings');
    }
};
