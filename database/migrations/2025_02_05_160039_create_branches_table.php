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
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('business_entity_id');
            $table->unsignedInteger('vendor_client_id');
            $table->string('branch_name');
            $table->string('mobile1')->nullable();
            $table->string('mobile2')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->text('service_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
