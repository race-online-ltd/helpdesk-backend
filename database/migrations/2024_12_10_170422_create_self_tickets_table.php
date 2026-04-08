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
        Schema::create('self_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticket_number')->unique();
            $table->integer('user_id');
            $table->unsignedInteger('business_entity_id');
            $table->unsignedInteger('client_id_helpdesk');
            $table->string('client_id_vendor')->nullable();
            $table->string('sid')->nullable();
            $table->integer('source_id')->nullable();
            $table->unsignedInteger('cat_id')->nullable();
            $table->unsignedInteger('subcat_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('team_id')->nullable();
            $table->string('ref_ticket_no')->nullable();
            $table->string('priority_name');
            $table->text('note')->nullable();
            $table->string('attached_filename')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_tickets');
    }
};
