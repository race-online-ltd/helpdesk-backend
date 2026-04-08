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
        Schema::create('user_dump_mq_to_locals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sid')->unique;
            $table->string('pppoe_name')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('entity_id')->nullable();
            $table->string('entity_code')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_dump_mq_to_locals');
    }
};
