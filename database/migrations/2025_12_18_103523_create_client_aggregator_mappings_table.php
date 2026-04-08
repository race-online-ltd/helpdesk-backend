<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_aggregator_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('business_entity_id');
            $table->string('client_id');
            $table->string('aggregator_id');
            $table->timestamps();

            // $table->foreign('aggregator_id')
            //       ->references('id')
            //       ->on('aggregators')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_aggregator_mappings');
    }
};
