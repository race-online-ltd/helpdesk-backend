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
        Schema::create('team_mapping_for_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->unsignedBigInteger('team_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            
            // Add indexes for faster lookups
            $table->index('company_id');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('team_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_mapping_for_partners');
    }
};
