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
        Schema::create('entity_category_subcategory_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->string('category_id');
            $table->string('sub_category_id');
            $table->integer('is_client_visible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_category_subcategory_mappings');
    }
};
