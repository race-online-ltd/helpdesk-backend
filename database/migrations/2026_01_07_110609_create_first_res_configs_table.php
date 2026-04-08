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
        Schema::create('first_res_configs', function (Blueprint $table) {
            $table->id();

              $table->foreignId('team_id')
                    ->constrained()
                    ->cascadeOnDelete()
                    ->index();

              $table->integer('duration_min'); 

              $table->boolean('first_response_status')->default(1);
              $table->boolean('escalation_status')->default(0);

              $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_res_configs');
    }
};
