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
        Schema::create('sla_subcategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('sla_id');
            $table->unsignedInteger('business_entity_id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('subcat_id');
            $table->unsignedInteger('fr_res_day');
            $table->unsignedInteger('fr_res_hr');
            $table->unsignedInteger('fr_res_min');
            $table->unsignedInteger('fr_res_time_min');
            $table->string('fr_res_time_str');
            $table->unsignedInteger('srv_day');
            $table->unsignedInteger('srv_hr');
            $table->unsignedInteger('srv_min');
            $table->unsignedInteger('srv_time_min');
            $table->string('srv_time_str');
            $table->boolean('esc_status');
            $table->boolean('status');
            $table->unsignedInteger('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_subcategories');
    }
};
