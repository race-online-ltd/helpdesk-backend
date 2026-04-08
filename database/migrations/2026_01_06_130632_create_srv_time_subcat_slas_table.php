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
        Schema::create('srv_time_subcat_slas', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->index();
            $table->unsignedBigInteger('sla_subcat_config_id')->index();
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
        Schema::dropIfExists('srv_time_subcat_slas');
    }
};
