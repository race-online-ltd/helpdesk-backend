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
        Schema::create('first_res_slas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_number')->index();

            $table->foreignId('first_res_config_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->index();

            $table->tinyInteger('sla_status')
                  ->comment('0=failed,1=success,2=started')
                  ->index();
            $table->timestamps();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_res_slas');
    }
};
