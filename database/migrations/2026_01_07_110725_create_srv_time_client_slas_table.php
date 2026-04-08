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
        Schema::create('srv_time_client_slas', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->index();

          $table->foreignId('sla_client_config_id')
          ->constrained('sla_client_configs')
          ->cascadeOnDelete()
          ->name('fk_srv_time_client_slas_sla_client_configs');

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
        Schema::dropIfExists('srv_time_client_slas');
    }
};
